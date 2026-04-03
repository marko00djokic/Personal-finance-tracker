<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlannedTransactionRequest;
use App\Http\Requests\UpdatePlannedTransactionRequest;
use App\Models\PlannedTransaction;
use App\Models\Transaction;
use App\Services\BalanceService;
use App\Services\PlannedTransactionService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PlannedTransactionController extends Controller
{
    public function __construct(
        private PlannedTransactionService $plannedService,
        private BalanceService $balanceService,
    ) {}

    public function index(): View
    {
        $today = Carbon::today();

        $due = Auth::user()->plannedTransactions()
            ->with('category')
            ->where('is_active', true)
            ->whereDate('next_due_date', '<=', $today)
            ->orderBy('next_due_date')
            ->get();

        $upcoming = Auth::user()->plannedTransactions()
            ->with('category')
            ->where('is_active', true)
            ->whereDate('next_due_date', '>', $today)
            ->orderBy('next_due_date')
            ->get();

        $inactive = Auth::user()->plannedTransactions()
            ->with('category')
            ->where('is_active', false)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('planned-transactions.index', compact('due', 'upcoming', 'inactive'));
    }

    public function create(): View
    {
        $categories = Auth::user()->categories()->orderBy('type')->orderBy('name')->get();

        return view('planned-transactions.create', compact('categories'));
    }

    public function store(StorePlannedTransactionRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['user_id']   = Auth::id();
            $data['is_active'] = $request->boolean('is_active', true);

            PlannedTransaction::create($data);

            return redirect()->route('planned-transactions.index')
                ->with('success', 'Planirana transakcija je uspešno kreirana.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Greška pri kreiranju. Pokušajte ponovo.');
        }
    }

    public function edit(PlannedTransaction $plannedTransaction): View
    {
        $this->authorizeOwnership($plannedTransaction);

        $categories = Auth::user()->categories()->orderBy('type')->orderBy('name')->get();

        return view('planned-transactions.edit', compact('plannedTransaction', 'categories'));
    }

    public function update(UpdatePlannedTransactionRequest $request, PlannedTransaction $plannedTransaction): RedirectResponse
    {
        $this->authorizeOwnership($plannedTransaction);

        try {
            $data = $request->validated();
            $data['is_active'] = $request->boolean('is_active', true);

            $plannedTransaction->update($data);

            return redirect()->route('planned-transactions.index')
                ->with('success', 'Planirana transakcija je uspešno ažurirana.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Greška pri ažuriranju. Pokušajte ponovo.');
        }
    }

    public function destroy(PlannedTransaction $plannedTransaction): RedirectResponse
    {
        $this->authorizeOwnership($plannedTransaction);

        try {
            $plannedTransaction->delete();

            return redirect()->route('planned-transactions.index')
                ->with('success', 'Planirana transakcija je obrisana.');
        } catch (\Exception $e) {
            return redirect()->route('planned-transactions.index')
                ->with('error', 'Greška pri brisanju. Pokušajte ponovo.');
        }
    }

    public function confirm(PlannedTransaction $plannedTransaction): RedirectResponse
    {
        $this->authorizeOwnership($plannedTransaction);

        try {
            Transaction::create([
                'user_id'          => Auth::id(),
                'category_id'      => $plannedTransaction->category_id,
                'type'             => $plannedTransaction->type,
                'amount'           => $plannedTransaction->amount,
                'description'      => $plannedTransaction->description,
                'transaction_date' => $plannedTransaction->next_due_date->format('Y-m-d'),
            ]);

            $this->balanceService->apply(
                Auth::user(),
                $plannedTransaction->type,
                (float) $plannedTransaction->amount,
            );

            $this->advanceDueDate($plannedTransaction);

            return redirect()->route('planned-transactions.index')
                ->with('success', 'Transakcija je potvrđena i dodana u evidenciju.');
        } catch (\Exception $e) {
            return redirect()->route('planned-transactions.index')
                ->with('error', 'Greška pri potvrđivanju. Pokušajte ponovo.');
        }
    }

    public function skip(PlannedTransaction $plannedTransaction): RedirectResponse
    {
        $this->authorizeOwnership($plannedTransaction);

        try {
            $this->advanceDueDate($plannedTransaction);

            return redirect()->route('planned-transactions.index')
                ->with('success', 'Planirana transakcija je preskočena.');
        } catch (\Exception $e) {
            return redirect()->route('planned-transactions.index')
                ->with('error', 'Greška pri preskakanju. Pokušajte ponovo.');
        }
    }

    private function advanceDueDate(PlannedTransaction $pt): void
    {
        $next = $this->plannedService->calculateNextDueDate($pt);

        if ($next === null) {
            $pt->update(['is_active' => false]);
        } else {
            $pt->update(['next_due_date' => $next->format('Y-m-d')]);
        }
    }

    private function authorizeOwnership(PlannedTransaction $pt): void
    {
        if ($pt->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
