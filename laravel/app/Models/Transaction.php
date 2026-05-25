<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'kode_transaksi',
        'user_id',
        'total',
        'payment_type',
        'uang_diterima',
        'kembalian',
        'bank_name',
        'reference_number',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'integer',
            'uang_diterima' => 'integer',
            'kembalian' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
