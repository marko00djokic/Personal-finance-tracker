<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Balance card -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
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
            </div>

        </div>
    </div>
</x-app-layout>
