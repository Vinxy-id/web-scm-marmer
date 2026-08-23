<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\CodeGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%")
                  ->orWhere('dimension_spec', 'like', "%{$search}%")
                  ->orWhere('finishing_type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('material') && $request->material !== 'all') {
            $query->where('material_type', $request->material);
        }

        $products = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        $categories = Category::where('type', 'product')->get();

        $stats = [
            'total_products' => Product::count(),
            'total_stock' => Product::sum('ready_stock'),
            'low_stock' => Product::whereColumn('ready_stock', '<=', 'safety_stock')->count(),
            'marmer_count' => Product::where('material_type', 'marmer')->count(),
            'batu_kali_count' => Product::where('material_type', 'batu_kali')->count(),
            'onix_count' => Product::where('material_type', 'onix')->count(),
        ];

        // Next auto-generated code suggestion
        $suggestedCode = CodeGeneratorService::generateProductCode();

        return view('products.index', compact('products', 'categories', 'stats', 'suggestedCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'material_type' => ['required', 'in:marmer,onix,batu_kali'],
            'dimension_spec' => ['nullable', 'string', 'max:100'],
            'finishing_type' => ['nullable', 'string', 'max:100'],
            'ready_stock' => ['required', 'integer', 'min:0'],
            'safety_stock' => ['required', 'integer', 'min:0'],
            'standard_cogs' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'], // Max 5MB
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'ready_stock.integer' => 'Stok awal harus berupa bilangan bulat.',
            'safety_stock.integer' => 'Safety stock harus berupa bilangan bulat.',
            'selling_price.numeric' => 'Harga jual harus berupa nominal angka valid.',
            'image.image' => 'File yang diunggah harus berupa gambar foto.',
            'image.max' => 'Ukuran foto maksimal 5 MB.',
        ]);

        // Auto-generate consistent product code
        $productCode = CodeGeneratorService::generateProductCode($validated['material_type']);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = Str::slug($validated['name']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/products');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $fileName);
            $imagePath = 'images/products/' . $fileName;
        } else {
            // Default placeholder image based on material
            $imagePath = match ($validated['material_type']) {
                'marmer' => 'images/products/wastafel-marmer-putih.svg',
                'onix' => 'images/products/wastafel-onyx.svg',
                'batu_kali' => 'images/products/wastafel-batu-kali.svg',
                default => 'images/products/wastafel-marmer-putih.svg',
            };
        }

        Product::create([
            'product_code' => $productCode,
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'material_type' => $validated['material_type'],
            'dimension_spec' => $validated['dimension_spec'] ?? 'Standar Pengrajin',
            'finishing_type' => $validated['finishing_type'] ?? 'Hi-Glossy',
            'ready_stock' => $validated['ready_stock'],
            'safety_stock' => $validated['safety_stock'],
            'standard_cogs' => $validated['standard_cogs'],
            'selling_price' => $validated['selling_price'],
            'image_path' => $imagePath,
        ]);

        return redirect()->route('products.index')
            ->with('success', "Produk baru '{$validated['name']}' dengan Kode {$productCode} berhasil ditambahkan!");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'material_type' => ['required', 'in:marmer,onix,batu_kali'],
            'dimension_spec' => ['nullable', 'string', 'max:100'],
            'finishing_type' => ['nullable', 'string', 'max:100'],
            'ready_stock' => ['required', 'integer', 'min:0'],
            'safety_stock' => ['required', 'integer', 'min:0'],
            'standard_cogs' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'material_type' => $validated['material_type'],
            'dimension_spec' => $validated['dimension_spec'] ?? $product->dimension_spec,
            'finishing_type' => $validated['finishing_type'] ?? $product->finishing_type,
            'ready_stock' => $validated['ready_stock'],
            'safety_stock' => $validated['safety_stock'],
            'standard_cogs' => $validated['standard_cogs'],
            'selling_price' => $validated['selling_price'],
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = Str::slug($validated['name']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/products');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $fileName);
            $updateData['image_path'] = 'images/products/' . $fileName;
        }

        $product->update($updateData);

        return redirect()->route('products.index')
            ->with('success', "Data produk {$product->product_code} ({$product->name}) berhasil diperbarui!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Check if there are active work orders or customer orders
        if ($product->workOrders()->exists()) {
            return redirect()->route('products.index')
                ->with('error', "Produk {$product->product_code} tidak dapat dihapus karena telah terhubung dengan riwayat SPK Produksi.");
        }

        $code = $product->product_code;
        $name = $product->name;
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', "Produk {$code} ({$name}) berhasil dihapus dari sistem.");
    }
}
