<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Izmeni planiranu transakciju</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

                <form method="POST" action="{{ route('planned-transactions.update', $plannedTransaction) }}"
                      x-data="{ recurrence: '{{ old('recurrence_type', $plannedTransaction->recurrence_type) }}' }">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tip</label>
                            <select id="type" name="type"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    required>
                                <option value="income"  {{ old('type', $plannedTransaction->type) === 'income'  ? 'selected' : '' }}>Prihod</option>
                                <option value="expense" {{ old('type', $plannedTransaction->type) === 'expense' ? 'selected' : '' }}>Rashod</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Iznos (RSD)</label>
                            <input type="number" id="amount" name="amount"
                                   value="{{ old('amount', $plannedTransaction->amount) }}"
                                   step="0.01" min="0.01" max="9999999.99"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategorija (opciono)</label>
                            <select id="category_id" name="category_id"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">— Bez kategorije —</option>
                                @foreach($categories->groupBy('type') as $type => $group)
                                    <optgroup label="{{ $type === 'income' ? 'Prihodi' : 'Rashodi' }}">
                                        @foreach($group as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ old('category_id', $plannedTransaction->category_id) == $cat->id ? 'selected' : '' }}>
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
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Opis (opciono)</label>
                            <input type="text" id="description" name="description"
                                   value="{{ old('description', $plannedTransaction->description) }}"
                                   maxlength="255"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="next_due_date" class="block text-sm font-medium text-gray-700 mb-1">Datum dospeća</label>
                            <input type="date" id="next_due_date" name="next_due_date"
                                   value="{{ old('next_due_date', $plannedTransaction->next_due_date->format('Y-m-d')) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            @error('next_due_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="recurrence_type" class="block text-sm font-medium text-gray-700 mb-1">Ponavljanje</label>
                            <select id="recurrence_type" name="recurrence_type"
                                    x-model="recurrence"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    required>
                                <option value="none"    {{ old('recurrence_type', $plannedTransaction->recurrence_type) === 'none'    ? 'selected' : '' }}>Jednokratno</option>
                                <option value="daily"   {{ old('recurrence_type', $plannedTransaction->recurrence_type) === 'daily'   ? 'selected' : '' }}>Dnevno</option>
                                <option value="weekly"  {{ old('recurrence_type', $plannedTransaction->recurrence_type) === 'weekly'  ? 'selected' : '' }}>Nedeljno</option>
                                <option value="monthly" {{ old('recurrence_type', $plannedTransaction->recurrence_type) === 'monthly' ? 'selected' : '' }}>Mesečno</option>
                                <option value="yearly"  {{ old('recurrence_type', $plannedTransaction->recurrence_type) === 'yearly'  ? 'selected' : '' }}>Godišnje</option>
                            </select>
                            @error('recurrence_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-show="recurrence === 'monthly'" x-cloak>
                            <label for="recurrence_day" class="block text-sm font-medium text-gray-700 mb-1">
                                Dan u mesecu (1–31)
                                <span class="text-gray-400 font-normal">— ostavi prazno za isti dan kao datum dospeća</span>
                            </label>
                            <input type="number" id="recurrence_day" name="recurrence_day"
                                   value="{{ old('recurrence_day', $plannedTransaction->recurrence_day) }}"
                                   min="1" max="31"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('recurrence_day')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $plannedTransaction->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <label for="is_active" class="text-sm font-medium text-gray-700">Aktivna</label>
                        </div>

                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('planned-transactions.index') }}"
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Otkaži
                        </a>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">
                            Sačuvaj
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
