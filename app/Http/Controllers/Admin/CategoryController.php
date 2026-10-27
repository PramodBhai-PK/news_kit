<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('articles')->orderBy('order')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'hindi_name' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:20'],
            'order' => ['nullable', 'integer'],
            'description' => ['nullable', 'string', 'max:300'],
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'hindi_name' => $validated['hindi_name'] ?? null,
            'color' => $validated['color'] ?: '#E11D48',
            'order' => $validated['order'] ?? 0,
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        return back()->with('success', 'कैटेगरी सफलतापूर्वक जोड़ दी गई है!');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'hindi_name' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:20'],
            'order' => ['nullable', 'integer'],
            'description' => ['nullable', 'string', 'max:300'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'hindi_name' => $validated['hindi_name'] ?? null,
            'color' => $validated['color'] ?: $category->color,
            'order' => $validated['order'] ?? $category->order,
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'कैटेगरी सफलतापूर्वक अपडेट हो गई है!');
    }

    public function destroy(Category $category)
    {
        if ($category->articles()->count() > 0) {
            return back()->with('error', 'इस कैटेगरी में ख़बरें मौजूद हैं, इसे सीधे हटाया नहीं जा सकता!');
        }

        $category->delete();

        return back()->with('success', 'कैटेगरी हटा दी गई है।');
    }
}
