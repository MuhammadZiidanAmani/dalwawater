<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'harga_modal',
        'harga_jual',
        'stok',
        'satuan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'harga_modal' => 'integer',
            'harga_jual' => 'integer',
            'stok' => 'integer',
        ];
    }

    public function stockIns(): HasMany
    {
        return $this->hasMany(StockIn::class);
    }

    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
