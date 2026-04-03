<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transakcije</h2>
            <a href="{{ route('transactions.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                + Nova transakcija
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <!-- Filteri -->
            <div class="bg-white shadow-sm rounded-lg p-4">
                <form method="GET" action="{{ route('transactions.index') }}" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Od datuma</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                               class="border-gray-300 rounded-md text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Do datuma</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                               class="border-gray-300 rounded-md text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Kategorija</label>
                        <select name="category_id"
                                class="border-gray-300 rounded-md text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Sve kategorije</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Tip</label>
                        <select name="type"
                                class="border-gray-300 rounded-md text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Svi tipovi</option>
                            <option value="income"  {{ request('type') === 'income'  ? 'selected' : '' }}>Prihod</option>
                            <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Rashod</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                            Filtriraj
                        </button>
                        <a href="{{ route('transactions.index') }}"
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabela -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                @if($transactions->isEmpty())
                    <p class="p-6 text-sm text-gray-500">Nema transakcija koje odgovaraju filteru.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategorija</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Iznos</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Akcije</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($transactions as $transaction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm text-gray-600 whitespace-nowrap">
                                        {{ $transaction->transaction_date->format('d.m.Y') }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-800">
                                        {{ $transaction->description ?: '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-sm">
                                        @if($transaction->category)
                                            <span class="inline-flex items-center gap-1.5">
                                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $transaction->category->color }}"></span>
                                                {{ $transaction->category->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-sm font-medium text-right whitespace-nowrap
                                        {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }}{{ number_format($transaction->amount, 2, ',', '.') }} RSD
                                    </td>
                                    <td class="px-6 py-3 text-right whitespace-nowrap">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('transactions.edit', $transaction) }}"
                                               class="text-sm text-indigo-600 hover:text-indigo-800">Izmeni</a>
                                            <form method="POST" action="{{ route('transactions.destroy', $transaction) }}"
                                                  x-data
                                                  @submit.prevent="confirm('Obrisati ovu transakciju?') && $el.submit()">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-600 hover:text-red-800">Obriši</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
