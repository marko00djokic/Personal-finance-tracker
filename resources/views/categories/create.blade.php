<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nova kategorija</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Naziv</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   required maxlength="100">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

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
                            <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Boja</label>
                            <div class="flex items-center gap-3">
                                <input type="color" id="color" name="color" value="{{ old('color', '#6B7280') }}"
                                       class="h-10 w-16 border-gray-300 rounded cursor-pointer">
                                <span class="text-sm text-gray-500">Izaberi boju kategorije</span>
                            </div>
                            @error('color')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="monthly_limit" class="block text-sm font-medium text-gray-700 mb-1">
                                Mesečni limit (RSD)
                                <span class="ml-1 text-xs text-gray-400 font-normal">— samo za rashode, opciono</span>
                            </label>
                            <input type="number" id="monthly_limit" name="monthly_limit"
                                   value="{{ old('monthly_limit') }}"
                                   min="0" step="0.01"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="npr. 20000">
                            @error('monthly_limit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('categories.index') }}"
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
