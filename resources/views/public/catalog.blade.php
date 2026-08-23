@extends('layouts.public')

@section('title', 'Katalog Lengkap Kerajinan Marmer & Onyx Tulungagung')

@section('content')

<!-- Header Breadcrumb Banner -->
<div class="bg-slate-900 text-white py-10 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 text-xs text-slate-400 mb-2">
            <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
            <span>/</span>
            <span class="text-blue-400 font-semibold">Katalog Produk</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white">
            Katalog Produk Kerajinan Marmer, Onyx & Batu Kali
        </h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl">
            Koleksi lengkap wastafel cuci tangan, stepping stone taman, kap lampu, dan ornamen batuan alam langsung dari sentra pengrajin Tulungagung.
        </p>
    </div>
</div>

<!-- Main Catalog Content -->
<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter & Search Form -->
        <form method="GET" action="{{ route('catalog') }}" class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-sm mb-8 space-y-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Search Box -->
                <div class="lg:col-span-1">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Pencarian Produk</label>
                    <div class="relative">
                        <input type="text" 
                               name="q" 
                               value="{{ request('q') }}" 
                               placeholder="Cari wastafel, onix, dimensi..." 
                               class="w-full pl-9 pr-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-2.5"></i>
                    </div>
                </div>

                <!-- Category Select -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori Produk</label>
                    <select name="category" onchange="this.form.submit()" class="w-full py-2 px-3 border border-slate-200 rounded-xl text-xs bg-white text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="all">Semua Kategori</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->products_count }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Material Type Filter -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Batuan Alam</label>
                    <select name="material" onchange="this.form.submit()" class="w-full py-2 px-3 border border-slate-200 rounded-xl text-xs bg-white text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="all">Semua Batuan</option>
                        <option value="marmer" {{ request('material') == 'marmer' ? 'selected' : '' }}>Batu Marmer Tulungagung</option>
                        <option value="onix" {{ request('material') == 'onix' ? 'selected' : '' }}>Batu Onyx Tembus Cahaya</option>
                        <option value="batu_kali" {{ request('material') == 'batu_kali' ? 'selected' : '' }}>Batu Kali Alami</option>
                    </select>
                </div>

                <!-- Sorting -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Urutkan Berdasarkan</label>
                    <select name="sort" onchange="this.form.submit()" class="w-full py-2 px-3 border border-slate-200 rounded-xl text-xs bg-white text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Paling Populer</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                        <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stok Siap Kirim Terbanyak</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama Produk (A-Z)</option>
                    </select>
                </div>

            </div>

            <!-- Active Filters & Reset Buttons -->
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                <span class="text-slate-500">
                    Menampilkan <b>{{ $products->total() }}</b> produk kerajinan marmer
                </span>
                
                @if(request()->hasAny(['q', 'category', 'material', 'sort', 'stock']))
                <a href="{{ route('catalog') }}" class="text-red-600 hover:text-red-800 font-semibold flex items-center gap-1">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    <span>Reset Filter</span>
                </a>
                @endif
            </div>

        </form>

        <!-- Product Grid -->
        @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $item)
            @php
                $isPutraAbadi = in_array($item->material_type, ['batu_kali']) || 
                               str_contains(strtolower($item->name), 'kali') || 
                               str_contains(strtolower($item->name), 'stepping') || 
                               str_contains(strtolower($item->name), 'lampu');
                $artisanName = $isPutraAbadi ? 'UD Putra Abadi' : 'UD Cahaya Onix';
                $artisanPhone = $isPutraAbadi ? '6281298765432' : '6281234567890';
                $waMessage = "Halo {$artisanName}, saya tertarik untuk memesan produk *" . e($item->name) . "* (Kode: {$item->product_code}). Mohon info ketersediaan stok & ongkir.";
            @endphp
            <div class="product-card group bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-lg transition duration-300 flex flex-col justify-between cursor-pointer"
                 onclick="openCatalogModal({{ $item->id }})">
                
                <div>
                    <!-- Product Image Box (1:1 Ratio) -->
                    <div class="relative aspect-square w-full bg-slate-100 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset($item->image_path ?: 'images/products/wastafel-marmer-putih.svg') }}" 
                             alt="{{ $item->name }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        
                        <!-- Badges -->
                        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1">
                            <span class="bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-semibold px-2 py-0.5 rounded shadow">
                                {{ $item->category->name ?? 'Kerajinan' }}
                            </span>
                            @if($item->ready_stock > 0)
                            <span class="bg-emerald-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow">
                                Ready: {{ $item->ready_stock }} unit
                            </span>
                            @else
                            <span class="bg-amber-500 text-slate-900 text-[10px] font-bold px-2 py-0.5 rounded shadow">
                                Pre-Order
                            </span>
                            @endif
                        </div>

                        <!-- Quick View Overlay Button -->
                        <div class="absolute inset-0 bg-slate-900/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white font-bold text-xs gap-1.5 backdrop-blur-[2px]">
                            <span class="bg-slate-900/90 px-3 py-1.5 rounded-lg shadow flex items-center gap-1.5">
                                <i data-lucide="eye" class="w-4 h-4"></i> Lihat Detail Produk
                            </span>
                        </div>
                    </div>

                    <!-- Product Content -->
                    <div class="p-4 space-y-2">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="font-semibold text-blue-700">{{ $artisanName }}</span>
                            <span class="text-slate-400 font-mono text-[10px]">{{ $item->product_code }}</span>
                        </div>

                        <h3 class="font-bold text-sm text-slate-800 leading-snug line-clamp-2 min-h-[2.5rem]">
                            {{ $item->name }}
                        </h3>

                        <!-- Tech Specs Pills -->
                        <div class="text-[11px] text-slate-500 space-y-1 bg-slate-50 p-2 rounded-lg border border-slate-100">
                            <div class="flex items-center justify-between">
                                <span>Dimensi:</span>
                                <b class="text-slate-700">{{ $item->dimension_spec ?: 'D: 40cm, T: 15cm' }}</b>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Finishing:</span>
                                <span class="text-slate-700 font-medium">{{ $item->finishing_type ?: 'Hi-Glossy' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Footer / Price & Order -->
                <div class="p-4 pt-0" onclick="event.stopPropagation()">
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        <div>
                            <p class="text-[10px] text-slate-400 uppercase font-semibold">Harga</p>
                            <p class="text-sm font-extrabold text-slate-900">
                                Rp {{ number_format($item->selling_price, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('checkout.show', $item->id) }}" 
                               class="bg-blue-700 hover:bg-blue-600 text-white text-xs font-bold px-3 py-2 rounded-xl transition flex items-center gap-1 shadow-sm">
                                <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i>
                                <span>Beli</span>
                            </a>
                            <a href="https://wa.me/{{ $artisanPhone }}?text={{ urlencode($waMessage) }}" 
                               target="_blank" 
                               title="Tanya Serat via WhatsApp"
                               class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold p-2 rounded-xl transition flex items-center justify-center">
                                <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $products->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-8">
            <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="search-x" class="w-8 h-8"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800">Tidak ada produk yang cocok</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                Coba sesuaikan kata kunci pencarian atau reset filter kategori untuk melihat koleksi lainnya.
            </p>
            <a href="{{ route('catalog') }}" class="mt-4 inline-flex items-center gap-2 bg-blue-900 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow">
                <span>Reset Semua Filter</span>
            </a>
        </div>
        @endif

    </div>
</div>

<!-- QUICK VIEW MODAL -->
<div id="catalog-modal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto custom-scrollbar shadow-2xl border border-slate-200 relative animate-in fade-in zoom-in duration-200">
        
        <button onclick="closeCatalogModal()" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-700 bg-slate-100 rounded-full z-10 transition">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <div class="p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-center">
                <div class="bg-slate-100 rounded-2xl aspect-square w-full flex items-center justify-center border border-slate-200 overflow-hidden">
                    <img id="cat-modal-img" src="" alt="Product Image" class="w-full h-full object-cover">
                </div>

                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span id="cat-modal-artisan" class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full">
                            UD Cahaya Onix
                        </span>
                        <span id="cat-modal-code" class="text-xs text-slate-400 font-mono">PRD-WSF-01</span>
                    </div>

                    <h3 id="cat-modal-name" class="text-lg sm:text-xl font-extrabold text-slate-900 leading-tight">
                        Nama Produk Marmer
                    </h3>

                    <div class="pt-2">
                        <p class="text-[10px] text-slate-400 uppercase font-semibold">Harga Pengrajin</p>
                        <p id="cat-modal-price" class="text-2xl font-black text-slate-900">Rp 0</p>
                    </div>

                    <div id="cat-modal-stock" class="pt-1"></div>
                </div>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2 text-xs">
                <h4 class="font-bold text-slate-800 flex items-center gap-1.5">
                    <i data-lucide="info" class="w-4 h-4 text-blue-600"></i> Spesifikasi Teknis:
                </h4>
                <div class="grid grid-cols-2 gap-2 text-slate-600 pt-1">
                    <div>
                        <span class="text-slate-400 block text-[11px]">Dimensi Produk:</span>
                        <b id="cat-modal-dim" class="text-slate-800">-</b>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Finishing:</span>
                        <b id="cat-modal-finish" class="text-slate-800">-</b>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Lubang Afur:</span>
                        <b class="text-slate-800">4.5 cm (Universal)</b>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Asal Produksi:</span>
                        <b id="cat-modal-loc" class="text-slate-800">Tulungagung, Jatim</b>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100">
                <div class="flex items-center gap-2">
                    <button type="button" onclick="closeCatalogModal()" class="text-xs font-semibold text-slate-500 hover:text-slate-800 px-3 py-2">
                        Tutup
                    </button>
                    <a id="cat-modal-detail" href="#" class="text-xs font-semibold text-blue-700 hover:underline px-2 py-2">
                        Lihat Halaman Lengkap &rarr;
                    </a>
                </div>
                <div class="flex items-center gap-2">
                    <a id="cat-modal-wa" href="#" target="_blank" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        <span>Tanya WA</span>
                    </a>
                    <a id="cat-modal-checkout" href="#" class="bg-blue-700 hover:bg-blue-600 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition flex items-center gap-1.5 shadow-md shadow-blue-700/20">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        <span>Beli / Checkout</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
    function openCatalogModal(productId) {
        fetch(`{{ url('/katalog') }}/${productId}?json=1`)
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    const data = res.data;
                    document.getElementById('cat-modal-img').src = data.image_url;
                    document.getElementById('cat-modal-name').innerText = data.name;
                    document.getElementById('cat-modal-code').innerText = data.product_code;
                    document.getElementById('cat-modal-artisan').innerText = data.artisan.name;
                    document.getElementById('cat-modal-price').innerText = data.formatted_price;
                    document.getElementById('cat-modal-dim').innerText = data.dimension_spec;
                    document.getElementById('cat-modal-finish').innerText = data.finishing_type;
                    document.getElementById('cat-modal-loc').innerText = data.artisan.location;
                    document.getElementById('cat-modal-wa').href = data.wa_link;
                    document.getElementById('cat-modal-checkout').href = data.checkout_url || `{{ url('/checkout') }}/${data.id}`;
                    document.getElementById('cat-modal-detail').href = data.detail_url || `{{ url('/katalog') }}/${data.id}`;

                    const stockContainer = document.getElementById('cat-modal-stock');
                    if (data.ready_stock > 0) {
                        stockContainer.innerHTML = `<span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-1 rounded-md">Ready Stock: ${data.ready_stock} unit</span>`;
                    } else {
                        stockContainer.innerHTML = `<span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-1 rounded-md">Pre-Order 3-5 Hari Kerja</span>`;
                    }

                    document.getElementById('catalog-modal').classList.remove('hidden');
                    lucide.createIcons();
                }
            });
    }

    function closeCatalogModal() {
        document.getElementById('catalog-modal').classList.add('hidden');
    }
</script>
@endsection
