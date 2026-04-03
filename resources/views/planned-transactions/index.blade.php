<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Planirane transakcije</h2>
            <a href="{{ route('planned-transactions.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                + Nova planirana transakcija
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- DOSPELE DANAS --}}
            @if($due->isNotEmpty())
                <div class="bg-red-50 border border-red-200 shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-3 bg-red-100 border-b border-red-200">
                        <h3 class="font-semibold text-red-800">
                            Dospele danas ili zakasnele
                            <span class="ml-2 inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-600 text-white text-xs font-bold">
                                {{ $due->count() }}
                            </span>
                        </h3>
                    </div>
                    <table class="min-w-full divide-y divide-red-100">
                        <thead class="bg-red-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-600 uppercase tracking-wider">Datum dospeća</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-600 uppercase tracking-wider">Opis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-600 uppercase tracking-wider">Kategorija</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-600 uppercase tracking-wider">Ponavljanje</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-red-600 uppercase tracking-wider">Iznos</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-red-600 uppercase tracking-wider">Akcije</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-red-50">
                            @foreach($due as $pt)
                                <tr class="hover:bg-red-50">
                                    <td class="px-6 py-3 text-sm font-medium text-red-700 whitespace-nowrap">
                                        {{ $pt->next_due_date->format('d.m.Y') }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-800">
                                        {{ $pt->description ?: '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-sm">
                                        @if($pt->category)
                                            <span class="inline-flex items-center gap-1.5">
                                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $pt->category->color }}"></span>
                                                {{ $pt->category->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-600">
                                        @include('planned-transactions._recurrence_badge', ['pt' => $pt])
                                    </td>
                                    <td class="px-6 py-3 text-sm font-medium text-right whitespace-nowrap
                                        {{ $pt->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $pt->type === 'income' ? '+' : '-' }}{{ number_format($pt->amount, 2, ',', '.') }} RSD
                                    </td>
                                    <td class="px-6 py-3 text-right whitespace-nowrap">
                                        <div class="flex justify-end gap-2">
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
                                                        class="px-3 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200">
                                                    Preskoči
                                                </button>
                                            </form>
                                            <a href="{{ route('planned-transactions.edit', $pt) }}"
                                               class="px-3 py-1 text-xs font-medium text-indigo-600 hover:text-indigo-800">
                                                Izmeni
                                            </a>
                                            <form method="POST" action="{{ route('planned-transactions.destroy', $pt) }}"
                                                  x-data
                                                  @submit.prevent="confirm('Obrisati ovu planiranu transakciju?') && $el.submit()">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 text-xs font-medium text-red-600 hover:text-red-800">Obriši</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- PREDSTOJEĆE --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-700">Predstojeće (aktivne)</h3>
                </div>
                @if($upcoming->isEmpty())
                    <p class="p-6 text-sm text-gray-500">Nema predstojećih planiranih transakcija.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sledeći datum</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategorija</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ponavljanje</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Iznos</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Akcije</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($upcoming as $pt)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm text-gray-600 whitespace-nowrap">
                                        {{ $pt->next_due_date->format('d.m.Y') }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-800">
                                        {{ $pt->description ?: '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-sm">
                                        @if($pt->category)
                                            <span class="inline-flex items-center gap-1.5">
                                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $pt->category->color }}"></span>
                                                {{ $pt->category->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-600">
                                        @include('planned-transactions._recurrence_badge', ['pt' => $pt])
                                    </td>
                                    <td class="px-6 py-3 text-sm font-medium text-right whitespace-nowrap
                                        {{ $pt->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $pt->type === 'income' ? '+' : '-' }}{{ number_format($pt->amount, 2, ',', '.') }} RSD
                                    </td>
                                    <td class="px-6 py-3 text-right whitespace-nowrap">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('planned-transactions.edit', $pt) }}"
                                               class="text-sm text-indigo-600 hover:text-indigo-800">Izmeni</a>
                                            <form method="POST" action="{{ route('planned-transactions.destroy', $pt) }}"
                                                  x-data
                                                  @submit.prevent="confirm('Obrisati ovu planiranu transakciju?') && $el.submit()">
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
                @endif
            </div>

            {{-- NEAKTIVNE --}}
            @if($inactive->isNotEmpty())
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-500 text-sm">Neaktivne (jednokratne završene)</h3>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Datum</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Opis</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Iznos</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Akcije</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($inactive as $pt)
                                <tr class="hover:bg-gray-50 opacity-60">
                                    <td class="px-6 py-3 text-sm text-gray-500 whitespace-nowrap">
                                        {{ $pt->next_due_date->format('d.m.Y') }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-500">
                                        {{ $pt->description ?: '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-right text-gray-500 whitespace-nowrap">
                                        {{ number_format($pt->amount, 2, ',', '.') }} RSD
                                    </td>
                                    <td class="px-6 py-3 text-right whitespace-nowrap">
                                        <form method="POST" action="{{ route('planned-transactions.destroy', $pt) }}"
                                              x-data
                                              @submit.prevent="confirm('Obrisati ovu planiranu transakciju?') && $el.submit()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-400 hover:text-red-600">Obriši</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
