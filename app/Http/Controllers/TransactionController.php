<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use App\Services\BalanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function __construct(private BalanceService $balanceService) {}

    public function index(Request $request): View
    {
        $query = Auth::user()->transactions()
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('type') && in_array($request->type, ['income', 'expense'])) {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate(20)->withQueryString();
        $categories   = Auth::user()->categories()->orderBy('name')->get();

        return view('transactions.index', compact('transactions', 'categories'));
    }

    public function create(): View
    {
        $categories = Auth::user()->categories()->orderBy('type')->orderBy('name')->get();

        return view('transactions.create', compact('categories'));
    }

    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();

            $transaction = Transaction::create($data);

            $this->balanceService->apply(Auth::user(), $transaction->type, (float) $transaction->amount);

            return redirect()->route('transactions.index')
                ->with('success', 'Transakcija je uspešno dodana.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Greška pri dodavanju transakcije. Pokušajte ponovo.');
        }
    }

    public function edit(Transaction $transaction): View
    {
        $this->authorizeTransaction($transaction);

        $categories = Auth::user()->categories()->orderBy('type')->orderBy('name')->get();

        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $this->authorizeTransaction($transaction);

        try {
            $data = $request->validated();

            $this->balanceService->reapply(
                Auth::user(),
                $transaction,
                $data['type'],
                (float) $data['amount']
            );

            $transaction->update($data);

            return redirect()->route('transactions.index')
                ->with('success', 'Transakcija je uspešno ažurirana.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Greška pri ažuriranju transakcije. Pokušajte ponovo.');
        }
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $this->authorizeTransaction($transaction);

        try {
            $this->balanceService->reverse(Auth::user(), $transaction->type, (float) $transaction->amount);

            $transaction->delete();

            return redirect()->route('transactions.index')
                ->with('success', 'Transakcija je uspešno obrisana.');
        } catch (\Exception $e) {
            return redirect()->route('transactions.index')
                ->with('error', 'Greška pri brisanju transakcije. Pokušajte ponovo.');
        }
    }

    private function authorizeTransaction(Transaction $transaction): void
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
