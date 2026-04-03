<?php

namespace App\Services;

use App\Models\PlannedTransaction;
use Carbon\Carbon;

class PlannedTransactionService
{
    public function calculateNextDueDate(PlannedTransaction $pt): ?Carbon
    {
        $current = Carbon::parse($pt->next_due_date);

        return match ($pt->recurrence_type) {
            'none'    => null,
            'daily'   => $current->copy()->addDay(),
            'weekly'  => $current->copy()->addWeek(),
            'monthly' => $this->nextMonthly($current, $pt->recurrence_day),
            'yearly'  => $current->copy()->addYear(),
            default   => null,
        };
    }

    private function nextMonthly(Carbon $from, ?int $recurrenceDay): Carbon
    {
        $next = $from->copy()->addMonth();
        $day  = $recurrenceDay ?? $from->day;

        return $next->setDay(min($day, $next->daysInMonth));
    }
}
