<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockIn extends Model
{
    protected $fillable = [
        'product_id',
        'qty',
        'supplier',
        'tanggal',
        'keterangan',
        'nota_pembelian',
        'surat_jalan',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'integer',
            'tanggal' => 'date',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
