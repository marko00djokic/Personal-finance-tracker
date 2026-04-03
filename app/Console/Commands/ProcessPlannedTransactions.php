<?php

namespace App\Console\Commands;

use App\Models\PlannedTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessPlannedTransactions extends Command
{
    protected $signature = 'planned-transactions:process';

    protected $description = 'Log planned transactions due today or within the next 3 days';

    public function handle(): int
    {
        $today     = Carbon::today();
        $lookahead = $today->copy()->addDays(3);

        $due = PlannedTransaction::query()
            ->where('is_active', true)
            ->whereDate('next_due_date', '<=', $lookahead)
            ->with('user')
            ->orderBy('next_due_date')
            ->get();

        if ($due->isEmpty()) {
            $this->info('Nema dospelih ili nadolazećih planiranih transakcija.');
            return Command::SUCCESS;
        }

        $this->info("Planirane transakcije koje dospevaju (ukupno: {$due->count()}):");
        $this->newLine();

        foreach ($due as $pt) {
            $dueDate     = Carbon::parse($pt->next_due_date);
            $daysUntil   = $today->diffInDays($dueDate, false);
            $label       = $daysUntil <= 0 ? 'DOSPELA' : "za {$daysUntil} dan(a)";
            $typeLabel   = $pt->type === 'income' ? 'prihod' : 'rashod';

            $this->line(sprintf(
                '[%s] %s — %.2f RSD (%s) — korisnik: %s',
                $label,
                $pt->description ?: '(bez opisa)',
                $pt->amount,
                $typeLabel,
                $pt->user->email,
            ));
        }

        return Command::SUCCESS;
    }
}
