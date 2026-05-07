<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class FinanceCategoryController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard');
        }

        $categories = Category::orderBy('type')->orderBy('name')->get();
        return view('finance.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        Category::create($validated);

        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        $category->update($validated);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized.');
        }

        $category->delete();

        return back()->with('success', 'Category deleted successfully.');
    }
}
