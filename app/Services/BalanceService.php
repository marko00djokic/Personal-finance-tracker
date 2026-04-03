<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;

class BalanceService
{
    public function apply(User $user, string $type, float $amount): void
    {
        if ($type === 'income') {
            $user->increment('current_balance', $amount);
        } else {
            $user->decrement('current_balance', $amount);
        }
    }

    public function reverse(User $user, string $type, float $amount): void
    {
        if ($type === 'income') {
            $user->decrement('current_balance', $amount);
        } else {
            $user->increment('current_balance', $amount);
        }
    }

    public function reapply(User $user, Transaction $old, string $newType, float $newAmount): void
    {
        $this->reverse($user, $old->type, (float) $old->amount);
        $this->apply($user, $newType, $newAmount);
    }
}
