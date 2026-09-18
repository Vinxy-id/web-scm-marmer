@extends('layouts.public')

@section('title', 'Checkout Pemesanan - ' . $product->name)

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    #map-picker {
        height: 280px;
        width: 100%;
        border-radius: 1rem;
        z-index: 1;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 0.75rem;
        font-family: inherit;
        font-size: 12px;
    }
</style>
@endsection

@section('content')
<!-- Breadcrumb -->
<div class="bg-slate-900 text-white py-6 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
            <span>/</span>
            <a href="{{ route('catalog') }}" class="hover:text-white transition">Katalog</a>
            <span>/</span>
            <a href="{{ route('catalog.show', $product->id) }}" class="hover:text-white transition">{{ $product->name }}</a>
            <span>/</span>
            <span class="text-blue-400 font-semibold">Checkout Pemesanan Online</span>
        </div>
    </div>
</div>

<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Formulir Pemesanan & Checkout E-Commerce
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pemesanan langsung ke pengrajin IKM Marmer & Onyx Tulungagung dengan proteksi packing peti kayu solid dan pelacakan pesanan digital.
            </p>
        </div>

        @if($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-xs">
            <div class="flex items-center gap-2 font-bold mb-1">
                <i data-lucide="alert-circle" class="w-4 h-4"></i> Mohon lengkapi data pemesanan berikut:
            </div>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <!-- MOB-14 SOLVED: Mobile Order Quick Summary Bar (lg:hidden) -->
            <div class="lg:hidden bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between gap-3 mb-6">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-12 h-12 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0 border border-slate-200">
                        <img src="{{ asset($product->image_path ?: 'images/products/wastafel-marmer-putih-b1.webp') }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs font-bold text-slate-900 truncate">{{ $product->name }}</h4>
                        <p class="text-[11px] font-bold text-blue-900">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="text-[10px] text-slate-400 block font-medium">Bahan Alami</span>
                    <span class="text-[11px] font-bold bg-blue-50 text-blue-800 px-2 py-0.5 rounded-lg border border-blue-100">
                        100% Tulungagung
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Shipping & Payment Details (8 cols) -->
                <div class="lg:col-span-7 space-y-6">

                    <!-- Section 1: Receiver Details -->
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                        <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-xs">1</div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Data Penerima & Alamat Pengiriman</h3>
                                <p class="text-[11px] text-slate-400">Pastikan nomor WhatsApp aktif untuk konfirmasi foto real pic corak batu.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Penerima *</label>
                                <input type="text" 
                                       name="receiver_name" 
                                       value="{{ old('receiver_name') }}" 
                                       placeholder="Contoh: Bpk. Hendra Gunawan"
                                       required
                                       class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 p-3 bg-slate-50/50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp / HP Aktif *</label>
                                <input type="text" 
                                       name="receiver_phone" 
                                       value="{{ old('receiver_phone') }}" 
                                       placeholder="081234567890"
                                       required
                                       class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 p-3 bg-slate-50/50">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kota / Kabupaten Tujuan *</label>
                            <input type="text" 
                                   name="shipping_city" 
                                   value="{{ old('shipping_city') }}" 
                                   placeholder="Contoh: Surabaya, Jakarta Selatan, Denpasar, Yogyakarta"
                                   required
                                   class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 p-3 bg-slate-50/50">
                        </div>

                        <!-- Map Pinpoint Selector (GPS & Draggable Pin) -->
                        <div class="p-4 sm:p-5 bg-blue-50/30 rounded-2xl border-2 border-blue-200 space-y-3.5">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-blue-100 pb-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <i data-lucide="map-pin" class="w-4 h-4 text-red-500"></i>
                                        <span>Titik Alamat Pengiriman (Peta & GPS) *</span>
                                    </label>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        Ketik nama alamat / jalan pada kolom pencarian di bawah, klik deteksi GPS, atau geser pin merah tepat di depan rumah Anda.
                                    </p>
                                </div>
                                <button type="button" 
                                        id="btn-detect-gps"
                                        onclick="detectGPSLocation()" 
                                        class="self-start sm:self-auto text-xs font-bold bg-blue-700 hover:bg-blue-600 text-white px-3.5 py-2 rounded-xl transition shadow-xs flex items-center gap-1.5 flex-shrink-0">
                                    <i data-lucide="navigation" class="w-3.5 h-3.5"></i>
                                    <span>Deteksi GPS Saya</span>
                                </button>
                            </div>

                            <!-- Map Search / Address Search Input with Enhanced Visibility -->
                            <div class="space-y-1">
                                <label class="block text-[11px] font-bold text-slate-700">Cari Alamat / Nama Jalan / Kelurahan di Peta:</label>
                                <div class="flex gap-2">
                                    <div class="flex-1">
                                        <input type="text" 
                                               id="map-search-input" 
                                               placeholder="Contoh: Jl. Basuki Rahmat No. 12, Genteng, Surabaya" 
                                               class="w-full text-xs rounded-xl border-2 border-blue-400/80 bg-blue-50/50 text-slate-900 placeholder:text-slate-400 focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-200 px-3.5 py-2.5 font-medium transition shadow-2xs"
                                               onkeydown="if(event.key === 'Enter'){ event.preventDefault(); searchLocation(); }">
                                    </div>
                                    <button type="button" 
                                            onclick="searchLocation()" 
                                            class="px-4 py-2.5 bg-blue-700 hover:bg-blue-600 text-white font-bold text-xs rounded-xl transition flex items-center gap-1.5 shadow-sm flex-shrink-0">
                                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                                        <span>Cari di Peta</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Leaflet Map Container -->
                            <div class="relative rounded-2xl overflow-hidden border-2 border-slate-300 shadow-inner">
                                <div id="map-picker"></div>
                                <div id="map-loading" class="hidden absolute inset-0 bg-slate-900/30 backdrop-blur-xs flex items-center justify-center z-10 text-white text-xs font-bold">
                                    <div class="bg-white text-slate-800 px-4 py-2 rounded-xl shadow-lg flex items-center gap-2">
                                        <i data-lucide="loader-2" class="w-4 h-4 animate-spin text-blue-600"></i>
                                        <span id="map-loading-text">Mencari titik lokasi...</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Selected Address & Coordinates Indicator Card -->
                            <div class="space-y-2 bg-white p-3.5 rounded-xl border border-slate-200 text-xs shadow-xs">
                                <div class="flex items-start gap-2">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5"></i>
                                    <div class="flex-1 min-w-0">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Alamat Pengiriman Terdeteksi dari Peta:</span>
                                        <p id="address-display" class="font-semibold text-slate-800 text-xs leading-snug break-words">
                                            {{ old('shipping_address', 'Belum ada titik dipilih. Silakan klik GPS atau cari alamat pada kolom di atas.') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pt-2 border-t border-slate-100 text-[11px] text-slate-500">
                                    <div class="flex items-center gap-1.5 font-mono">
                                        <span id="coords-display" class="truncate">{{ old('latitude') && old('longitude') ? 'GPS: ' . old('latitude') . ', ' . old('longitude') : 'GPS: Menunggu pemilihan titik' }}</span>
                                    </div>
                                    <a id="btn-open-gmaps" 
                                       href="{{ old('maps_url', '#') }}" 
                                       target="_blank" 
                                       class="{{ old('latitude') && old('longitude') ? '' : 'hidden' }} text-blue-700 hover:text-blue-900 font-bold hover:underline flex items-center gap-1 flex-shrink-0">
                                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Tes Buka di Google Maps
                                    </a>
                                </div>
                            </div>

                            <!-- Hidden Form Inputs for Shipping Address, Latitude, Longitude & Maps URL -->
                            <input type="hidden" name="shipping_address" id="input-shipping-address" value="{{ old('shipping_address') }}">
                            <input type="hidden" name="latitude" id="input-latitude" value="{{ old('latitude') }}">
                            <input type="hidden" name="longitude" id="input-longitude" value="{{ old('longitude') }}">
                            <input type="hidden" name="maps_url" id="input-maps-url" value="{{ old('maps_url') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Kustom Motif / Serat Batu (Opsional)</label>
                            <input type="text" 
                                   name="custom_notes" 
                                   value="{{ old('custom_notes') }}" 
                                   placeholder="Contoh: Minta corak serat marmer dominan abu-abu / onyx transparan tembus"
                                   class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 p-3 bg-slate-50/50">
                        </div>
                    </div>

                    <!-- Section 2: Payment Scheme (DP 50% vs Lunas) -->
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                        <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-xs">2</div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Pilihan Skema Pembayaran</h3>
                                <p class="text-[11px] text-slate-400">Fleksibilitas uang muka untuk produk kerajinan batu alam.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Option 1: DP 50% -->
                            <label class="relative flex flex-col p-4 bg-slate-50 hover:bg-blue-50/40 rounded-2xl border-2 border-slate-200 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 cursor-pointer transition">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-extrabold text-slate-900">DP 50% (Uang Muka)</span>
                                    <input type="radio" name="payment_scheme" value="dp_50" checked class="text-blue-600 focus:ring-blue-500 h-4 w-4" onchange="updateCalculations()">
                                </div>
                                <p class="text-[11px] text-slate-500 mt-2 leading-tight">
                                    Bayar 50% untuk mulai pengerjaan di bengkel. Pelunasan sisa saat barang lulus QC & siap kirim.
                                </p>
                                <span class="mt-3 text-[10px] font-bold text-blue-700 bg-blue-100/60 px-2 py-0.5 rounded-md inline-block w-max">
                                    Populer untuk Custom/PO
                                </span>
                            </label>

                            <!-- Option 2: Full 100% -->
                            <label class="relative flex flex-col p-4 bg-slate-50 hover:bg-blue-50/40 rounded-2xl border-2 border-slate-200 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 cursor-pointer transition">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-extrabold text-slate-900">Lunas 100% (Full Payment)</span>
                                    <input type="radio" name="payment_scheme" value="full_100" class="text-blue-600 focus:ring-blue-500 h-4 w-4" onchange="updateCalculations()">
                                </div>
                                <p class="text-[11px] text-slate-500 mt-2 leading-tight">
                                    Pembayaran penuh sekaligus. Prioritas antrean pembuatan peti kayu & proses kargo kilat.
                                </p>
                                <span class="mt-3 text-[10px] font-bold text-emerald-700 bg-emerald-100/60 px-2 py-0.5 rounded-md inline-block w-max">
                                    Prioritas Kargo Kilat
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Section 3: Payment Process Info -->
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                        <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-xs">3</div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Saluran Pembayaran Digital (Otomatis)</h3>
                                <p class="text-[11px] text-slate-400">Verifikasi real-time tanpa perlu kirim bukti transfer secara manual.</p>
                            </div>
                        </div>

                        <!-- CHK-04 SOLVED: Pre-Order Lead Time Info -->
                        @if(($product->ready_stock ?? 0) <= 0)
                        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-xs text-amber-900 space-y-1">
                            <div class="flex items-center gap-2 font-bold text-amber-800">
                                <i data-lucide="clock" class="w-4 h-4 text-amber-600"></i>
                                <span>Status Pemesanan: Pre-Order Pengrajin</span>
                            </div>
                            <p class="text-[11px] text-amber-700 leading-relaxed">
                                Unit siap kirim saat ini sedang kosong. Pesanan Anda akan dikerjakan langsung oleh pengrajin sentra Campurdarat dengan estimasi pengerjaan <b>3–7 hari kerja</b> setelah pembayaran diverifikasi.
                            </p>
                        </div>
                        @endif

                        <!-- Payment Flow Explanation Container -->
                        <div class="p-5 bg-gradient-to-br from-slate-50 via-blue-50/40 to-indigo-50/40 rounded-2xl border border-blue-200/80 space-y-4">
                            <input type="hidden" name="payment_method" value="midtrans">
                            
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm flex-shrink-0">
                                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs sm:text-sm font-extrabold text-slate-900">Pembayaran Terintegrasi Midtrans</h4>
                                        <p class="text-[11px] text-slate-500">Tersertifikasi resmi Bank Indonesia & enkripsi keamanan tingkat tinggi.</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-emerald-800 bg-emerald-100 border border-emerald-200 px-2.5 py-1 rounded-full whitespace-nowrap">
                                    Bebas Kode Unik
                                </span>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed">
                                Anda tidak perlu memilih rekening bank di sini. Setelah menekan tombol <b>"Konfirmasi & Buat Pesanan"</b> di bawah, jendela pop-up Midtrans akan langsung terbuka di layar Anda untuk memilih:
                            </p>

                            <!-- Channel Badges (Non-interactive info chips) -->
                            <div class="flex flex-wrap gap-2 text-[11px]">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 text-slate-800 rounded-xl font-semibold shadow-2xs">
                                    <i data-lucide="qr-code" class="w-3.5 h-3.5 text-blue-600"></i> QRIS Dinamis (GoPay / OVO / DANA / ShopeePay)
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 text-slate-800 rounded-xl font-semibold shadow-2xs">
                                    <i data-lucide="building-2" class="w-3.5 h-3.5 text-indigo-600"></i> Virtual Account (BCA, Mandiri, BRI, BNI, Permata, CIMB)
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 text-slate-800 rounded-xl font-semibold shadow-2xs">
                                    <i data-lucide="credit-card" class="w-3.5 h-3.5 text-emerald-600"></i> Kartu Kredit / Debit (Visa / Mastercard)
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 text-slate-800 rounded-xl font-semibold shadow-2xs">
                                    <i data-lucide="store" class="w-3.5 h-3.5 text-amber-600"></i> Gerai Indomaret & Alfamart
                                </span>
                            </div>

                            <!-- Guidance alert -->
                            <div class="pt-2 border-t border-blue-100/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-[11px] text-slate-500">
                                <span class="flex items-center gap-1.5">
                                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600"></i> SPK pengerjaan bengkel langsung terbit secara otomatis setelah pembayaran Anda selesai.
                                </span>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Right: Order Summary Sticky Card (4 cols) (MOB-14 SOLVED: Sticky sidebar) -->
                <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-6 lg:self-start">
                    
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center justify-between">
                            <span>Ringkasan Pesanan</span>
                            <span class="text-[11px] font-mono text-slate-400">{{ $product->product_code }}</span>
                        </h3>

                        <!-- Product Mini Card -->
                        <div class="flex gap-4 items-center">
                            <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center flex-shrink-0 border border-slate-200 overflow-hidden">
                                <img src="{{ asset($product->image_path ?: 'images/products/wastafel-marmer-putih-b1.webp') }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-blue-700 uppercase bg-blue-50 px-2 py-0.5 rounded">
                                    {{ $product->category->name ?? 'Kerajinan' }}
                                </span>
                                <h4 class="text-xs font-bold text-slate-900 leading-tight">{{ $product->name }}</h4>
                                <p class="text-[11px] text-slate-500">Pengrajin: {{ $artisan['name'] }}</p>
                                <p class="text-xs font-black text-slate-900">Rp {{ number_format($product->selling_price, 0, ',', '.') }} / unit</p>
                            </div>
                        </div>

                        <!-- Quantity Selector (CHK-05 SOLVED: EDITABLE & CLAMPED) -->
                        <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700">Jumlah Pesanan:</span>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="changeQty(-1)" class="w-8 h-8 rounded-xl bg-white border border-slate-300 font-bold text-slate-700 hover:bg-slate-100 flex items-center justify-center">-</button>
                                <input type="number" id="qty-input" name="quantity" value="1" min="1" max="50" onchange="onQtyChange(this.value)" class="w-14 text-center text-xs font-bold rounded-xl border-slate-300 p-1.5 focus:ring-2 focus:ring-blue-500 outline-none">
                                <button type="button" onclick="changeQty(1)" class="w-8 h-8 rounded-xl bg-white border border-slate-300 font-bold text-slate-700 hover:bg-slate-100 flex items-center justify-center">+</button>
                            </div>
                        </div>

                        <!-- Cost Breakdown -->
                        <div class="space-y-2.5 text-xs text-slate-600 border-t border-slate-100 pt-4">
                            <div class="flex justify-between">
                                <span>Subtotal Produk</span>
                                <span class="font-bold text-slate-900" id="txt-subtotal">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Packing Peti Kayu Solid</span>
                                <span class="font-bold text-emerald-600">GRATIS (Standar Ekspedisi)</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Estimasi Ongkos Kargo</span>
                                <span class="text-slate-500">Dikonfirmasi via WhatsApp</span>
                            </div>
                            <div class="flex justify-between border-t border-slate-100 pt-3 text-sm">
                                <span class="font-bold text-slate-900" id="lbl-tagihan">Total Tagihan (DP 50%):</span>
                                <span class="font-black text-blue-900 text-lg" id="txt-total">Rp {{ number_format($product->selling_price * 0.5, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Protections / Guarantees -->
                        <div class="bg-blue-50/60 p-3.5 rounded-2xl border border-blue-100 space-y-1.5 text-[11px] text-blue-900">
                            <div class="flex items-center gap-1.5 font-bold">
                                <i data-lucide="shield-check" class="w-4 h-4 text-blue-700"></i>
                                <span>Jaminan Transaksi Aman E-SCM</span>
                            </div>
                            <p class="text-blue-800/80 leading-tight">
                                Setiap pesanan langsung terbit nomor resi SPK digital dan otomatis masuk ke antrean pengerjaan bengkel.
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-blue-700 hover:bg-blue-600 text-white font-extrabold text-xs sm:text-sm py-4 px-6 rounded-2xl transition shadow-lg shadow-blue-700/20 flex items-center justify-center gap-2">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                            <span>Konfirmasi & Buat Pesanan</span>
                        </button>

                        <div class="text-center">
                            <a href="{{ route('catalog.show', $product->id) }}" class="text-[11px] font-bold text-slate-400 hover:text-slate-600 transition">
                                &larr; Kembali ke Detail Produk
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </form>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    // --- Product Price & Quantity Calculation ---
    const basePrice = {{ (float) $product->selling_price }};
    let currentQty = 1;

    function changeQty(delta) {
        currentQty = Math.max(1, Math.min(50, currentQty + delta));
        document.getElementById('qty-input').value = currentQty;
        updateCalculations();
    }

    function onQtyChange(val) {
        let parsed = parseInt(val, 10);
        if (isNaN(parsed) || parsed < 1) parsed = 1;
        if (parsed > 50) parsed = 50;
        currentQty = parsed;
        document.getElementById('qty-input').value = currentQty;
        updateCalculations();
    }

    function updateCalculations() {
        const scheme = document.querySelector('input[name="payment_scheme"]:checked')?.value || 'dp_50';
        const subtotal = basePrice * currentQty;
        
        document.getElementById('txt-subtotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');

        if (scheme === 'dp_50') {
            const dp = subtotal * 0.5;
            document.getElementById('lbl-tagihan').innerText = 'Total Tagihan (DP 50%):';
            document.getElementById('txt-total').innerText = 'Rp ' + dp.toLocaleString('id-ID');
        } else {
            document.getElementById('lbl-tagihan').innerText = 'Total Tagihan (Lunas 100%):';
            document.getElementById('txt-total').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        }
    }

    // --- Interactive Map Picker (Leaflet + OpenStreetMap) ---
    let map, marker;
    const defaultLat = {{ old('latitude', -7.2575) }}; // Default Surabaya/East Java or previously submitted
    const defaultLng = {{ old('longitude', 112.7521) }};
    const hasInitialCoord = {{ old('latitude') && old('longitude') ? 'true' : 'false' }};

    document.addEventListener('DOMContentLoaded', function() {
        initMapPicker();
    });

    function initMapPicker() {
        const mapContainer = document.getElementById('map-picker');
        if (!mapContainer) return;

        // Custom red pin icon for marble cargo delivery
        const cargoIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        const zoomLevel = hasInitialCoord ? 16 : 11;
        map = L.map('map-picker').setView([defaultLat, defaultLng], zoomLevel);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        marker = L.marker([defaultLat, defaultLng], {
            draggable: true,
            icon: cargoIcon
        }).addTo(map);

        marker.bindPopup("<b>Titik Pengiriman Kargo</b><br>Geser pin tepat di gerbang / pintu Anda.").openPopup();

        // Marker drag event
        marker.on('dragend', function(e) {
            const pos = marker.getLatLng();
            updateCoordinates(pos.lat, pos.lng, true);
        });

        // Map click event
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoordinates(e.latlng.lat, e.latlng.lng, true);
        });

        if (hasInitialCoord) {
            updateCoordinates(defaultLat, defaultLng, false);
        }

        setTimeout(() => {
            map.invalidateSize();
        }, 400);
    }

    function updateCoordinates(lat, lng, doReverseGeocode = false) {
        const fixedLat = parseFloat(lat).toFixed(7);
        const fixedLng = parseFloat(lng).toFixed(7);
        const gmapsUrl = `https://www.google.com/maps?q=${fixedLat},${fixedLng}`;

        const latInput = document.getElementById('input-latitude');
        const lngInput = document.getElementById('input-longitude');
        const urlInput = document.getElementById('input-maps-url');
        const display = document.getElementById('coords-display');
        const gmapsBtn = document.getElementById('btn-open-gmaps');

        if (latInput) latInput.value = fixedLat;
        if (lngInput) lngInput.value = fixedLng;
        if (urlInput) urlInput.value = gmapsUrl;

        if (display) {
            display.innerHTML = `<b>Koordinat:</b> ${fixedLat}, ${fixedLng}`;
        }

        if (gmapsBtn) {
            gmapsBtn.href = gmapsUrl;
            gmapsBtn.classList.remove('hidden');
        }

        if (doReverseGeocode) {
            reverseGeocode(fixedLat, fixedLng);
        }
    }

    function detectGPSLocation() {
        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung fitur deteksi lokasi GPS.');
            return;
        }

        showMapLoading('Mendeteksi sinyal GPS perangkat Anda...');

        navigator.geolocation.getCurrentPosition(
            function(position) {
                hideMapLoading();
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                if (map && marker) {
                    map.setView([lat, lng], 17);
                    marker.setLatLng([lat, lng]);
                    marker.bindPopup("<b>📍 Lokasi GPS Anda Terdeteksi!</b><br>Geser jika perlu disesuaikan dengan gerbang.").openPopup();
                    updateCoordinates(lat, lng, true);
                }
            },
            function(error) {
                hideMapLoading();
                let msg = 'Gagal mendeteksi lokasi GPS.';
                if (error.code === error.PERMISSION_DENIED) {
                    msg = 'Izin akses lokasi GPS ditolak di browser. Anda dapat mencari kelurahan / jalan pada kolom pencarian atau menggeser pin di peta.';
                } else if (error.code === error.POSITION_UNAVAILABLE) {
                    msg = 'Sinyal lokasi perangkat tidak tersedia.';
                } else if (error.code === error.TIMEOUT) {
                    msg = 'Waktu permintaan deteksi GPS habis. Silakan coba lagi.';
                }
                alert(msg);
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    }

    function searchLocation() {
        const query = document.getElementById('map-search-input').value.trim();
        if (!query) {
            alert('Ketikkan nama jalan, kelurahan, atau kota untuk mencari di peta.');
            return;
        }

        showMapLoading('Mencari "' + query + '"...');

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=1&addressdetails=1`)
            .then(res => res.json())
            .then(data => {
                hideMapLoading();
                if (data && data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lng = parseFloat(data[0].lon);
                    map.setView([lat, lng], 16);
                    marker.setLatLng([lat, lng]);

                    const fullAddress = data[0].display_name;
                    const addrInput = document.getElementById('input-shipping-address');
                    const addrDisplay = document.getElementById('address-display');
                    if (addrInput) addrInput.value = fullAddress;
                    if (addrDisplay) addrDisplay.innerText = fullAddress;

                    const cityField = document.querySelector('input[name="shipping_city"]');
                    if (cityField && data[0].address) {
                        const detectedCity = data[0].address.city || data[0].address.county || data[0].address.state_district || data[0].address.town || data[0].address.municipality || '';
                        if (!cityField.value.trim() && detectedCity) {
                            cityField.value = detectedCity;
                        }
                    }

                    marker.bindPopup(`<b>${data[0].display_name.split(',')[0]}</b><br>Geser pin jika ingin menentukan titik gerbang.`).openPopup();
                    updateCoordinates(lat, lng, false);
                } else {
                    alert('Lokasi "' + query + '" tidak ditemukan. Coba ketikkan nama kecamatan atau kota yang lebih umum.');
                }
            })
            .catch(err => {
                hideMapLoading();
                console.error(err);
                alert('Gagal menghubungi layanan pencarian peta. Anda tetap bisa menggeser pin merah di peta.');
            });
    }

    function reverseGeocode(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
            .then(res => res.json())
            .then(data => {
                if (data && data.display_name) {
                    const fullAddress = data.display_name;
                    const addrInput = document.getElementById('input-shipping-address');
                    const addrDisplay = document.getElementById('address-display');
                    if (addrInput) addrInput.value = fullAddress;
                    if (addrDisplay) addrDisplay.innerText = fullAddress;

                    if (data.address) {
                        const cityField = document.querySelector('input[name="shipping_city"]');
                        const detectedCity = data.address.city || data.address.county || data.address.state_district || data.address.town || data.address.municipality || '';
                        if (cityField && !cityField.value.trim() && detectedCity) {
                            cityField.value = detectedCity;
                        }
                    }
                    
                    if (marker) {
                        const shortName = data.display_name.split(',').slice(0, 3).join(',');
                        marker.bindPopup(`<b>Titik Terpilih:</b><br>${shortName}`).openPopup();
                    }
                }
            })
            .catch(err => console.log('Reverse geocoding silent fallback:', err));
    }

    function showMapLoading(text) {
        const el = document.getElementById('map-loading');
        const txt = document.getElementById('map-loading-text');
        if (txt) txt.innerText = text;
        if (el) el.classList.remove('hidden');
    }

    function hideMapLoading() {
        const el = document.getElementById('map-loading');
        if (el) el.classList.add('hidden');
    }
</script>
@endsection
