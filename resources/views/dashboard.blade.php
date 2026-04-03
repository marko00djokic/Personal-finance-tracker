<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Balance + Quick Links -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                    <p class="text-sm text-gray-500 mb-1">Tekući balans</p>
                    <p class="text-3xl font-bold {{ Auth::user()->current_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format(Auth::user()->current_balance, 2, ',', '.') }} RSD
                    </p>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                    <p class="text-sm text-gray-500 mb-1">Transakcije</p>
                    <a href="{{ route('transactions.index') }}"
                       class="text-indigo-600 hover:text-indigo-800 font-semibold text-lg">
                        Prikaži sve &rarr;
                    </a>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                    <p class="text-sm text-gray-500 mb-1">Kategorije</p>
                    <a href="{{ route('categories.index') }}"
                       class="text-indigo-600 hover:text-indigo-800 font-semibold text-lg">
                        Upravljaj &rarr;
                    </a>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                    <p class="text-sm text-gray-500 mb-1">Planirane transakcije</p>
                    @php
                        $duePlannedCount = Auth::user()->plannedTransactions()
                            ->where('is_active', true)
                            ->whereDate('next_due_date', '<=', now())
                            ->count();
                    @endphp
                    <a href="{{ route('planned-transactions.index') }}"
                       class="font-semibold text-lg {{ $duePlannedCount > 0 ? 'text-red-600 hover:text-red-800' : 'text-indigo-600 hover:text-indigo-800' }}">
                        @if($duePlannedCount > 0)
                            {{ $duePlannedCount }} dospelih &rarr;
                        @else
                            Prikaži &rarr;
                        @endif
                    </a>
                </div>
            </div>

            <!-- Dospele planirane transakcije -->
            @php
                $duePlanned = Auth::user()->plannedTransactions()
                    ->with('category')
                    ->where('is_active', true)
                    ->whereDate('next_due_date', '<=', now())
                    ->orderBy('next_due_date')
                    ->get();
            @endphp

            @if($duePlanned->isNotEmpty())
                <div class="bg-red-50 border border-red-200 shadow-sm rounded-lg overflow-hidden">
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
                                        <button type="submit"
                                                class="px-3 py-1 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700">
                                            Potvrdi
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('planned-transactions.skip', $pt) }}">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded hover:bg-gray-200">
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

        </div>
    </div>
</x-app-layout>
