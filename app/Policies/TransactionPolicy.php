<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function view(User $user, Transaction $transaction): bool
    {
        return $user->isAdmin() || $transaction->user_id === $user->id;
    }

    public function update(User $user, Transaction $transaction): bool
    {
        return $this->view($user, $transaction);
    }

    public function delete(User $user, Transaction $transaction): bool
    {
        return $user->isAdmin();
    }

    public function settle(User $user, Transaction $transaction): bool
    {
        return $this->view($user, $transaction);
    }

    public function verifyTransfer(User $user, Transaction $transaction): bool
    {
        return $user->isAdmin();
    }
}
