<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Period switcher
        $period = $request->get('period', 'this_month');

        [$startDate, $endDate] = $this->resolvePeriod($period);

        // Summary aggregates
        $income = $user->transactions()
            ->where('type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $expenses = $user->transactions()
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $net = $income - $expenses;

        // Last 10 transactions
        $recentTransactions = $user->transactions()
            ->with('category')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->take(10)
            ->get();

        // Upcoming planned transactions (next 7 days)
        $upcomingPlanned = $user->plannedTransactions()
            ->with('category')
            ->where('is_active', true)
            ->whereDate('next_due_date', '>', now())
            ->whereDate('next_due_date', '<=', now()->addDays(7))
            ->orderBy('next_due_date')
            ->get();

        // Overdue planned transactions
        $duePlanned = $user->plannedTransactions()
            ->with('category')
            ->where('is_active', true)
            ->whereDate('next_due_date', '<=', now())
            ->orderBy('next_due_date')
            ->get();

        // --- Chart data ---

        // Line chart: daily income vs expenses for selected period
        $dailyData = $this->getDailyChartData($user, $startDate, $endDate);

        // Pie chart: expenses by category (selected period)
        $categoryExpenses = $user->transactions()
            ->with('category')
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->get()
            ->groupBy('category_id')
            ->map(function ($group) {
                return [
                    'name' => $group->first()->category?->name ?? 'Bez kategorije',
                    'color' => $group->first()->category?->color ?? '#6B7280',
                    'total' => $group->sum('amount'),
                ];
            })
            ->sortByDesc('total')
            ->values();

        // Bar chart: monthly income vs expenses (last 6 months)
        $monthlyData = $this->getMonthlyChartData($user);

        // Budget progress bars: expense categories with monthly_limit
        $budgetCategories = $user->categories()
            ->where('type', 'expense')
            ->whereNotNull('monthly_limit')
            ->where('monthly_limit', '>', 0)
            ->get()
            ->map(function ($category) use ($user, $startDate, $endDate) {
                $spent = $user->transactions()
                    ->where('type', 'expense')
                    ->where('category_id', $category->id)
                    ->whereBetween('transaction_date', [$startDate, $endDate])
                    ->sum('amount');

                $pct = ($category->monthly_limit > 0)
                    ? min(round(($spent / $category->monthly_limit) * 100, 1), 999)
                    : 0;

                return [
                    'category' => $category,
                    'spent' => $spent,
                    'limit' => $category->monthly_limit,
                    'pct' => $pct,
                    'color_class' => $pct >= 100 ? 'bg-red-500' : ($pct >= 80 ? 'bg-yellow-400' : 'bg-green-500'),
                    'text_class' => $pct >= 100 ? 'text-red-600' : ($pct >= 80 ? 'text-yellow-600' : 'text-green-600'),
                ];
            });

        return view('dashboard', compact(
            'period', 'startDate', 'endDate',
            'income', 'expenses', 'net',
            'recentTransactions',
            'upcomingPlanned', 'duePlanned',
            'dailyData', 'categoryExpenses', 'monthlyData',
            'budgetCategories'
        ));
    }

    private function resolvePeriod(string $period): array
    {
        return match ($period) {
            'last_month' => [
                Carbon::now()->subMonth()->startOfMonth()->toDateString(),
                Carbon::now()->subMonth()->endOfMonth()->toDateString(),
            ],
            'last_3_months' => [
                Carbon::now()->subMonths(3)->startOfMonth()->toDateString(),
                Carbon::now()->endOfMonth()->toDateString(),
            ],
            default => [ // this_month
                Carbon::now()->startOfMonth()->toDateString(),
                Carbon::now()->endOfMonth()->toDateString(),
            ],
        };
    }

    private function getDailyChartData($user, string $startDate, string $endDate): array
    {
        $rows = $user->transactions()
            ->select(
                'transaction_date',
                'type',
                DB::raw('SUM(amount) as total')
            )
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->groupBy('transaction_date', 'type')
            ->orderBy('transaction_date')
            ->get();

        $period = Carbon::parse($startDate)->daysUntil(Carbon::parse($endDate)->addDay());
        $labels = [];
        $incomeMap = [];
        $expenseMap = [];

        foreach ($period as $date) {
            $key = $date->toDateString();
            $labels[] = $date->format('d.m');
            $incomeMap[$key] = 0;
            $expenseMap[$key] = 0;
        }

        foreach ($rows as $row) {
            $key = Carbon::parse($row->transaction_date)->toDateString();
            if ($row->type === 'income') {
                $incomeMap[$key] = (float) $row->total;
            } else {
                $expenseMap[$key] = (float) $row->total;
            }
        }

        return [
            'labels' => $labels,
            'income' => array_values($incomeMap),
            'expenses' => array_values($expenseMap),
        ];
    }

    private function getMonthlyChartData($user): array
    {
        $labels = [];
        $income = [];
        $expenses = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $start = $month->copy()->startOfMonth()->toDateString();
            $end = $month->copy()->endOfMonth()->toDateString();

            $labels[] = $month->translatedFormat('M Y');

            $income[] = (float) $user->transactions()
                ->where('type', 'income')
                ->whereBetween('transaction_date', [$start, $end])
                ->sum('amount');

            $expenses[] = (float) $user->transactions()
                ->where('type', 'expense')
                ->whereBetween('transaction_date', [$start, $end])
                ->sum('amount');
        }

        return compact('labels', 'income', 'expenses');
    }
}
