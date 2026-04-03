<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Auth::user()->categories()->orderBy('type')->orderBy('name')->get();

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        try {
            Auth::user()->categories()->create($request->validated());

            return redirect()->route('categories.index')
                ->with('success', 'Kategorija je uspešno kreirana.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Greška pri kreiranju kategorije. Pokušajte ponovo.');
        }
    }

    public function edit(Category $category): View
    {
        $this->authorizeCategory($category);

        return view('categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $this->authorizeCategory($category);

        try {
            $category->update($request->validated());

            return redirect()->route('categories.index')
                ->with('success', 'Kategorija je uspešno ažurirana.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Greška pri ažuriranju kategorije. Pokušajte ponovo.');
        }
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorizeCategory($category);

        if ($category->transactions()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', 'Kategorija se ne može obrisati jer ima povezanih transakcija.');
        }

        try {
            $category->delete();

            return redirect()->route('categories.index')
                ->with('success', 'Kategorija je uspešno obrisana.');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')
                ->with('error', 'Greška pri brisanju kategorije. Pokušajte ponovo.');
        }
    }

    private function authorizeCategory(Category $category): void
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
