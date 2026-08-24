@extends('layouts.public')

@section('title', $product->name . ' - Kerajinan Marmer Tulungagung')
@section('meta-description', 'Beli ' . $product->name . ' asli Tulungagung. Spesifikasi: ' . ($product->dimension_spec ?: 'Dimensi presisi') . ', finishing ' . ($product->finishing_type ?: 'Hi-Glossy') . ' langsung dari pengrajin ' . $artisan['name'] . '.')

@section('styles')
    <link rel="preload" as="image" href="{{ asset($product->image_path ?: 'images/products/wastafel-marmer-putih.svg') }}" fetchpriority="high">
@endsection

@section('content')

@php
    $waMessage = "Halo {$artisan['name']}, saya tertarik dengan produk *" . e($product->name) . "* (Kode: {$product->product_code}) di katalog E-SCM. Mohon info pemesanan, ketersediaan stok, dan estimasi ongkir ke alamat saya.";
    $waLink = "https://wa.me/{$artisan['phone']}?text=" . urlencode($waMessage);
@endphp

<!-- Breadcrumb -->
<div class="bg-slate-900 text-white py-6 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
            <span>/</span>
            <a href="{{ route('catalog') }}" class="hover:text-white transition">Katalog Produk</a>
            <span>/</span>
            <span class="text-blue-400 font-semibold truncate">{{ $product->name }}</span>
        </div>
    </div>
</div>

<!-- Main Detail Section -->
<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-10 shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                
                <!-- Left: Product Image & Gallery -->
                <div class="lg:col-span-6 space-y-4">
                    <!-- Main Image Display (1:1 Ratio) -->
                    <div class="relative bg-slate-100 rounded-3xl aspect-square w-full flex items-center justify-center border border-slate-200 overflow-hidden group">
                        <img id="main-product-img" 
                             src="{{ asset($product->image_path ?: 'images/products/wastafel-marmer-putih.svg') }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        
                        <!-- Badges -->
                        <div class="absolute top-4 left-4 flex flex-col gap-1.5">
                            <span class="bg-slate-900 text-white font-bold text-xs px-3 py-1 rounded-lg shadow">
                                {{ $product->category->name ?? 'Kerajinan Marmer' }}
                            </span>
                            @if($product->ready_stock > 0)
                            <span class="bg-emerald-600 text-white font-bold text-xs px-3 py-1 rounded-lg shadow flex items-center gap-1">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i> Ready: {{ $product->ready_stock }} unit
                            </span>
                            @else
                            <span class="bg-amber-500 text-slate-950 font-bold text-xs px-3 py-1 rounded-lg shadow">
                                Pre-Order (3-5 Hari)
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- 4 Thumbnail Gallery -->
                    <div class="grid grid-cols-4 gap-3">
                        <button onclick="setMainImage('{{ asset($product->image_path ?: 'images/products/wastafel-marmer-putih.svg') }}')" class="aspect-square bg-slate-100 rounded-xl border-2 border-blue-600 hover:opacity-80 transition overflow-hidden">
                            <img src="{{ asset($product->image_path ?: 'images/products/wastafel-marmer-putih.svg') }}" class="w-full h-full object-cover">
                        </button>
                        <div class="aspect-square bg-slate-100 rounded-xl p-2 border border-slate-200 flex flex-col items-center justify-center text-center text-[10px] text-slate-500 font-semibold">
                            <i data-lucide="disc" class="w-4 h-4 text-blue-600 mb-1"></i>
                            <span>Lubang 4.5cm</span>
                        </div>
                        <div class="aspect-square bg-slate-100 rounded-xl p-2 border border-slate-200 flex flex-col items-center justify-center text-center text-[10px] text-slate-500 font-semibold">
                            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600 mb-1"></i>
                            <span>Lolos QC 2</span>
                        </div>
                        <div class="aspect-square bg-slate-100 rounded-xl p-2 border border-slate-200 flex flex-col items-center justify-center text-center text-[10px] text-slate-500 font-semibold">
                            <i data-lucide="package" class="w-4 h-4 text-amber-600 mb-1"></i>
                            <span>Peti Kayu</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Product Specifications & Order Action -->
                <div class="lg:col-span-6 space-y-6">
                    
                    <div>
                        <!-- Artisan Credential -->
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-blue-100 text-blue-900 font-bold text-xs px-3 py-1 rounded-full flex items-center gap-1.5">
                                <i data-lucide="store" class="w-3.5 h-3.5 text-blue-700"></i>
                                <span>{{ $artisan['name'] }}</span>
                            </span>
                            <span class="text-xs text-slate-400 font-mono">Kode: {{ $product->product_code }}</span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">
                            {{ $product->name }}
                        </h1>
                        <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span>Sentra Industri Batuan Alam: {{ $artisan['location'] }}</span>
                        </p>
                    </div>

                    <!-- Price Box -->
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex items-baseline justify-between">
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Harga Pengrajin Langsung</p>
                            <p class="text-3xl font-black text-slate-900 mt-0.5">
                                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                            </p>
                        </div>
                        <span class="text-xs text-emerald-600 font-bold bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-lg">
                            Bisa Custom Ukuran
                        </span>
                    </div>

                    <!-- Technical Specs Table -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Spesifikasi Detail Batuan:</h3>
                        <div class="divide-y divide-slate-100 border border-slate-200 rounded-2xl overflow-hidden text-xs">
                            <div class="grid grid-cols-2 p-3 bg-white">
                                <span class="text-slate-500">Dimensi Produk</span>
                                <b class="text-slate-900">{{ $product->dimension_spec ?: 'D: 40 cm, T: 15 cm' }}</b>
                            </div>
                            <div class="grid grid-cols-2 p-3 bg-slate-50">
                                <span class="text-slate-500">Tipe Finishing</span>
                                <b class="text-slate-900">{{ $product->finishing_type ?: 'Hi-Glossy' }}</b>
                            </div>
                            <div class="grid grid-cols-2 p-3 bg-white">
                                <span class="text-slate-500">Bahan Baku Utama</span>
                                <b class="text-slate-900 capitalize">{{ str_replace('_', ' ', $product->material_type) }} Asli Tulungagung</b>
                            </div>
                            <div class="grid grid-cols-2 p-3 bg-slate-50">
                                <span class="text-slate-500">Diameter Lubang Afur</span>
                                <b class="text-slate-900">4.5 cm (Standar Afur Wastafel Universal)</b>
                            </div>
@php
    $pNameLower = strtolower($product->name ?? '');
    if (str_contains($pNameLower, 'stepping') || str_contains($pNameLower, 'pijakan')) {
        $weightEst = '6 - 9 kg (Batu Kali Flat Padat)';
    } elseif (str_contains($pNameLower, 'lampu')) {
        $weightEst = '8 - 12 kg (Batuan Kali Berongga)';
    } elseif (str_contains($pNameLower, 'pedestal') || str_contains($pNameLower, 'meja')) {
        $weightEst = '35 - 55 kg (Bongkahan Utuh Padat)';
    } else {
        $weightEst = '14 - 18 kg (Batuan Utuh Padat)';
    }
@endphp
                            <div class="grid grid-cols-2 p-3 bg-white">
                                <span class="text-slate-500">Estimasi Bobot Fisik</span>
                                <b class="text-slate-900">{{ $weightEst }}</b>
                            </div>
                            <div class="grid grid-cols-2 p-3 bg-slate-50">
                                <span class="text-slate-500">Standar Pengemasan</span>
                                <b class="text-slate-900">Foam Tebal + Peti Kayu Solid Pallet</b>
                            </div>
                        </div>
                    </div>

                    <!-- Order Action CTA Buttons -->
                    <div class="space-y-3 pt-2">
                        <a href="{{ route('checkout.show', $product->id) }}" class="w-full bg-blue-700 hover:bg-blue-600 text-white font-extrabold text-sm py-4 px-6 rounded-2xl transition shadow-lg shadow-blue-700/20 flex items-center justify-center gap-2">
                            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                            <span>Beli / Checkout Online (Opsi DP 50% / Lunas)</span>
                        </a>

                        <a href="{{ $waLink }}" target="_blank" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm py-3.5 px-6 rounded-2xl transition shadow-md flex items-center justify-center gap-2">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                            <span>Tanya Serat & Real Pic via WhatsApp ({{ $artisan['name'] }})</span>
                        </a>

                        <a href="https://wa.me/{{ $artisan['phone'] }}?text={{ urlencode('Halo ' . $artisan['name'] . ', saya ingin meminta video fisik atau foto serat terbaru untuk produk ' . $product->name) }}" target="_blank" class="w-full bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs py-2.5 px-4 rounded-xl border border-slate-200 transition flex items-center justify-center gap-2">
                            <i data-lucide="video" class="w-4 h-4 text-blue-600"></i>
                            <span>Minta Video Fisik / Foto Serat Batuan Terbaru</span>
                        </a>

                        <!-- Share Buttons (DET-04 SOLVED) -->
                        <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                            <span class="text-xs text-slate-500 font-medium">Bagikan Produk:</span>
                            <div class="flex items-center gap-2">
                                <button onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan produk berhasil disalin!');" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-2.5 py-1.5 rounded-lg flex items-center gap-1 transition">
                                    <i data-lucide="link" class="w-3.5 h-3.5"></i> Salin Link
                                </button>
                                <a href="https://api.whatsapp.com/send?text={{ urlencode('Lihat produk kerajinan marmer: ' . $product->name . ' - ' . url()->current()) }}" target="_blank" class="text-xs bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold px-2.5 py-1.5 rounded-lg flex items-center gap-1 transition">
                                    <i data-lucide="share-2" class="w-3.5 h-3.5"></i> Bagikan WA
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <!-- Tabbed Information Details (Keunggulan, QC, Garansi) -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <div class="border-b border-slate-200 flex gap-4 text-xs font-bold mb-6 overflow-x-auto custom-scrollbar">
                <button onclick="switchDetailTab('keunggulan')" id="dtab-keunggulan" class="dtab pb-3 text-blue-900 border-b-2 border-blue-900 flex-shrink-0">
                    Keunggulan Batuan Alami
                </button>
                <button onclick="switchDetailTab('qc')" id="dtab-qc" class="dtab pb-3 text-slate-500 hover:text-slate-800 flex-shrink-0">
                    Sertifikasi & QC 2-Tahap
                </button>
                <button onclick="switchDetailTab('garansi')" id="dtab-garansi" class="dtab pb-3 text-slate-500 hover:text-slate-800 flex-shrink-0">
                    Garansi Peti Kayu & Pengiriman
                </button>
            </div>

            <!-- Tab Content 1: Keunggulan -->
            <div id="dcontent-keunggulan" class="dcontent space-y-4 text-xs text-slate-600 leading-relaxed">
                <h4 class="font-bold text-sm text-slate-900">Karakteristik Alami Batuan Tulungagung</h4>
                <p>
                    Setiap wastafel dan kerajinan dipahat dari batuan alam pegunungan selatan Tulungagung (Campurdarat). Setiap unit memiliki guratan serat alami yang unik (*one-of-a-kind*) yang tidak mungkin identik dengan unit lainnya.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <b class="text-slate-900 block mb-1">Tahan Panas & Air</b>
                        <span>Tidak mudah tergores, tahan suhu air panas/dingin dan deterjen rumah tangga ringan.</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <b class="text-slate-900 block mb-1">Coating Pelindung Kilap</b>
                        <span>Dilapisi resin coating khusus yang mengunci kilau Hi-Glossy dan mencegah penyerapan noda.</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <b class="text-slate-900 block mb-1">Perawatan Mudah</b>
                        <span>Cukup dibersihkan dengan sabun cair lembut dan spons halus tanpa cairan asam keras.</span>
                    </div>
                </div>
            </div>

            <!-- Tab Content 2: QC -->
            <div id="dcontent-qc" class="dcontent hidden space-y-4 text-xs text-slate-600 leading-relaxed">
                <h4 class="font-bold text-sm text-slate-900">Standar Pengendalian Mutu (QC) 2-Tahap E-SCM</h4>
                <p>
                    Setiap produk yang keluar dari bengkel wajib melalui 2 tahap verifikasi digital sebelum masuk ke status *Ready Stock*:
                </p>
                <ul class="list-disc list-inside space-y-1.5 text-slate-700">
                    <li><b>QC Tahap 1 (Bentuk Mentah):</b> Memeriksa tidak adanya retak tembus (*crack scrap*) pada serat batuan setelah pemotongan dan pembubutan awal.</li>
                    <li><b>QC Tahap 2 (Finishing Kilap & Lubang Afur):</b> Memeriksa kehalusan polesan *Hi-Glossy*, simetrisitas bibir wastafel, dan uji kelancaran afur pembuangan tanpa genangan air.</li>
                </ul>
            </div>

            <!-- Tab Content 3: Garansi -->
            <div id="dcontent-garansi" class="dcontent hidden space-y-4 text-xs text-slate-600 leading-relaxed">
                <h4 class="font-bold text-sm text-slate-900">Jaminan Keamanan Ekspedisi & Asuransi</h4>
                <p>
                    Kami berpengalaman mengirimkan kerajinan marmer ke seluruh wilayah Indonesia (Jawa, Bali, Sumatera, Kalimantan, Sulawesi, hingga Papua) dan mancanegara:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <b class="text-slate-900 block mb-1">Packing Krat Kayu Solid Pallet</b>
                        <span>Produk dibungkus foam sheet 5mm, kardus tebal, dan rangka peti kayu solid agar aman dari benturan ekspedisi.</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <b class="text-slate-900 block mb-1">Garansi Ganti Baru Pecah</b>
                        <span>Jika barang diterima dalam kondisi retak/pecah akibat perjalanan, kami kirimkan unit pengganti baru setelah bukti unboxing dikonfirmasi.</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Related Products Section -->
        @if($relatedProducts->count() > 0)
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg sm:text-xl font-bold text-slate-900">
                    Koleksi Kerajinan Terkait Lainnya
                </h3>
                <a href="{{ route('catalog') }}" class="text-xs font-bold text-blue-700 hover:underline">
                    Lihat Semua
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach($relatedProducts as $rel)
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm hover:shadow-md transition group">
                    <div class="aspect-square bg-slate-100 rounded-xl flex items-center justify-center overflow-hidden mb-3">
                        <img src="{{ asset($rel->image_path ?: 'images/products/wastafel-marmer-putih.svg') }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <p class="text-[10px] font-semibold text-blue-700">{{ $rel->category->name ?? 'Kerajinan' }}</p>
                    <h4 class="font-bold text-xs text-slate-900 truncate mt-1">
                        <a href="{{ route('catalog.show', $rel->id) }}" class="hover:text-blue-700 transition">
                            {{ $rel->name }}
                        </a>
                    </h4>
                    <p class="text-xs font-black text-slate-900 mt-2">
                        Rp {{ number_format($rel->selling_price, 0, ',', '.') }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

@endsection

@section('scripts')
<script>
    function setMainImage(src) {
        document.getElementById('main-product-img').src = src;
    }

    function switchDetailTab(tabKey) {
        // Reset all tabs
        document.querySelectorAll('.dtab').forEach(t => {
            t.classList.remove('text-blue-900', 'border-b-2', 'border-blue-900');
            t.classList.add('text-slate-500');
        });

        // Hide all contents
        document.querySelectorAll('.dcontent').forEach(c => {
            c.classList.add('hidden');
        });

        // Active tab & content
        const activeTab = document.getElementById('dtab-' + tabKey);
        const activeContent = document.getElementById('dcontent-' + tabKey);

        if (activeTab) {
            activeTab.classList.remove('text-slate-500');
            activeTab.classList.add('text-blue-900', 'border-b-2', 'border-blue-900');
        }

        if (activeContent) {
            activeContent.classList.remove('hidden');
        }
    }
</script>
@endsection
