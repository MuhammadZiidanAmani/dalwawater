<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'kode_transaksi',
        'user_id',
        'customer_name',
        'total',
        'payment_type',
        'uang_diterima',
        'kembalian',
        'bank_name',
        'reference_number',
        'payment_status',
        'paid_at',
        'settled_by',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'integer',
            'uang_diterima' => 'integer',
            'kembalian' => 'integer',
            'paid_at' => 'datetime',
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

    public function settledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'settled_by');
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        return $query->when(
            ! $user->isAdmin(),
            fn (Builder $query) => $query->where('user_id', $user->id),
        );
    }
}
