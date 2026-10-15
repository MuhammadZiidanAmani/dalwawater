<?php

namespace Tests\Feature;

use App\Models\ApprovalRequest;
use App\Models\Expense;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_product_edit_waits_for_admin_approval(): void
    {
        $admin = $this->user('admin', 'admin');
        $cashier = $this->user('kasir', 'cashier');
        $product = Product::create([
            'kode_barang' => 'DW-001',
            'nama_barang' => 'Produk Lama',
            'kategori' => 'Air Mineral',
            'harga_modal' => 3000,
            'harga_jual' => 5000,
            'stok' => 10,
            'stok_minimal' => 5,
            'satuan' => 'Botol',
            'status' => 'active',
        ]);

        $this->actingAs($cashier)
            ->put(route('products.update', $product), [
                'kode_barang' => 'DW-001',
                'nama_barang' => 'Produk Baru',
                'kategori' => 'Air Mineral',
                'harga_modal' => 3000,
                'harga_jual' => 6000,
                'stok' => 10,
                'stok_minimal' => 5,
                'satuan' => 'Botol',
                'status' => 'active',
            ])
            ->assertRedirect(route('dashboard', ['page' => 'inventory']));

        $this->assertSame('Produk Lama', $product->fresh()->nama_barang);
        $approval = ApprovalRequest::sole();
        $this->assertSame('pending', $approval->status);
        $this->assertSame('Produk Baru', $approval->payload['nama_barang']);

        $this->actingAs($admin)
            ->put(route('approval-requests.approve', $approval))
            ->assertRedirect(route('dashboard', ['page' => 'approvals']));

        $this->assertSame('Produk Baru', $product->fresh()->nama_barang);
        $this->assertSame(6000, $product->fresh()->harga_jual);
        $this->assertSame('approved', $approval->fresh()->status);
        $this->assertSame($admin->id, $approval->fresh()->reviewer_id);
    }

    public function test_rejected_cashier_delete_keeps_expense(): void
    {
        $admin = $this->user('admin', 'admin');
        $cashier = $this->user('kasir', 'cashier');
        $expense = Expense::create([
            'user_id' => $cashier->id,
            'spent_at' => '2026-10-15',
            'category' => 'Operasional',
            'uraian' => 'Biaya kirim',
            'description' => 'Biaya kirim',
            'volume' => 1,
            'unit' => 'Kali',
            'price' => 20000,
            'amount' => 20000,
        ]);

        $this->actingAs($cashier)
            ->delete(route('expenses.destroy', $expense))
            ->assertRedirect(route('dashboard', ['page' => 'operations']));

        $approval = ApprovalRequest::sole();
        $this->assertDatabaseHas('expenses', ['id' => $expense->id]);

        $this->actingAs($admin)
            ->put(route('approval-requests.reject', $approval), ['review_notes' => 'Data masih diperlukan.'])
            ->assertRedirect(route('dashboard', ['page' => 'approvals']));

        $this->assertDatabaseHas('expenses', ['id' => $expense->id]);
        $this->assertSame('rejected', $approval->fresh()->status);
        $this->assertSame('Data masih diperlukan.', $approval->fresh()->review_notes);
    }

    public function test_admin_edit_is_applied_without_approval(): void
    {
        $admin = $this->user('admin', 'admin');
        $product = Product::create([
            'kode_barang' => 'DW-001',
            'nama_barang' => 'Produk Lama',
            'kategori' => 'Air Mineral',
            'harga_modal' => 3000,
            'harga_jual' => 5000,
            'stok' => 10,
            'stok_minimal' => 5,
            'satuan' => 'Botol',
            'status' => 'active',
        ]);

        $this->actingAs($admin)->put(route('products.update', $product), [
            'kode_barang' => 'DW-001',
            'nama_barang' => 'Produk Admin',
            'kategori' => 'Air Mineral',
            'harga_modal' => 3000,
            'harga_jual' => 5000,
            'stok' => 10,
            'stok_minimal' => 5,
            'satuan' => 'Botol',
            'status' => 'active',
        ])->assertRedirect(route('dashboard', ['page' => 'inventory']));

        $this->assertSame('Produk Admin', $product->fresh()->nama_barang);
        $this->assertDatabaseCount('approval_requests', 0);
    }

    private function user(string $username, string $role): User
    {
        return User::factory()->create([
            'username' => $username,
            'role' => $role,
            'status' => 'active',
        ]);
    }
}
