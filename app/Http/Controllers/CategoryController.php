<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['nullable', 'in:material,product'],
            'description' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'type' => $validated['type'] ?? 'product',
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->back()
            ->with('success', "Kategori '{$validated['name']}' berhasil ditambahkan!");
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;

        while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $category->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->back()
            ->with('success', "Kategori '{$category->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Proteksi: jangan hapus kategori jika masih ada produk yang memakainya
        $productCount = $category->products()->count();
        if ($productCount > 0) {
            return redirect()->back()
                ->with('error', "Kategori '{$category->name}' tidak dapat dihapus karena masih digunakan oleh {$productCount} produk!");
        }

        $categoryName = $category->name;
        $category->delete();

        return redirect()->back()
            ->with('success', "Kategori '{$categoryName}' berhasil dihapus dari sistem.");
    }
}
