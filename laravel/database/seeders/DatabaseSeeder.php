<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(LoginUserSeeder::class);

        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin Dalwa',
                'email' => 'admin@dalwa-water.local',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        $cashier = User::updateOrCreate(
            ['username' => 'kasir'],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'kasir@dalwa-water.local',
                'password' => Hash::make('password'),
                'role' => 'cashier',
                'status' => 'active',
            ]
        );

        $products = collect([
            ['kode_barang' => 'DW-GLN-19', 'nama_barang' => 'Isi Ulang Galon 19L', 'kategori' => 'Galon', 'harga_modal' => 9500, 'harga_jual' => 15000, 'stok' => 1245, 'satuan' => 'Galon', 'status' => 'active'],
            ['kode_barang' => 'DW-BTL-600', 'nama_barang' => 'Botol Air 600ml (Dus)', 'kategori' => 'Botol', 'harga_modal' => 36000, 'harga_jual' => 48000, 'stok' => 86, 'satuan' => 'Dus', 'status' => 'active'],
            ['kode_barang' => 'DW-GLN-BRU', 'nama_barang' => 'Galon Baru + Isi', 'kategori' => 'Galon', 'harga_modal' => 42000, 'harga_jual' => 55000, 'stok' => 32, 'satuan' => 'Unit', 'status' => 'active'],
            ['kode_barang' => 'DW-CUP-220', 'nama_barang' => 'Air Cup 220ml (Dus)', 'kategori' => 'Cup', 'harga_modal' => 22000, 'harga_jual' => 30000, 'stok' => 14, 'satuan' => 'Dus', 'status' => 'active'],
        ])->map(fn (array $data) => Product::updateOrCreate(['kode_barang' => $data['kode_barang']], $data));

        if (StockIn::count() === 0) {
            StockIn::create([
                'product_id' => $products->first()->id,
                'qty' => 120,
                'supplier' => 'Gudang Dalwa',
                'tanggal' => now()->toDateString(),
                'keterangan' => 'Restock awal sistem.',
            ]);
        }

        if (Transaction::count() === 0) {
            $transaction = Transaction::create([
                'kode_transaksi' => 'TRX-'.now()->format('ymd').'-0001',
                'user_id' => $cashier->id,
                'total' => 78000,
                'payment_type' => 'cash',
                'uang_diterima' => 100000,
                'kembalian' => 22000,
                'payment_status' => 'paid',
            ]);

            $transaction->details()->create([
                'product_id' => $products[0]->id,
                'qty' => 2,
                'harga' => 15000,
                'subtotal' => 30000,
            ]);

            $transaction->details()->create([
                'product_id' => $products[1]->id,
                'qty' => 1,
                'harga' => 48000,
                'subtotal' => 48000,
            ]);

            $admin->transactions()->create([
                'kode_transaksi' => 'TRX-'.now()->format('ymd').'-0002',
                'total' => 55000,
                'payment_type' => 'transfer',
                'uang_diterima' => 55000,
                'kembalian' => 0,
                'bank_name' => 'BSI',
                'reference_number' => 'REF-SEED-001',
                'payment_status' => 'paid',
            ])->details()->create([
                'product_id' => $products[2]->id,
                'qty' => 1,
                'harga' => 55000,
                'subtotal' => 55000,
            ]);
        }
    }
}
