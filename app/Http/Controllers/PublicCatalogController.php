<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicCatalogController extends Controller
{
    /**
     * Display Public Landing Page & Featured Catalog Showcase.
     */
    public function index()
    {
        // 1. Fetch Product Categories with product counts
        $categories = Category::where('type', 'product')
            ->withCount('products')
            ->get();

        // 2. Fetch Featured Products (Showcase)
        $featuredProducts = Product::with('category')
            ->orderBy('ready_stock', 'desc')
            ->orderBy('id', 'asc')
            ->take(8)
            ->get();

        // 3. IKM Profiles Data
        $ikmProfiles = [
            'cahaya_onix' => [
                'name' => 'UD Cahaya Onix',
                'owner' => 'M. Ilham Nur Amali',
                'tagline' => 'Spesialis Wastafel Marmer Putih, Onix Tembus Cahaya & Pedestal Mewah',
                'description' => 'Didirikan di sentra batuan Campurdarat Tulungagung, berfokus pada pengolahan marmer putih kristal dan batuan onix transparan berkualitas ekspor dengan standar poles Hi-Glossy.',
                'phone' => '6281234567890',
                'location' => 'Desa Besole / Campurdarat, Kabupaten Tulungagung, Jawa Timur',
                'specialties' => ['Wastafel Onix Tembus Cahaya', 'Wastafel Marmer Putih B1', 'Pedestal Luxury', 'Meja Marmer'],
                'color' => 'blue',
            ],
            'putra_abadi' => [
                'name' => 'UD Putra Abadi',
                'owner' => 'Efri Saputra',
                'tagline' => 'Spesialis Olahan Batu Kali Alami, Stepping Stone & Hilirisasi Residu Ramah Lingkungan',
                'description' => 'Mengolah batuan kali alam asli aliran sungai Tulungagung menjadi wastafel artistik, batu pijakan taman (stepping stone), kap lampu, serta memanfaatkan residu potongan menjadi wall cladding.',
                'phone' => '6281298765432',
                'location' => 'Kecamatan Campurdarat, Kabupaten Tulungagung, Jawa Timur',
                'specialties' => ['Wastafel Batu Kali Alami', 'Stepping Stone Taman', 'Kap Lampu Batu Kali', 'Wall Cladding'],
                'color' => 'emerald',
            ],
        ];

        // 4. Cluster Statistics
        $stats = [
            'total_products' => Product::count(),
            'ready_units' => Product::sum('ready_stock'),
            'categories_count' => $categories->count(),
            'satisfaction_rate' => 99.4,
        ];

        return view('public.index', compact('categories', 'featuredProducts', 'ikmProfiles', 'stats'));
    }

    /**
     * Display Full Searchable & Filterable Catalog.
     */
    public function catalog(Request $request)
    {
        $query = Product::with('category');

        // Filter by Search Keyword
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%")
                  ->orWhere('dimension_spec', 'like', "%{$search}%")
                  ->orWhere('finishing_type', 'like', "%{$search}%");
            });
        }

        // Filter by Category Slug or ID
        if ($request->filled('category') && $request->category !== 'all') {
            $categorySlug = $request->category;
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug)->orWhere('id', $categorySlug);
            });
        }

        // Filter by Material Type (marmer, onix, batu_kali, kombinasi)
        if ($request->filled('material') && $request->material !== 'all') {
            $query->where('material_type', $request->material);
        }

        // Filter by Stock Status
        if ($request->filled('stock')) {
            if ($request->stock === 'ready') {
                $query->where('ready_stock', '>', 0);
            } elseif ($request->stock === 'preorder') {
                $query->where('ready_stock', '<=', 0);
            }
        }

        // Sorting
        $sort = $request->input('sort', 'popular');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('selling_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('selling_price', 'desc');
                break;
            case 'stock_desc':
                $query->orderBy('ready_stock', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'popular':
            default:
                $query->orderBy('ready_stock', 'desc')->orderBy('id', 'asc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::where('type', 'product')
            ->withCount('products')
            ->get();

        return view('public.catalog', compact('products', 'categories'));
    }

    /**
     * Display Single Product Detail / Return JSON for Quick View Modal.
     */
    public function show(Request $request, $id)
    {
        $product = Product::with('category')->findOrFail($id);

        // If AJAX / JSON Quick View requested
        if ($request->wantsJson() || $request->ajax() || $request->has('json')) {
            // Determine Artisan Partner Info
            $isPutraAbadi = in_array($product->material_type, ['batu_kali']) || 
                           str_contains(strtolower($product->name), 'kali') || 
                           str_contains(strtolower($product->name), 'stepping') || 
                           str_contains(strtolower($product->name), 'lampu');

            $artisan = $isPutraAbadi ? [
                'name' => 'UD Putra Abadi',
                'owner' => 'Efri Saputra',
                'phone' => '6281298765432',
                'location' => 'Campurdarat, Tulungagung',
            ] : [
                'name' => 'UD Cahaya Onix',
                'owner' => 'M. Ilham Nur Amali',
                'phone' => '6281234567890',
                'location' => 'Besole, Campurdarat, Tulungagung',
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $product->id,
                    'product_code' => $product->product_code,
                    'name' => $product->name,
                    'category_name' => $product->category->name ?? 'Kerajinan Marmer',
                    'material_type' => $product->material_type,
                    'dimension_spec' => $product->dimension_spec ?? 'Standar Pengrajin',
                    'finishing_type' => $product->finishing_type ?? 'Hi-Glossy',
                    'ready_stock' => $product->ready_stock,
                    'selling_price' => $product->selling_price,
                    'formatted_price' => 'Rp ' . number_format($product->selling_price, 0, ',', '.'),
                    'image_url' => asset($product->image_path ?: 'images/products/wastafel-marmer-putih.svg'),
                    'artisan' => $artisan,
                    'wa_link' => 'https://wa.me/' . $artisan['phone'] . '?text=' . urlencode(
                        "Halo {$artisan['name']}, saya tertarik dengan produk *{$product->name}* (Kode: {$product->product_code}) yang ada di katalog E-SCM. Apakah stok ready atau bisa custom ukuran?"
                    ),
                ]
            ]);
        }

        // Related recommendations
        $relatedProducts = Product::with('category')
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                $q->where('category_id', $product->category_id)
                  ->orWhere('material_type', $product->material_type);
            })
            ->take(3)
            ->get();

        $isPutraAbadi = in_array($product->material_type, ['batu_kali']) || 
                       str_contains(strtolower($product->name), 'kali') || 
                       str_contains(strtolower($product->name), 'stepping') || 
                       str_contains(strtolower($product->name), 'lampu');

        $artisan = $isPutraAbadi ? [
            'name' => 'UD Putra Abadi',
            'owner' => 'Efri Saputra',
            'phone' => '6281298765432',
            'location' => 'Campurdarat, Tulungagung',
        ] : [
            'name' => 'UD Cahaya Onix',
            'owner' => 'M. Ilham Nur Amali',
            'phone' => '6281234567890',
            'location' => 'Besole, Campurdarat, Tulungagung',
        ];

        return view('public.detail', compact('product', 'relatedProducts', 'artisan'));
    }
}
