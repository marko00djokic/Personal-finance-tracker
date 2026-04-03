<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kategorije</h2>
            <a href="{{ route('categories.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                + Nova kategorija
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @php
                $incomeCategories  = $categories->where('type', 'income');
                $expenseCategories = $categories->where('type', 'expense');
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Prihodi -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                        <h3 class="font-semibold text-green-800">Prihodi ({{ $incomeCategories->count() }})</h3>
                    </div>
                    <ul class="divide-y divide-gray-100">
                        @forelse($incomeCategories as $category)
                            <li class="flex items-center justify-between px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="inline-block w-4 h-4 rounded-full" style="background-color: {{ $category->color }}"></span>
                                    <span class="text-gray-800">{{ $category->name }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('categories.edit', $category) }}"
                                       class="text-sm text-indigo-600 hover:text-indigo-800">Izmeni</a>
                                    <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                          x-data="{ name: @js($category->name) }"
                                          @submit.prevent="confirm('Obrisati kategoriju ' + name + '?') && $el.submit()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Obriši</button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="px-6 py-4 text-sm text-gray-500">Nema kategorija prihoda.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Rashodi -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
                        <h3 class="font-semibold text-red-800">Rashodi ({{ $expenseCategories->count() }})</h3>
                    </div>
                    <ul class="divide-y divide-gray-100">
                        @forelse($expenseCategories as $category)
                            <li class="flex items-center justify-between px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="inline-block w-4 h-4 rounded-full" style="background-color: {{ $category->color }}"></span>
                                    <span class="text-gray-800">{{ $category->name }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('categories.edit', $category) }}"
                                       class="text-sm text-indigo-600 hover:text-indigo-800">Izmeni</a>
                                    <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                          x-data="{ name: @js($category->name) }"
                                          @submit.prevent="confirm('Obrisati kategoriju ' + name + '?') && $el.submit()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Obriši</button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="px-6 py-4 text-sm text-gray-500">Nema kategorija rashoda.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
