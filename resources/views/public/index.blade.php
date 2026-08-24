@extends('layouts.public')

@section('title', 'Kerajinan Marmer & Onyx Asli Tulungagung - E-SCM Showcase')

@section('styles')
    <link rel="preload" as="image" href="{{ asset($heroProduct->image_path ?: 'images/products/wastafel-onyx-tembus-cahaya.webp') }}" type="image/webp" fetchpriority="high">
@endsection

@section('content')

<!-- ============================================================================ -->
<!-- 1. HERO SECTION                                                              -->
<!-- ============================================================================ -->
<section class="relative bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 text-white pt-16 pb-24 overflow-hidden">
    <!-- Decorative background glow and texture -->
    <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:24px_24px] pointer-events-none"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Hero Text -->
            <div class="lg:col-span-7 space-y-6">
                
                <div class="inline-flex items-center gap-2 bg-blue-500/20 border border-blue-400/30 text-blue-300 px-3.5 py-1.5 rounded-full text-xs font-semibold backdrop-blur-md">
                    <i data-lucide="sparkles" class="w-4 h-4 text-amber-400"></i>
                    <span>Koleksi Kerajinan Batuan Alam Kualitas Ekspor</span>
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight text-white">
                    Kerajinan Marmer & Onyx Asli <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-sky-300 to-amber-300">Tulungagung</span>
                </h1>

                <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-2xl">
                    Etalase produk langsung dari sentra pengrajin binaan <b>UD Cahaya Onix</b> & <b>UD Putra Abadi</b>. Dipahat dengan presisi dari tambang batuan alam Campurdarat, menghadirkan kemewahan wastafel, onix tembus cahaya, dan ornamen alami ke hunian Anda.
                </p>

                <!-- Dual Action CTAs -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <a href="#katalog" class="bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm px-6 py-3.5 rounded-xl transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2 text-center">
                        <i data-lucide="grid" class="w-4 h-4"></i>
                        <span>Jelajahi Katalog Produk</span>
                    </a>
                    
                    <a href="https://wa.me/6281234567890?text=Halo%20Pengrajin%20Marmer%20Tulungagung,%20saya%20tertarik%20untuk%20konsultasi%20custom%20order%20produk." target="_blank" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm px-6 py-3.5 rounded-xl transition shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2 text-center">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        <span>Konsultasi Custom Order WA</span>
                    </a>
                </div>

                <!-- 4 Quick Trust Badges -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-6 border-t border-slate-800/80">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400 flex-shrink-0">
                            <i data-lucide="gem" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[11px] font-medium text-slate-300">100% Batuan Alami Murni</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400 flex-shrink-0">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[11px] font-medium text-slate-300">QC 2-Tahap Bersertifikat</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-400 flex-shrink-0">
                            <i data-lucide="package-check" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[11px] font-medium text-slate-300">Peti Kayu Anti-Pecah</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400 flex-shrink-0">
                            <i data-lucide="award" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[11px] font-medium text-slate-300">Harga Langsung Pengrajin</span>
                    </div>
                </div>

            </div>

            <!-- Right Hero Card / Product Highlight (LP-01 & LP-02 SOLVED: DYNAMIC DATA) -->
            <div class="lg:col-span-5 relative">
                <div class="bg-gradient-to-b from-slate-800/90 to-slate-900/90 p-5 rounded-3xl border border-slate-700/80 shadow-2xl backdrop-blur-xl relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-amber-500/20 rounded-full blur-2xl"></div>
                    
                    <!-- Highlight Image -->
                    <div class="relative h-64 sm:h-72 rounded-2xl overflow-hidden bg-slate-950 flex items-center justify-center border border-slate-700/50">
                        <img src="{{ asset($heroProduct->image_path ?: 'images/products/wastafel-onyx-tembus-cahaya.webp') }}" 
                             alt="{{ $heroProduct->name ?? 'Wastafel Onyx' }}" 
                             fetchpriority="high" 
                             decoding="async" 
                             width="400" 
                             height="400"
                             class="w-full h-full object-contain p-4 group-hover:scale-105 transition duration-500">
                        
                        <span class="absolute top-3 left-3 bg-amber-500 text-slate-950 font-extrabold text-[11px] px-2.5 py-1 rounded-full shadow">
                            ★ Unggulan Ekspor
                        </span>

                        <span class="absolute bottom-3 right-3 bg-slate-950/80 backdrop-blur-md text-slate-200 text-xs px-3 py-1 rounded-lg border border-slate-700">
                            {{ ucfirst(str_replace('_', ' ', $heroProduct->material_type ?? 'Batu Alam')) }}
                        </span>
                    </div>

                    <!-- Highlight Meta -->
                    <div class="mt-5 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-blue-400">UD Cahaya Onix</span>
                            <span class="text-xs font-bold text-emerald-400 bg-emerald-950/60 border border-emerald-800 px-2 py-0.5 rounded">
                                {{ ($heroProduct->ready_stock ?? 0) > 0 ? 'Ready Stock: ' . $heroProduct->ready_stock . ' Unit' : 'Pre-Order Pengrajin' }}
                            </span>
                        </div>
                        <h2 class="text-lg font-bold text-white leading-snug">{{ $heroProduct->name ?? 'Wastafel Onyx Tembus Cahaya Eksklusif' }}</h2>
                        <p class="text-xs text-slate-300">{{ $heroProduct->dimension_spec ?? 'D: 42 cm, T: 14 cm' }} • Finishing: {{ $heroProduct->finishing_type ?? 'Hi-Glossy Translucent' }}</p>
                        
                        <div class="pt-3 flex items-center justify-between border-t border-slate-800">
                            <div>
                                <p class="text-[10px] text-slate-300 uppercase tracking-wider">Harga Pengrajin</p>
                                <p class="text-lg font-black text-amber-400">Rp {{ number_format($heroProduct->selling_price ?? 950000, 0, ',', '.') }}</p>
                            </div>
                            <button onclick="openProductModal({{ $heroProduct->id ?? 9 }})" class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold px-4 py-2 rounded-xl transition flex items-center gap-1.5 shadow">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                <span>Lihat Spesifikasi</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================================ -->
<!-- 2. PROFIL 2 IKM MITRA BINAAN                                                 -->
<!-- ============================================================================ -->
<section id="profil-ikm" class="py-16 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-xs font-bold text-blue-700 uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-full border border-blue-200">
                Pilar Rantai Pasok Terintegrasi
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-3">
                Sentra Pengrajin Marmer & Batu Alam Mitra
            </h2>
            <p class="text-slate-600 text-xs sm:text-sm mt-2">
                Menghubungkan keahlian turun-temurun pengrajin lokal dengan standarisasi modern mutu batuan alam dan sistem kontrol pasokan digital.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- IKM Card 1: UD Cahaya Onix -->
            <div class="bg-gradient-to-br from-slate-50 to-blue-50/50 p-6 sm:p-8 rounded-3xl border border-blue-200/80 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <span class="bg-blue-900 text-white text-xs font-bold px-3 py-1 rounded-lg shadow-sm">
                        UD CAHAYA ONIX
                    </span>
                    <span class="text-xs text-slate-500 font-medium flex items-center gap-1">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-blue-600"></i> Campurdarat, Tulungagung
                    </span>
                </div>

                <h3 class="text-lg sm:text-xl font-bold text-slate-900">
                    Spesialis Wastafel Marmer Putih & Onyx Mewah
                </h3>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                    Dipimpin oleh <b>M. Ilham Nur Amali</b>, UD Cahaya Onix mengolah bongkahan marmer putih B1 dan batu onyx transparan dari pegunungan Campurdarat menjadi wastafel washi bowl, pedestal kamar mandi hotel, dan meja marmer berpola urat alami eksotis.
                </p>

                <div class="mt-4 pt-4 border-t border-blue-100">
                    <p class="text-[11px] font-bold text-slate-700 mb-2">Katalog Spesialisasi:</p>
                    <div class="flex flex-wrap gap-1.5">
                        <span class="text-[11px] bg-white border border-blue-200 text-blue-800 px-2.5 py-1 rounded-md font-medium">Wastafel Marmer Putih B1</span>
                        <span class="text-[11px] bg-white border border-blue-200 text-blue-800 px-2.5 py-1 rounded-md font-medium">Onyx Tembus Cahaya</span>
                        <span class="text-[11px] bg-white border border-blue-200 text-blue-800 px-2.5 py-1 rounded-md font-medium">Pedestal Luxury 85cm</span>
                        <span class="text-[11px] bg-white border border-blue-200 text-blue-800 px-2.5 py-1 rounded-md font-medium">Meja Marmer Bundar</span>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <a href="{{ route('catalog') }}" class="text-xs font-bold text-blue-700 hover:text-blue-900 flex items-center gap-1">
                        <span>Lihat Koleksi Pengrajin</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                    <a href="https://wa.me/6281234567890?text=Halo%20UD%20Cahaya%20Onix,%20saya%20ingin%20tanya%20produk%20marmer/onix." target="_blank" class="bg-blue-900 hover:bg-blue-800 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 transition">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-blue-300"></i> Kontak Pengrajin
                    </a>
                </div>
            </div>

            <!-- IKM Card 2: UD Putra Abadi -->
            <div class="bg-gradient-to-br from-slate-50 to-emerald-50/50 p-6 sm:p-8 rounded-3xl border border-emerald-200/80 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <span class="bg-emerald-800 text-white text-xs font-bold px-3 py-1 rounded-lg shadow-sm">
                        UD PUTRA ABADI
                    </span>
                    <span class="text-xs text-slate-500 font-medium flex items-center gap-1">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-emerald-600"></i> Campurdarat, Tulungagung
                    </span>
                </div>

                <h3 class="text-lg font-bold text-slate-900">
                    Spesialis Wastafel Batu Kali Alami, Kap Lampu & Stepping Stone
                </h3>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                    Dipimpin oleh <b>Efri Saputra</b>, UD Putra Abadi mengangkat potensi batuan kali alam dari aliran sungai Tulungagung menjadi wastafel bernuansa natural tropis, pijakan taman artistik, serta hilirisasi residu potongan batu menjadi wall cladding ramah lingkungan.
                </p>

                <div class="mt-4 pt-4 border-t border-emerald-100">
                    <p class="text-[11px] font-bold text-slate-700 mb-2">Katalog Spesialisasi:</p>
                    <div class="flex flex-wrap gap-1.5">
                        <span class="text-[11px] bg-white border border-emerald-200 text-emerald-800 px-2.5 py-1 rounded-md font-medium">Wastafel Batu Kali Alami</span>
                        <span class="text-[11px] bg-white border border-emerald-200 text-emerald-800 px-2.5 py-1 rounded-md font-medium">Stepping Stone Taman</span>
                        <span class="text-[11px] bg-white border border-emerald-200 text-emerald-800 px-2.5 py-1 rounded-md font-medium">Kap Lampu Hias Minimalis</span>
                        <span class="text-[11px] bg-white border border-emerald-200 text-emerald-800 px-2.5 py-1 rounded-md font-medium">Wall Cladding Residu</span>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <a href="{{ route('catalog', ['material' => 'batu_kali']) }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-900 flex items-center gap-1">
                        <span>Lihat Produk UD Putra Abadi</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                    <a href="https://wa.me/6281298765432?text=Halo%20UD%20Putra%20Abadi,%20saya%20ingin%20tanya%20produk%20batu%20kali/stepping%20stone." target="_blank" class="bg-emerald-800 hover:bg-emerald-700 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 transition">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-emerald-300"></i> Kontak Pengrajin
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ============================================================================ -->
<!-- 3. ETALASE PRODUK UNGGULAN (KATALOG RINGKAS)                                 -->
<!-- ============================================================================ -->
<section id="katalog-unggulan" class="py-16 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
            <div>
                <span class="text-xs font-bold text-blue-700 uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-full border border-blue-200">
                    Etalase Hasil Karya Pengrajin
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
                    Koleksi Kerajinan Marmer & Onix Unggulan
                </h2>
                <p class="text-slate-600 text-xs sm:text-sm mt-1">
                    Beli langsung dari pengrajin Campurdarat Tulungagung dengan harga tangan pertama, berstandar ekspor, dan garansi packing kayu solid.
                </p>
            </div>
            
            <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 text-xs font-bold text-blue-800 hover:text-blue-950 bg-white border border-slate-300 hover:border-blue-500 px-4 py-2.5 rounded-xl shadow-sm transition flex-shrink-0">
                <span>Buka Seluruh Katalog ({{ $stats['total_products'] }} Produk)</span>
                <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
            </a>
        </div>

        <!-- Filter Tab Buttons with Dynamic Count Badges (LP-03 SOLVED) -->
        <div class="flex items-center gap-2 overflow-x-auto pb-3 custom-scrollbar mb-6">
            <button onclick="filterCatalog('all')" id="tab-all" class="catalog-tab px-4 py-2 rounded-xl text-xs font-bold transition bg-blue-900 text-white shadow-sm flex-shrink-0">
                Semua Produk ({{ $materialCounts['all'] ?? $featuredProducts->count() }})
            </button>
            <button onclick="filterCatalog('marmer')" id="tab-marmer" class="catalog-tab px-4 py-2 rounded-xl text-xs font-bold transition bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 flex-shrink-0">
                Wastafel Marmer ({{ $materialCounts['marmer'] ?? 0 }})
            </button>
            <button onclick="filterCatalog('onix')" id="tab-onix" class="catalog-tab px-4 py-2 rounded-xl text-xs font-bold transition bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 flex-shrink-0">
                Wastafel Onyx Tembus Cahaya ({{ $materialCounts['onix'] ?? 0 }})
            </button>
            <button onclick="filterCatalog('batu_kali')" id="tab-batu_kali" class="catalog-tab px-4 py-2 rounded-xl text-xs font-bold transition bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 flex-shrink-0">
                Batu Kali & Stepping Stone ({{ $materialCounts['batu_kali'] ?? 0 }})
            </button>
        </div>

        <!-- Product Grid (8 Featured Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="product-grid">
            @foreach($featuredProducts as $item)
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
                 data-material="{{ $item->material_type }}"
                 onclick="openProductModal({{ $item->id }})">
                
                <div>
                    <!-- Product Image Box (1:1 Ratio) -->
                    <div class="relative aspect-square w-full bg-slate-100 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset($item->image_path ?: 'images/products/wastafel-marmer-putih.svg') }}" 
                             alt="{{ $item->name }}" 
                             loading="lazy"
                             decoding="async"
                             width="300"
                             height="300"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        
                        <!-- Badges -->
                        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1">
                            <span class="bg-slate-900/90 backdrop-blur-md text-white text-[10px] font-semibold px-2 py-0.5 rounded shadow">
                                {{ $item->category->name ?? 'Kerajinan' }}
                            </span>
                            @if($item->ready_stock > 0)
                            <span class="bg-emerald-700 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow">
                                Ready: {{ $item->ready_stock }} unit
                            </span>
                            @else
                            <span class="bg-amber-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow">
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
                            <span class="text-slate-600 font-mono text-[11px] font-medium">{{ $item->product_code }}</span>
                        </div>

                        <h3 class="font-bold text-sm text-slate-800 leading-snug line-clamp-2 min-h-[2.5rem]">
                            {{ $item->name }}
                        </h3>

                        <!-- Tech Specs Pills -->
                        <div class="text-[11px] text-slate-600 space-y-1 bg-slate-50 p-2 rounded-lg border border-slate-200">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-600">Dimensi:</span>
                                <b class="text-slate-800">{{ $item->dimension_spec ?: 'D: 40cm, T: 15cm' }}</b>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-600">Finishing:</span>
                                <span class="text-slate-800 font-medium">{{ $item->finishing_type ?: 'Hi-Glossy' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Footer / Price & Order -->
                <div class="p-4 pt-0" onclick="event.stopPropagation()">
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        <div>
                            <p class="text-[10px] text-slate-600 uppercase font-bold">Harga</p>
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

    </div>
</section>

<!-- ============================================================================ -->
<!-- 4. ALUR RANTAI PASOK & TRANSPARANSI MUTU (SUPPLY CHAIN STORY)                -->
<!-- ============================================================================ -->
<section id="alur-rantai-pasok" class="py-16 bg-white border-y border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="text-xs font-bold text-blue-700 uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-full border border-blue-200">
                Dari Tambang ke Ruang Anda
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-3">
                Transparansi Rantai Pasok Hulu ke Hilir
            </h2>
            <p class="text-slate-600 text-xs sm:text-sm mt-2">
                Setiap produk diawasi melalui sistem E-SCM untuk memastikan keaslian batuan alam dan ketepatan spesifikasi teknis tanpa cacat.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
            
            <!-- Step 1 -->
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 relative group hover:border-blue-300 transition">
                <div class="w-12 h-12 rounded-xl bg-blue-900 text-white font-black text-base flex items-center justify-center shadow-md mb-4">
                    01
                </div>
                <h3 class="font-bold text-sm text-slate-900">Penambangan Batuan Alami</h3>
                <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                    Pengambilan bongkahan marmer putih & hitam dari tambang batuan alam serta seleksi batuan kali berkualitas tinggi di Campurdarat.
                </p>
                <div class="mt-3 text-[11px] font-semibold text-blue-700 flex items-center gap-1">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i> Bahan Baku Legal & Terdata
                </div>
            </div>

            <!-- Step 2 -->
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 relative group hover:border-blue-300 transition">
                <div class="w-12 h-12 rounded-xl bg-blue-900 text-white font-black text-base flex items-center justify-center shadow-md mb-4">
                    02
                </div>
                <h3 class="font-bold text-sm text-slate-900">Pembubutan & Pemahatan</h3>
                <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                    Pemotongan mesin slep, pembubutan manual oleh pengrajin berpengalaman, dan presisi lubang afur pembuangan universal 4.5 cm.
                </p>
                <div class="mt-3 text-[11px] font-semibold text-blue-700 flex items-center gap-1">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i> Toleransi Presisi &lt; 2mm
                </div>
            </div>

            <!-- Step 3 -->
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 relative group hover:border-blue-300 transition">
                <div class="w-12 h-12 rounded-xl bg-emerald-700 text-white font-black text-base flex items-center justify-center shadow-md mb-4">
                    03
                </div>
                <h3 class="font-bold text-sm text-slate-900">QC 2-Tahap & Poles Kilap</h3>
                <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                    Inspeksi tahap 1 untuk serat retak, dilanjutkan poles Hi-Glossy kaca atau finishing doff alami, dan uji kelancaran afur.
                </p>
                <div class="mt-3 text-[11px] font-semibold text-emerald-700 flex items-center gap-1">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i> Standar Anti-Bocor & Noda
                </div>
            </div>

            <!-- Step 4 -->
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 relative group hover:border-blue-300 transition">
                <div class="w-12 h-12 rounded-xl bg-amber-700 text-white font-black text-base flex items-center justify-center shadow-md mb-4">
                    04
                </div>
                <h3 class="font-bold text-sm text-slate-900">Peti Kayu & Ekspedisi</h3>
                <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                    Pengemasan kardus berlapis foam tebal di dalam krat pallet kayu solid bergaransi aman untuk pengiriman ke seluruh Indonesia & luar negeri.
                </p>
                <div class="mt-3 text-[11px] font-semibold text-amber-700 flex items-center gap-1">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i> Garansi Ganti Baru Pecah
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ============================================================================ -->
<!-- 5. CALLOUT BANNER: CUSTOM ORDER ARSITEKTUR & PROYEK                         -->
<!-- ============================================================================ -->
<section class="py-14 bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8 bg-white/5 p-8 sm:p-12 rounded-3xl border border-white/10 backdrop-blur-md">
            
            <div class="space-y-3 text-center lg:text-left">
                <span class="bg-amber-500/20 text-amber-300 text-xs px-3 py-1 rounded-full font-bold border border-amber-500/30">
                    Layanan B2B & Proyek Arsitektur
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white">
                    Butuh Wastafel Custom untuk Hotel, Villa, atau Hunian?
                </h2>
                <p class="text-xs sm:text-sm text-slate-300 max-w-2xl">
                    Kami menerima pesanan custom dimensi, jenis batuan onix pilihan, finishing tekstur bakar/alami, serta pengadaan massal dengan invoice & surat jalan resmi E-SCM.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto flex-shrink-0">
                <a href="https://wa.me/6281234567890?text=Halo%20Pengrajin%20Marmer,%20saya%20ingin%20konsultasi%20kebutuhan%20proyek%20arsitektur/hotel." target="_blank" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-xs px-6 py-3.5 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20">
                    <i data-lucide="message-circle" class="w-4 h-4 text-slate-950"></i>
                    <span>Hubungi Tim Pengrajin WA</span>
                </a>
                
                <a href="{{ route('catalog') }}" class="bg-white/10 hover:bg-white/20 text-white font-bold text-xs px-5 py-3.5 rounded-xl transition border border-white/20 text-center">
                    Lihat Semua Pilihan
                </a>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================================ -->
<!-- 6. QUICK VIEW PRODUCT MODAL (INTERACTIVE)                                    -->
<!-- ============================================================================ -->
<div id="product-modal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto custom-scrollbar shadow-2xl border border-slate-200 relative animate-in fade-in zoom-in duration-200">
        
        <!-- Close Button -->
        <button onclick="closeProductModal()" class="absolute top-4 right-4 p-2 text-slate-500 hover:text-slate-800 bg-slate-100 rounded-full z-10 transition" aria-label="Tutup Modal Detail Produk">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <div class="p-6 sm:p-8 space-y-6">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-center">
                <!-- Modal Image (1:1 Ratio) -->
                <div class="bg-slate-100 rounded-2xl aspect-square w-full flex items-center justify-center border border-slate-200 overflow-hidden">
                    <img id="modal-img" src="" alt="Product Image" class="w-full h-full object-cover">
                </div>

                <!-- Modal Header Details -->
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span id="modal-artisan" class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full">
                            UD Cahaya Onix
                        </span>
                        <span id="modal-code" class="text-xs text-slate-600 font-mono font-medium">PRD-WSF-01</span>
                    </div>

                    <h3 id="modal-name" class="text-lg sm:text-xl font-extrabold text-slate-900 leading-tight">
                        Nama Produk Marmer
                    </h3>

                    <div class="pt-2">
                        <p class="text-[10px] text-slate-600 uppercase font-bold">Harga Langsung Pengrajin</p>
                        <p id="modal-price" class="text-2xl font-black text-slate-900">Rp 0</p>
                    </div>

                    <div id="modal-stock-badge" class="pt-1">
                        <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-1 rounded-md">
                            Ready Stock
                        </span>
                    </div>
                </div>
            </div>

            <!-- Technical Spec Table -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2 text-xs">
                <h4 class="font-bold text-slate-800 flex items-center gap-1.5">
                    <i data-lucide="info" class="w-4 h-4 text-blue-600"></i> Spesifikasi Teknis Batuan:
                </h4>
                <div class="grid grid-cols-2 gap-2 text-slate-600 pt-1">
                    <div>
                        <span class="text-slate-600 block text-[11px] font-medium">Dimensi Produk:</span>
                        <b id="modal-dimension" class="text-slate-800">D: 40cm, T: 15cm</b>
                    </div>
                    <div>
                        <span class="text-slate-600 block text-[11px] font-medium">Tipe Finishing:</span>
                        <b id="modal-finishing" class="text-slate-800">Hi-Glossy</b>
                    </div>
                    <div>
                        <span class="text-slate-600 block text-[11px] font-medium">Standar Lubang Afur:</span>
                        <b class="text-slate-800">4.5 cm (Universal)</b>
                    </div>
                    <div>
                        <span class="text-slate-600 block text-[11px] font-medium">Lokasi Produksi:</span>
                        <b id="modal-location" class="text-slate-800">Campurdarat, Tulungagung</b>
                    </div>
                </div>
            </div>

            <!-- Modal Action -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100">
                <div class="flex items-center gap-2">
                    <button type="button" onclick="closeProductModal()" class="text-xs font-semibold text-slate-500 hover:text-slate-800 px-3 py-2">
                        Tutup
                    </button>
                    <a id="modal-detail-btn" href="#" class="text-xs font-semibold text-blue-700 hover:underline px-2 py-2">
                        Lihat Halaman Lengkap &rarr;
                    </a>
                </div>
                <div class="flex items-center gap-2">
                    <a id="modal-wa-btn" href="#" target="_blank" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        <span>Tanya WA</span>
                    </a>
                    <a id="modal-checkout-btn" href="#" class="bg-blue-700 hover:bg-blue-600 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition flex items-center gap-1.5 shadow-md shadow-blue-700/20">
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
    // Tab Filter Logic
    function filterCatalog(type) {
        // Reset tabs style
        document.querySelectorAll('.catalog-tab').forEach(tab => {
            tab.classList.remove('bg-blue-900', 'text-white', 'shadow-sm');
            tab.classList.add('bg-white', 'border', 'border-slate-200', 'text-slate-700');
        });

        // Active tab
        const activeTab = document.getElementById('tab-' + type);
        if (activeTab) {
            activeTab.classList.remove('bg-white', 'border', 'border-slate-200', 'text-slate-700');
            activeTab.classList.add('bg-blue-900', 'text-white', 'shadow-sm');
        }

        // Show/hide product cards
        const cards = document.querySelectorAll('.product-card');
        cards.forEach(card => {
            const material = card.getAttribute('data-material');
            if (type === 'all' || material === type) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Modal Quick View Logic
    function openProductModal(productId) {
        fetch(`{{ url('/katalog') }}/${productId}?json=1`)
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    const data = res.data;
                    document.getElementById('modal-img').src = data.image_url;
                    document.getElementById('modal-name').innerText = data.name;
                    document.getElementById('modal-code').innerText = data.product_code;
                    document.getElementById('modal-artisan').innerText = data.artisan.name;
                    document.getElementById('modal-price').innerText = data.formatted_price;
                    document.getElementById('modal-dimension').innerText = data.dimension_spec;
                    document.getElementById('modal-finishing').innerText = data.finishing_type;
                    document.getElementById('modal-location').innerText = data.artisan.location;
                    document.getElementById('modal-wa-btn').href = data.wa_link;
                    document.getElementById('modal-checkout-btn').href = data.checkout_url || `{{ url('/checkout') }}/${data.id}`;
                    document.getElementById('modal-detail-btn').href = data.detail_url || `{{ url('/katalog') }}/${data.id}`;

                    // Stock badge
                    const stockContainer = document.getElementById('modal-stock-badge');
                    if (data.ready_stock > 0) {
                        stockContainer.innerHTML = `<span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-1 rounded-md">Ready Stock: ${data.ready_stock} unit</span>`;
                    } else {
                        stockContainer.innerHTML = `<span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-1 rounded-md">Pre-Order 3-5 Hari Kerja</span>`;
                    }

                    document.getElementById('product-modal').classList.remove('hidden');
                    lucide.createIcons();
                }
            })
            .catch(err => {
                console.error("Error loading product details:", err);
            });
    }

    function closeProductModal() {
        document.getElementById('product-modal').classList.add('hidden');
    }
</script>
@endsection
