<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CashierReportAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_history_only_contains_their_own_transactions(): void
    {
        $cashier = $this->cashier('cashier-one');
        $otherCashier = $this->cashier('cashier-two');
        $ownTransaction = $this->transaction($cashier, 'TRX-OWN-0001', 100000);
        $otherTransaction = $this->transaction($otherCashier, 'TRX-OTHER-0001', 900000);

        $response = $this->actingAs($cashier)->get('/?'.http_build_query([
            'page' => 'history',
            'start_date' => now()->toDateString(),
            'end_date' => now()->toDateString(),
            'user_id' => $otherCashier->id,
        ]));

        $response->assertOk()
            ->assertSee('Riwayat Saya')
            ->assertSee($ownTransaction->kode_transaksi)
            ->assertDontSee($otherTransaction->kode_transaksi)
            ->assertSee('Rp 100.000')
            ->assertDontSee('Rp 900.000')
            ->assertDontSee('Semua user')
            ->assertDontSee('Excel');
    }

    public function test_cashier_can_only_open_their_own_receipt(): void
    {
        $cashier = $this->cashier('cashier-one');
        $otherCashier = $this->cashier('cashier-two');
        $ownTransaction = $this->transaction($cashier, 'TRX-OWN-0001', 100000);
        $otherTransaction = $this->transaction($otherCashier, 'TRX-OTHER-0001', 900000);

        $this->actingAs($cashier)
            ->get(route('transactions.receipt', $ownTransaction))
            ->assertOk();

        $this->actingAs($cashier)
            ->get(route('transactions.receipt', $otherTransaction))
            ->assertForbidden();
    }

    public function test_cashier_cannot_export_or_delete_transactions(): void
    {
        $cashier = $this->cashier('cashier-one');
        $transaction = $this->transaction($cashier, 'TRX-OWN-0001', 100000);

        $this->actingAs($cashier)
            ->get(route('transactions.export-excel'))
            ->assertForbidden();

        $this->actingAs($cashier)
            ->delete(route('transactions.destroy', $transaction))
            ->assertForbidden();
    }

    public function test_admin_can_view_another_cashiers_receipt(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'role' => 'admin',
            'status' => 'active',
        ]);
        $cashier = $this->cashier('cashier-one');
        $transaction = $this->transaction($cashier, 'TRX-CASHIER-0001', 100000);

        $this->actingAs($admin)
            ->get(route('transactions.receipt', $transaction))
            ->assertOk();
    }

    public function test_cashier_navigation_is_simple_and_profile_is_read_only(): void
    {
        $cashier = $this->cashier('cashier-one');

        $this->actingAs($cashier)
            ->get('/')
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Transaksi')
            ->assertSee('Data Stok')
            ->assertSee('Riwayat Saya')
            ->assertSee('Informasi Akun')
            ->assertDontSee('Manajemen Barang')
            ->assertDontSee('Edit Profil');

        $this->actingAs($cashier)
            ->put(route('profile.update'), [
                'name' => 'Nama Baru',
                'username' => 'username-baru',
                'email' => 'baru@example.com',
            ])
            ->assertForbidden();
    }

    public function test_cashier_can_change_their_own_password(): void
    {
        $cashier = $this->cashier('cashier-one');

        $this->actingAs($cashier)
            ->put(route('password.update'), [
                'current_password' => 'password',
                'password' => 'password-baru',
                'password_confirmation' => 'password-baru',
            ])
            ->assertRedirect(route('dashboard', ['page' => 'account-password']));

        $this->assertTrue(Hash::check('password-baru', $cashier->fresh()->password));
    }

    public function test_admin_navigation_keeps_management_features(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->get('/')
            ->assertOk()
            ->assertSee('Manajemen Barang')
            ->assertSee('Laporan')
            ->assertSee('Manajemen User')
            ->assertDontSee('Pengaturan')
            ->assertSee('Edit Profil');
    }

    public function test_cashier_can_create_an_unpaid_transaction(): void
    {
        $cashier = $this->cashier('cashier-one');
        $product = Product::create([
            'kode_barang' => 'BRG-001',
            'nama_barang' => 'Air Mineral',
            'kategori' => 'Minuman',
            'harga_modal' => 3000,
            'harga_jual' => 5000,
            'stok' => 10,
            'satuan' => 'Botol',
            'status' => 'active',
        ]);

        $this->actingAs($cashier)
            ->post(route('transactions.store'), [
                'items' => [['product_id' => $product->id, 'qty' => 2]],
                'payment_type' => null,
                'payment_status' => 'pending',
                'discount_per_product' => 0,
                'customer_name' => 'Pelanggan Tempo',
            ])
            ->assertRedirect();

        $transaction = Transaction::sole();
        $this->assertSame('pending', $transaction->payment_status);
        $this->assertNull($transaction->payment_type);
        $this->assertNull($transaction->paid_at);
        $this->assertNull($transaction->settled_by);
    }

    public function test_cashier_can_settle_their_own_unpaid_transaction_once(): void
    {
        $cashier = $this->cashier('cashier-one');
        $transaction = Transaction::create([
            'kode_transaksi' => 'TRX-PENDING-0001',
            'user_id' => $cashier->id,
            'total' => 100000,
            'payment_type' => null,
            'payment_status' => 'pending',
            'uang_diterima' => 0,
            'kembalian' => 0,
        ]);

        $this->actingAs($cashier)
            ->put(route('transactions.settle', $transaction), [
                'payment_type' => 'cash',
                'uang_diterima' => 120000,
            ])
            ->assertRedirect(route('dashboard', ['page' => 'history']));

        $transaction->refresh();
        $this->assertSame('paid', $transaction->payment_status);
        $this->assertSame('cash', $transaction->payment_type);
        $this->assertSame(20000, $transaction->kembalian);
        $this->assertSame($cashier->id, $transaction->settled_by);
        $this->assertNotNull($transaction->paid_at);

        $this->actingAs($cashier)
            ->put(route('transactions.settle', $transaction), [
                'payment_type' => 'transfer',
            ])
            ->assertSessionHasErrors('payment_status');

        $this->assertSame('cash', $transaction->fresh()->payment_type);
    }

    public function test_cashier_cannot_settle_another_cashiers_transaction(): void
    {
        $cashier = $this->cashier('cashier-one');
        $otherCashier = $this->cashier('cashier-two');
        $transaction = Transaction::create([
            'kode_transaksi' => 'TRX-PENDING-0001',
            'user_id' => $otherCashier->id,
            'total' => 100000,
            'payment_type' => null,
            'payment_status' => 'pending',
            'uang_diterima' => 0,
            'kembalian' => 0,
        ]);

        $this->actingAs($cashier)
            ->put(route('transactions.settle', $transaction), [
                'payment_type' => 'cash',
                'uang_diterima' => 100000,
            ])
            ->assertForbidden();
    }

    public function test_admin_can_settle_any_cashiers_unpaid_transaction(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'role' => 'admin',
            'status' => 'active',
        ]);
        $cashier = $this->cashier('cashier-one');
        $transaction = Transaction::create([
            'kode_transaksi' => 'TRX-PENDING-0001',
            'user_id' => $cashier->id,
            'total' => 100000,
            'payment_type' => null,
            'payment_status' => 'pending',
            'uang_diterima' => 0,
            'kembalian' => 0,
        ]);

        $this->actingAs($admin)
            ->put(route('transactions.settle', $transaction), [
                'payment_type' => 'transfer',
                'bank_name' => 'BSI',
                'reference_number' => 'REF-001',
            ])
            ->assertRedirect(route('dashboard', ['page' => 'history']));

        $transaction->refresh();
        $this->assertSame('paid', $transaction->payment_status);
        $this->assertSame('transfer', $transaction->payment_type);
        $this->assertSame($admin->id, $transaction->settled_by);
    }

    public function test_transaction_export_links_include_the_active_filters(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'role' => 'admin',
            'status' => 'active',
        ]);
        $cashier = $this->cashier('cashier-one');
        $filters = [
            'start_date' => '2026-06-01',
            'end_date' => '2026-06-21',
            'user_id' => $cashier->id,
            'payment_status' => 'paid',
        ];

        $this->actingAs($admin)
            ->get('/?'.http_build_query(['page' => 'history', ...$filters]))
            ->assertOk()
            ->assertSee(route('transactions.export-excel', $filters))
            ->assertSee(route('transactions.export-pdf', $filters));
    }

    public function test_purchase_history_uses_the_selected_date_range(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'role' => 'admin',
            'status' => 'active',
        ]);
        $product = Product::create([
            'kode_barang' => 'BRG-001',
            'nama_barang' => 'Air Mineral',
            'kategori' => 'Minuman',
            'harga_modal' => 3000,
            'harga_jual' => 5000,
            'stok' => 10,
            'satuan' => 'Botol',
            'status' => 'active',
        ]);
        StockIn::create(['product_id' => $product->id, 'qty' => 10, 'supplier' => 'Supplier Juni', 'tanggal' => '2026-06-15']);
        StockIn::create(['product_id' => $product->id, 'qty' => 20, 'supplier' => 'Supplier Mei', 'tanggal' => '2026-05-15']);

        $response = $this->actingAs($admin)
            ->get('/?'.http_build_query([
                'page' => 'purchase-history',
                'start_date' => '2026-06-01',
                'end_date' => '2026-06-30',
            ]));

        $response->assertOk()->assertSee('Riwayat Pembelian');
        $purchaseHistory = $response->viewData('purchaseHistory');
        $this->assertTrue($purchaseHistory->contains('supplier', 'Supplier Juni'));
        $this->assertFalse($purchaseHistory->contains('supplier', 'Supplier Mei'));
    }

    private function cashier(string $username): User
    {
        return User::factory()->create([
            'username' => $username,
            'role' => 'cashier',
            'status' => 'active',
        ]);
    }

    private function transaction(User $user, string $code, int $total): Transaction
    {
        return Transaction::create([
            'kode_transaksi' => $code,
            'user_id' => $user->id,
            'total' => $total,
            'payment_type' => 'cash',
            'uang_diterima' => $total,
            'kembalian' => 0,
            'payment_status' => 'paid',
        ]);
    }
}
