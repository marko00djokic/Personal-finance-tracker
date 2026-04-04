<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function exportCsv(Request $request): StreamedResponse
    {
        $transactions = $this->getFilteredTransactions($request);

        $filename = 'transakcije_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($transactions) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Datum', 'Tip', 'Kategorija', 'Opis', 'Iznos (RSD)'], ';');

            foreach ($transactions as $t) {
                fputcsv($handle, [
                    $t->transaction_date->format('d.m.Y'),
                    $t->type === 'income' ? 'Prihod' : 'Rashod',
                    $t->category?->name ?? '—',
                    $t->description ?? '',
                    number_format($t->amount, 2, ',', '.'),
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request): \Illuminate\Http\Response
    {
        $transactions = $this->getFilteredTransactions($request);

        $from  = $request->filled('date_from') ? $request->date_from : null;
        $to    = $request->filled('date_to')   ? $request->date_to   : null;
        $user  = Auth::user();

        $income   = $transactions->where('type', 'income')->sum('amount');
        $expenses = $transactions->where('type', 'expense')->sum('amount');
        $net      = $income - $expenses;

        $pdf = Pdf::loadView('exports.transactions-pdf', compact(
            'transactions', 'from', 'to', 'user', 'income', 'expenses', 'net'
        ))->setPaper('a4', 'portrait');

        $filename = 'transakcije_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    private function getFilteredTransactions(Request $request)
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

        return $query->get();
    }
}
