<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>

            {{-- Period switcher --}}
            <div class="flex items-center gap-2 text-sm">
                @foreach(['this_month' => 'Ovaj mesec', 'last_month' => 'Prošli mesec', 'last_3_months' => 'Posled. 3 meseca'] as $key => $label)
                    <a href="{{ route('dashboard', ['period' => $key]) }}"
                       class="px-3 py-1.5 rounded-md font-medium transition
                              {{ $period === $key
                                  ? 'bg-indigo-600 text-white'
                                  : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ===== Summary Cards ===== --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Current Balance --}}
                <div class="col-span-2 lg:col-span-1 bg-white shadow-sm rounded-xl p-6 text-center border-t-4 {{ $net >= 0 ? 'border-green-500' : 'border-red-500' }}">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Tekući balans</p>
                    <p class="text-3xl font-bold {{ Auth::user()->current_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format(Auth::user()->current_balance, 2, ',', '.') }}
                        <span class="text-lg font-normal">RSD</span>
                    </p>
                </div>

                {{-- Income --}}
                <div class="bg-white shadow-sm rounded-xl p-6 text-center border-t-4 border-green-400">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Prihodi</p>
                    <p class="text-2xl font-bold text-green-600">
                        +{{ number_format($income, 2, ',', '.') }}
                        <span class="text-sm font-normal">RSD</span>
                    </p>
                </div>

                {{-- Expenses --}}
                <div class="bg-white shadow-sm rounded-xl p-6 text-center border-t-4 border-red-400">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Rashodi</p>
                    <p class="text-2xl font-bold text-red-600">
                        -{{ number_format($expenses, 2, ',', '.') }}
                        <span class="text-sm font-normal">RSD</span>
                    </p>
                </div>

                {{-- Net --}}
                <div class="bg-white shadow-sm rounded-xl p-6 text-center border-t-4 {{ $net >= 0 ? 'border-blue-400' : 'border-orange-400' }}">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Neto perioda</p>
                    <p class="text-2xl font-bold {{ $net >= 0 ? 'text-blue-600' : 'text-orange-600' }}">
                        {{ $net >= 0 ? '+' : '' }}{{ number_format($net, 2, ',', '.') }}
                        <span class="text-sm font-normal">RSD</span>
                    </p>
                </div>

            </div>

            {{-- ===== Overdue Planned Transactions Alert ===== --}}
            @if($duePlanned->isNotEmpty())
                <div class="bg-red-50 border border-red-200 shadow-sm rounded-xl overflow-hidden">
                    <div class="px-6 py-3 bg-red-100 border-b border-red-200 flex justify-between items-center">
                        <h3 class="font-semibold text-red-800">
                            Dospele planirane transakcije
                            <span class="ml-2 inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-600 text-white text-xs font-bold">
                                {{ $duePlanned->count() }}
                            </span>
                        </h3>
                        <a href="{{ route('planned-transactions.index') }}" class="text-sm text-red-700 hover:text-red-900 font-medium">
                            Prikaži sve &rarr;
                        </a>
                    </div>
                    <ul class="divide-y divide-red-100">
                        @foreach($duePlanned->take(5) as $pt)
                            <li class="px-6 py-3 flex items-center justify-between bg-white hover:bg-red-50">
                                <div>
                                    <span class="text-sm font-medium text-gray-800">{{ $pt->description ?: '(bez opisa)' }}</span>
                                    @if($pt->category)
                                        <span class="ml-2 text-xs text-gray-500">{{ $pt->category->name }}</span>
                                    @endif
                                    <span class="ml-2 text-xs text-red-600">dospela {{ $pt->next_due_date->format('d.m.Y') }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-semibold {{ $pt->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $pt->type === 'income' ? '+' : '-' }}{{ number_format($pt->amount, 2, ',', '.') }} RSD
                                    </span>
                                    <form method="POST" action="{{ route('planned-transactions.confirm', $pt) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700">
                                            Potvrdi
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('planned-transactions.skip', $pt) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded hover:bg-gray-200">
                                            Preskoči
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                        @if($duePlanned->count() > 5)
                            <li class="px-6 py-2 bg-red-50 text-center">
                                <a href="{{ route('planned-transactions.index') }}" class="text-sm text-red-700 hover:text-red-900">
                                    + {{ $duePlanned->count() - 5 }} više &rarr;
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

            {{-- ===== Charts Row ===== --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Line Chart: daily income vs expenses --}}
                <div class="lg:col-span-2 bg-white shadow-sm rounded-xl p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Prihodi vs Rashodi — dnevni prikaz</h3>
                    <div class="relative" style="height: 260px;">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>

                {{-- Donut Chart: expenses by category --}}
                <div class="bg-white shadow-sm rounded-xl p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Rashodi po kategorijama</h3>
                    @if($categoryExpenses->isEmpty())
                        <p class="text-sm text-gray-400 text-center mt-12">Nema rashoda u izabranom periodu.</p>
                    @else
                        <div class="relative" style="height: 220px;">
                            <canvas id="donutChart"></canvas>
                        </div>
                        <ul class="mt-4 space-y-1">
                            @foreach($categoryExpenses->take(5) as $item)
                                <li class="flex items-center justify-between text-xs text-gray-600">
                                    <span class="flex items-center gap-1.5">
                                        <span class="inline-block w-2.5 h-2.5 rounded-full" style="background:{{ $item['color'] }}"></span>
                                        {{ $item['name'] }}
                                    </span>
                                    <span class="font-medium">{{ number_format($item['total'], 0, ',', '.') }} RSD</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

            </div>

            {{-- Bar Chart: monthly comparison --}}
            <div class="bg-white shadow-sm rounded-xl p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Prihodi vs Rashodi — poslednjih 6 meseci</h3>
                <div class="relative" style="height: 240px;">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            {{-- ===== Budget Progress Bars ===== --}}
            @if($budgetCategories->isNotEmpty())
                <div class="bg-white shadow-sm rounded-xl p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-5">Budžetski limiti kategorija</h3>
                    <div class="space-y-4">
                        @foreach($budgetCategories as $b)
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-700">
                                        <span class="inline-block w-2.5 h-2.5 rounded-full mr-1.5" style="background:{{ $b['category']->color }}"></span>
                                        {{ $b['category']->name }}
                                    </span>
                                    <span class="text-xs {{ $b['text_class'] }} font-semibold">
                                        {{ number_format($b['spent'], 0, ',', '.') }} / {{ number_format($b['limit'], 0, ',', '.') }} RSD
                                        ({{ $b['pct'] }}%)
                                    </span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                    <div class="h-2.5 rounded-full transition-all duration-500"
                                         style="width: {{ min($b['pct'], 100) }}%; background-color: {{ $b['pct'] >= 100 ? '#ef4444' : ($b['pct'] >= 80 ? '#facc15' : '#22c55e') }};"></div>
                                </div>
                                @if($b['pct'] >= 100)
                                    <p class="text-xs text-red-500 mt-0.5">Limit prekoračen!</p>
                                @elseif($b['pct'] >= 80)
                                    <p class="text-xs text-yellow-500 mt-0.5">Blizu limita</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ===== Bottom Row: Recent Transactions + Upcoming Planned ===== --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Recent Transactions --}}
                <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-sm font-semibold text-gray-700">Poslednje transakcije</h3>
                        <a href="{{ route('transactions.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Prikaži sve &rarr;</a>
                    </div>
                    @if($recentTransactions->isEmpty())
                        <p class="px-6 py-8 text-sm text-gray-400 text-center">Nema transakcija.</p>
                    @else
                        <ul class="divide-y divide-gray-50">
                            @foreach($recentTransactions as $tx)
                                <li class="px-6 py-3 flex items-center justify-between hover:bg-gray-50">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $tx->description ?: '(bez opisa)' }}</p>
                                        <p class="text-xs text-gray-400">
                                            {{ $tx->category?->name ?? '—' }}
                                            &middot;
                                            {{ $tx->transaction_date->format('d.m.Y') }}
                                        </p>
                                    </div>
                                    <span class="ml-4 text-sm font-semibold whitespace-nowrap {{ $tx->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format($tx->amount, 2, ',', '.') }} RSD
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                {{-- Upcoming Planned Transactions --}}
                <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-sm font-semibold text-gray-700">Predstojeće u narednih 7 dana</h3>
                        <a href="{{ route('planned-transactions.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Prikaži sve &rarr;</a>
                    </div>
                    @if($upcomingPlanned->isEmpty())
                        <p class="px-6 py-8 text-sm text-gray-400 text-center">Nema predstojećih planiranih transakcija.</p>
                    @else
                        <ul class="divide-y divide-gray-50">
                            @foreach($upcomingPlanned as $pt)
                                <li class="px-6 py-3 flex items-center justify-between hover:bg-gray-50">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $pt->description ?: '(bez opisa)' }}</p>
                                        <p class="text-xs text-gray-400">
                                            {{ $pt->category?->name ?? '—' }}
                                            &middot;
                                            <span class="text-indigo-500">{{ $pt->next_due_date->format('d.m.Y') }}</span>
                                        </p>
                                    </div>
                                    <span class="ml-4 text-sm font-semibold whitespace-nowrap {{ $pt->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $pt->type === 'income' ? '+' : '-' }}{{ number_format($pt->amount, 2, ',', '.') }} RSD
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

            </div>

        </div>
    </div>

    {{-- ===== Chart.js ===== --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        const lineLabels   = @json($dailyData['labels']);
        const lineIncome   = @json($dailyData['income']);
        const lineExpenses = @json($dailyData['expenses']);

        const donutLabels  = @json($categoryExpenses->pluck('name'));
        const donutData    = @json($categoryExpenses->pluck('total'));
        const donutColors  = @json($categoryExpenses->pluck('color'));

        const barLabels    = @json($monthlyData['labels']);
        const barIncome    = @json($monthlyData['income']);
        const barExpenses  = @json($monthlyData['expenses']);

        // Line chart
        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: lineLabels,
                datasets: [
                    {
                        label: 'Prihodi',
                        data: lineIncome,
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34,197,94,0.08)',
                        tension: 0.3,
                        fill: true,
                        pointRadius: 2,
                    },
                    {
                        label: 'Rashodi',
                        data: lineExpenses,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239,68,68,0.08)',
                        tension: 0.3,
                        fill: true,
                        pointRadius: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } } },
                scales: {
                    y: { ticks: { font: { size: 11 } } },
                    x: { ticks: { font: { size: 10 }, maxRotation: 45, minRotation: 0 } },
                },
            },
        });

        // Donut chart
        @if($categoryExpenses->isNotEmpty())
        new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                labels: donutLabels,
                datasets: [{
                    data: donutData,
                    backgroundColor: donutColors,
                    borderWidth: 2,
                    borderColor: '#fff',
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: {
                        label: ctx => ` ${ctx.label}: ${new Intl.NumberFormat('sr-RS').format(ctx.raw)} RSD`
                    }},
                },
                cutout: '65%',
            },
        });
        @endif

        // Bar chart
        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: barLabels,
                datasets: [
                    {
                        label: 'Prihodi',
                        data: barIncome,
                        backgroundColor: 'rgba(34,197,94,0.7)',
                        borderRadius: 4,
                    },
                    {
                        label: 'Rashodi',
                        data: barExpenses,
                        backgroundColor: 'rgba(239,68,68,0.7)',
                        borderRadius: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } } },
                scales: {
                    y: { ticks: { font: { size: 11 } } },
                    x: { ticks: { font: { size: 11 } } },
                },
            },
        });
    </script>

</x-app-layout>
