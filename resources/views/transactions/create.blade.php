<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nova transakcija</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

                <form method="POST" action="{{ route('transactions.store') }}">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tip</label>
                            <select id="type" name="type"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    required>
                                <option value="">— Izaberi tip —</option>
                                <option value="income"  {{ old('type') === 'income'  ? 'selected' : '' }}>Prihod</option>
                                <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}>Rashod</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Iznos (RSD)</label>
                            <input type="number" id="amount" name="amount" value="{{ old('amount') }}"
                                   step="0.01" min="0.01" max="9999999.99"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategorija</label>
                            <select id="category_id" name="category_id"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    required>
                                <option value="">— Izaberi kategoriju —</option>
                                @foreach($categories->groupBy('type') as $type => $group)
                                    <optgroup label="{{ $type === 'income' ? 'Prihodi' : 'Rashodi' }}">
                                        @foreach($group as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-1">Datum</label>
                            <input type="date" id="transaction_date" name="transaction_date"
                                   value="{{ old('transaction_date', now()->format('Y-m-d')) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            @error('transaction_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Opis (opciono)</label>
                            <input type="text" id="description" name="description" value="{{ old('description') }}"
                                   maxlength="255"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('transactions.index') }}"
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Otkaži
                        </a>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">
                            Dodaj transakciju
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
