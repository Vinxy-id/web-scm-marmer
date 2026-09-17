@extends('layouts.app')

@section('title', 'Dashboard Monitoring Rantai Pasok')
@section('page-title', 'Dashboard Monitoring Rantai Pasok')
@section('page-subtitle', 'Klaster IKM Marmer Kabupaten Tulungagung')

@section('topbar-actions')
    @php $role = auth()->user()->role ?? 'owner'; @endphp
    <div class="flex items-center gap-1.5 sm:gap-2">
        <!-- Interactive Dashboard Tour Button -->
        <button type="button" onclick="startDashboardTour()" id="btn-dashboard-tour" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white text-xs font-bold px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg flex items-center gap-1.5 shadow-sm transition transform hover:scale-105" title="Mulai Panduan & Tur Fitur Dashboard">
            <i data-lucide="sparkles" class="w-3.5 h-3.5 text-yellow-200"></i>
            <span class="hidden md:inline">💡 Panduan Fitur</span>
            <span class="md:hidden">💡 Tur</span>
        </button>

        <a href="{{ route('catalog') }}" target="_blank" class="hidden sm:flex bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-3 py-2 rounded-lg items-center gap-1.5 border border-slate-300 shadow-sm transition">
            <i data-lucide="external-link" class="w-3.5 h-3.5 text-slate-500"></i>
            <span>Lihat Web Publik</span>
        </a>

        @if(in_array($role, ['owner', 'admin']))
            @if(isset($pendingOrdersCount) && $pendingOrdersCount > 0)
                <a href="{{ route('orders.index') }}" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg flex items-center gap-1 shadow-sm transition" title="Pesanan Masuk">
                    <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>
                    <span class="hidden md:inline">Pesanan</span>
                    <span class="bg-white text-amber-800 text-[10px] font-bold px-1.5 py-0.2 rounded-full">{{ $pendingOrdersCount }}</span>
                </a>
            @endif
            <a href="{{ route('production.kanban') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg flex items-center gap-1 shadow-sm transition" title="Buat SPK Baru">
                <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                <span class="hidden md:inline">Buat SPK Baru</span>
            </a>
        @elseif($role === 'gudang')
            <a href="{{ route('materials.index') }}" class="group/tip relative bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg flex items-center gap-1 shadow-sm transition" title="Catat Mutasi Stok">
                <i data-lucide="arrow-down-up" class="w-3.5 h-3.5"></i>
                <span class="hidden md:inline">Mutasi Stok</span>
                <span class="pointer-events-none absolute top-full mt-2 left-1/2 -translate-x-1/2 hidden group-hover/tip:block w-56 bg-slate-900 text-white text-[11px] font-normal p-2 rounded-lg shadow-xl z-50 border border-slate-700 text-center">
                    Mencatat pergerakan fisik batu marmer (Bahan Masuk Tambang, Pengeluaran ke Mesin Slep, atau Koreksi Balok Retak).
                </span>
            </a>
        @elseif($role === 'produksi')
            <a href="{{ route('production.kanban') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg flex items-center gap-1 shadow-sm transition" title="Buat SPK Baru">
                <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                <span class="hidden md:inline">Buat SPK Baru</span>
            </a>
        @elseif($role === 'qc')
            <a href="{{ route('qc.index') }}" class="group/tip relative bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg flex items-center gap-1 shadow-sm transition" title="Form Inspeksi QC">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                <span class="hidden md:inline">Inspeksi QC</span>
                <span class="pointer-events-none absolute top-full mt-2 left-1/2 -translate-x-1/2 hidden group-hover/tip:block w-64 bg-slate-900 text-white text-[11px] font-normal p-2 rounded-lg shadow-xl z-50 border border-slate-700 text-center">
                    Input hasil uji Tahap 1 (geometri & serat mentah) atau Tahap 2 (kilap Hi-Glossy, uji kebocoran afur wastafel, dan kelayakan peti kayu).
                </span>
            </a>
        @elseif($role === 'distribusi')
            <a href="{{ route('distribution.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg flex items-center gap-1 shadow-sm transition" title="Buat Surat Jalan">
                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                <span class="hidden md:inline">Surat Jalan</span>
            </a>
        @endif
    </div>
@endsection

@section('content')
<div class="space-y-6">

    <!-- ROLE WORKFLOW & QUICK SHORTCUTS (TOUR STEP 1) -->
    <div id="tour-step-1" class="bg-gradient-to-r from-slate-900 to-blue-950 p-4 rounded-xl text-white shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4 border border-slate-800">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="bg-blue-500/20 text-blue-300 border border-blue-400/30 text-[10px] uppercase font-bold px-2 py-0.5 rounded-full">
                    Akses Peran: {{ ucfirst(auth()->user()->role ?? 'owner') }}
                </span>
                <span class="text-xs text-slate-300 font-medium">{{ auth()->user()->ikm_name ?? 'UD Cahaya Onix' }}</span>
            </div>
            <h2 class="text-sm sm:text-base font-bold text-white">
                @if(($role ?? 'owner') === 'owner' || ($role ?? 'owner') === 'admin')
                    Pusat Kendali Eksekutif Rantai Pasok IKM Marmer
                @elseif(($role ?? '') === 'gudang')
                    Pusat Operasional Logistik & Bahan Baku Tambang
                @elseif(($role ?? '') === 'produksi')
                    Pusat Monitoring Lantai Kerja Bubut & Stasiun Mesin
                @elseif(($role ?? '') === 'qc')
                    Pusat Pengujian Kualitas 2-Tahap & Hilirisasi Residu
                @elseif(($role ?? '') === 'distribusi')
                    Pusat Pengiriman & Verifikasi Packing Krat Kayu
                @endif
            </h2>
            <p class="text-xs text-slate-300">
                @if(($role ?? 'owner') === 'owner' || ($role ?? 'owner') === 'admin')
                    Pantau nilai aset, efisiensi siklus proses (PCE 64,58%), dan peramalan kebutuhan bahan baku.
                @elseif(($role ?? '') === 'gudang')
                    Catat penerimaan balok marmer/batu kali dan keluarkan bahan ke mesin slep.
                @elseif(($role ?? '') === 'produksi')
                    Pantau progres SPK dari stasiun Slep → Bubut 1-4 → Poles.
                @elseif(($role ?? '') === 'qc')
                    Inspeksi tahap 1 (bentuk mentah) dan tahap 2 (kehalusan poles & lubang afur).
                @elseif(($role ?? '') === 'distribusi')
                    Cek produk ready-stock dan terbitkan surat jalan ekspedisi pesanan pelanggan.
                @endif
            </p>
        </div>

        <!-- Role Quick Action Buttons with Contextual Micro-Tooltips -->
        <div class="flex flex-wrap items-center gap-2">
            @if(in_array($role ?? '', ['owner', 'admin']))
                <a href="{{ route('orders.index') }}" class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 border border-slate-700 transition">
                    <i data-lucide="shopping-bag" class="w-3.5 h-3.5 text-amber-400"></i> Kelola Pesanan
                </a>
                <a href="{{ route('supply-chain-flow') }}" class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 border border-slate-700 transition">
                    <i data-lucide="git-merge" class="w-3.5 h-3.5 text-emerald-400"></i> Alur SCM
                </a>
                <a href="{{ route('reports') }}" class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 border border-slate-700 transition">
                    <i data-lucide="bar-chart-2" class="w-3.5 h-3.5 text-cyan-400"></i> Laporan PCE
                </a>
                <div class="group/tip relative inline-block">
                    <a href="{{ route('forecasting.index') }}" class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 shadow-sm transition">
                        <i data-lucide="trending-up" class="w-3.5 h-3.5 text-yellow-300"></i> AI Forecast
                    </a>
                    <span class="pointer-events-none absolute bottom-full mb-2 right-0 hidden group-hover/tip:block w-72 bg-slate-900/95 text-white text-[11px] font-normal leading-relaxed p-2.5 rounded-lg shadow-xl z-50 border border-slate-700 backdrop-blur-sm">
                        Menjalankan model time-series ARIMA(2,0,2) berbasis histori 24 bulan untuk memproyeksikan kebutuhan bahan baku balok marmer 3 bulan mendatang.
                    </span>
                </div>
            @elseif(($role ?? '') === 'gudang')
                <div class="group/tip relative inline-block">
                    <a href="{{ route('materials.index') }}" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 shadow-sm transition">
                        <i data-lucide="boxes" class="w-3.5 h-3.5"></i> Kelola Stok Bahan
                    </a>
                    <span class="pointer-events-none absolute bottom-full mb-2 right-0 hidden group-hover/tip:block w-72 bg-slate-900/95 text-white text-[11px] font-normal leading-relaxed p-2.5 rounded-lg shadow-xl z-50 border border-slate-700 backdrop-blur-sm">
                        Mencatat pergerakan fisik batu marmer (Bahan Masuk Tambang, Pengeluaran ke Mesin Slep, atau Koreksi Balok Retak/Rusak).
                    </span>
                </div>
            @elseif(($role ?? '') === 'produksi')
                <a href="{{ route('production.kanban') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 shadow-sm transition">
                    <i data-lucide="kanban-square" class="w-3.5 h-3.5"></i> Buka Kanban SPK
                </a>
                <a href="{{ route('production.wip') }}" class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 border border-slate-700 transition">
                    <i data-lucide="activity" class="w-3.5 h-3.5 text-cyan-400"></i> WIP Mesin
                </a>
            @elseif(($role ?? '') === 'qc')
                <div class="group/tip relative inline-block">
                    <a href="{{ route('qc.index') }}" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 shadow-sm transition">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> Form QC 2-Tahap
                    </a>
                    <span class="pointer-events-none absolute bottom-full mb-2 right-0 hidden group-hover/tip:block w-72 bg-slate-900/95 text-white text-[11px] font-normal leading-relaxed p-2.5 rounded-lg shadow-xl z-50 border border-slate-700 backdrop-blur-sm">
                        Input hasil inspeksi Tahap 1 (geometri & serat mentah) atau Tahap 2 (kilap Hi-Glossy, uji kebocoran afur wastafel, dan kelayakan peti kayu).
                    </span>
                </div>
                <div class="group/tip relative inline-block">
                    <a href="{{ route('waste.index') }}" class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 border border-slate-700 transition">
                        <i data-lucide="recycle" class="w-3.5 h-3.5 text-teal-400"></i> Hilirisasi Residu
                    </a>
                    <span class="pointer-events-none absolute bottom-full mb-2 right-0 hidden group-hover/tip:block w-72 bg-slate-900/95 text-white text-[11px] font-normal leading-relaxed p-2.5 rounded-lg shadow-xl z-50 border border-slate-700 backdrop-blur-sm">
                        Mencatat volume limbah bongkahan dan serpihan bubut untuk dialihkan menjadi produk bernilai tambah (teraso, ubin motif, kalsium).
                    </span>
                </div>
            @elseif(($role ?? '') === 'distribusi')
                <a href="{{ route('distribution.index') }}" class="bg-purple-600 hover:bg-purple-500 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 shadow-sm transition">
                    <i data-lucide="truck" class="w-3.5 h-3.5"></i> Surat Jalan & Resi
                </a>
            @endif
        </div>
    </div>

    <!-- ALERT BANNERS: 2-GATE VALIDATION & CRITICAL STOCK (TOUR STEP 2) -->
    <div id="tour-step-2" class="space-y-4">
        @if(isset($pendingOrdersCount) && $pendingOrdersCount > 0)
        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-amber-100 text-amber-700 rounded-full">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-amber-900">Pesanan E-Commerce Masuk! ({{ $pendingOrdersCount }} Perlu Verifikasi)</h3>
                    <p class="text-xs text-amber-800">Terdapat pesanan dari pembeli online yang telah membayar DP/Lunas dan siap diterbitkan SPK ke lantai produksi.</p>
                </div>
            </div>
            <div class="group/tip relative inline-block">
                <a href="{{ route('orders.index') }}" class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold px-3.5 py-1.5 rounded-md transition flex items-center gap-1.5 shadow-sm">
                    <i data-lucide="clipboard-check" class="w-4 h-4"></i> Verifikasi & Buat SPK
                </a>
                <span class="pointer-events-none absolute bottom-full mb-2 right-0 hidden group-hover/tip:block w-72 bg-slate-900/95 text-white text-[11px] font-normal leading-relaxed p-2.5 rounded-lg shadow-xl z-50 border border-slate-700 backdrop-blur-sm">
                    Memvalidasi bukti pembayaran DP 50% atau Lunas dari pembeli online, mengunci pesanan, dan otomatis menerbitkan kartu SPK ke antrean pemotongan bengkel.
                </span>
            </div>
        </div>
        @endif

    <!-- ALERT BANNER 2: CRITICAL STOCK -->
    @if($criticalMaterials->isNotEmpty())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-red-100 text-red-600 rounded-full">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-red-900">Peringatan Stok Kritis Terdeteksi!</h3>
                <p class="text-xs text-red-700">Terdapat <b>{{ $criticalMaterials->count() }} material</b> yang berada di bawah ambang batas minimum stok. Segera lakukan pengadaan.</p>
            </div>
        </div>
        <a href="{{ route('materials.index') }}" class="bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-3 py-1.5 rounded-md transition flex items-center gap-1">
            <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i> Periksa Stok
        </a>
    </div>
    @endif
    </div>

    <!-- 5 CLICKABLE KPI CARDS (TOUR STEP 3) -->
    <div id="tour-step-3" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- KPI 1: Bahan Mentah -->
        <a href="{{ route('materials.index') }}" class="group bg-white p-5 rounded-xl border border-slate-200 hover:border-amber-400 hover:shadow-md transition flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-600 uppercase tracking-wider group-hover:text-amber-700 transition">Bahan Mentah</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalRawMaterials, 0) }} Blok</p>
                <p class="text-[11px] text-emerald-700 font-semibold mt-1 flex items-center gap-1">
                    <i data-lucide="trending-up" class="w-3 h-3"></i> Gudang Batu
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 group-hover:bg-amber-100 text-amber-600 flex items-center justify-center transition">
                <i data-lucide="mountain" class="w-6 h-6"></i>
            </div>
        </a>

        <!-- KPI 2: SPK Produksi Aktif -->
        <a href="{{ route('production.kanban') }}" class="group bg-white p-5 rounded-xl border border-slate-200 hover:border-indigo-400 hover:shadow-md transition flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-600 uppercase tracking-wider group-hover:text-indigo-700 transition">SPK Produksi</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ $activeWorkOrders }} Batch</p>
                <p class="text-[11px] text-blue-700 font-semibold mt-1">7 Mesin Aktif</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 group-hover:bg-indigo-100 text-indigo-600 flex items-center justify-center transition">
                <i data-lucide="cog" class="w-6 h-6"></i>
            </div>
        </a>

        <!-- KPI 3: Barang Jadi Siap Kirim -->
        <a href="{{ route('products.index') }}" class="group bg-white p-5 rounded-xl border border-slate-200 hover:border-emerald-400 hover:shadow-md transition flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-600 uppercase tracking-wider group-hover:text-emerald-700 transition">Barang Jadi</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalReadyGoods }} Unit</p>
                <p class="text-[11px] text-emerald-700 font-semibold mt-1">Lolos QC Tahap 2</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 group-hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition">
                <i data-lucide="package-check" class="w-6 h-6"></i>
            </div>
        </a>

        <!-- KPI 4: Pesanan E-Commerce (BARU) -->
        <a href="{{ route('orders.index') }}" class="group bg-white p-5 rounded-xl border border-slate-200 hover:border-blue-400 hover:shadow-md transition flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-600 uppercase tracking-wider group-hover:text-blue-700 transition">E-Commerce</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalOrdersCount ?? 0 }} Order</p>
                <p class="text-[11px] {{ ($pendingOrdersCount ?? 0) > 0 ? 'text-amber-700 font-bold' : 'text-slate-600' }} mt-1">
                    {{ $pendingOrdersCount ?? 0 }} Perlu Verifikasi
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 group-hover:bg-blue-100 text-blue-600 flex items-center justify-center transition">
                <i data-lucide="shopping-cart" class="w-6 h-6"></i>
            </div>
        </a>

        <!-- KPI 5: Total Nilai Inventori -->
        <a href="{{ route('reports') }}" class="group bg-white p-5 rounded-xl border border-slate-200 hover:border-purple-400 hover:shadow-md transition flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-600 uppercase tracking-wider group-hover:text-purple-700 transition">Nilai Inventori</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($totalInventoryValue / 1000000, 1) }} Jt</p>
                <p class="text-[11px] text-slate-600 mt-1">Bahan + Produk Jadi</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 group-hover:bg-purple-100 text-purple-600 flex items-center justify-center transition">
                <i data-lucide="coins" class="w-6 h-6"></i>
            </div>
        </a>
    </div>

    <!-- INTERACTIVE SUPPLY CHAIN FLOW (TOUR STEP 4) -->
    <div id="tour-step-4" class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i data-lucide="git-merge" class="w-5 h-5 text-blue-600"></i> Diagram Alur Rantai Pasok Marmer Terintegrasi
                </h2>
                <p class="text-xs text-slate-600">Visualisasi aliran dari tambang hingga pelanggan. Klik kotak tahap untuk langsung membuka modulnya.</p>
            </div>
            <span class="text-xs bg-slate-100 text-slate-700 px-3 py-1 rounded-md font-semibold border">8 Tahap Hulu-Hilir</span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-2.5 pt-2">
            <!-- Stage 1 -->
            <a href="{{ route('materials.index') }}" class="group p-3 bg-slate-50 hover:bg-blue-50 border hover:border-blue-300 rounded-lg text-center transition block">
                <div class="w-8 h-8 mx-auto mb-1.5 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                    <i data-lucide="pickaxe" class="w-4 h-4"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-blue-600 block">1. Tambang</span>
                <p class="text-[10px] text-slate-600 mt-0.5">3 Pemasok</p>
                <span class="inline-block mt-1.5 text-[9px] bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded font-semibold">Campurdarat</span>
            </a>

            <!-- Stage 2 -->
            <a href="{{ route('materials.index') }}" class="group p-3 bg-slate-50 hover:bg-blue-50 border hover:border-blue-300 rounded-lg text-center transition block">
                <div class="w-8 h-8 mx-auto mb-1.5 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center">
                    <i data-lucide="boxes" class="w-4 h-4"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-blue-600 block">2. Gudang Batu</span>
                <p class="text-[10px] text-slate-600 mt-0.5">{{ number_format($totalRawMaterials, 0) }} Blok</p>
                <span class="inline-flex items-center gap-1 mt-1.5 text-[9px] bg-red-100 text-red-700 px-1.5 py-0.5 rounded font-semibold">
                    <span class="w-1 h-1 rounded-full bg-red-500"></span> 1 Kritis
                </span>
            </a>

            <!-- Stage 3 -->
            <a href="{{ route('production.kanban') }}" class="group p-3 bg-slate-50 hover:bg-blue-50 border hover:border-blue-300 rounded-lg text-center transition block">
                <div class="w-8 h-8 mx-auto mb-1.5 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center">
                    <i data-lucide="scissors" class="w-4 h-4"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-blue-600 block">3. Mesin Slep</span>
                <p class="text-[10px] text-slate-600 mt-0.5">Potong Blok</p>
                <span class="inline-block mt-1.5 text-[9px] bg-slate-200 text-slate-700 px-1.5 py-0.5 rounded font-semibold">Max 80 cm</span>
            </a>

            <!-- Stage 4 -->
            <a href="{{ route('production.wip') }}" class="group p-3 bg-slate-50 hover:bg-blue-50 border hover:border-blue-300 rounded-lg text-center transition block">
                <div class="w-8 h-8 mx-auto mb-1.5 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                    <i data-lucide="disc" class="w-4 h-4"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-blue-600 block">4. Pembubutan</span>
                <p class="text-[10px] text-slate-600 mt-0.5">7 Mesin Aktif</p>
                <span class="inline-block mt-1.5 text-[9px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded font-semibold">14 Unit/Hari</span>
            </a>

            <!-- Stage 5 -->
            <a href="{{ route('qc.index') }}" class="group p-3 bg-slate-50 hover:bg-blue-50 border hover:border-blue-300 rounded-lg text-center transition block">
                <div class="w-8 h-8 mx-auto mb-1.5 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center">
                    <i data-lucide="scan-search" class="w-4 h-4"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-blue-600 block">5. QC Tahap 1</span>
                <p class="text-[10px] text-slate-600 mt-0.5">Cek Serat Awal</p>
                <span class="inline-block mt-1.5 text-[9px] bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded font-semibold">Bebas Retak</span>
            </a>

            <!-- Stage 6: Fixed route to production.wip (Poles is a WIP sub-station) -->
            <a href="{{ route('production.wip') }}" class="group p-3 bg-slate-50 hover:bg-blue-50 border hover:border-blue-300 rounded-lg text-center transition block">
                <div class="w-8 h-8 mx-auto mb-1.5 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-blue-600 block">6. Finishing Poles</span>
                <p class="text-[10px] text-slate-600 mt-0.5">Hi-Glossy</p>
                <span class="inline-block mt-1.5 text-[9px] bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded font-semibold">Kilau Super</span>
            </a>

            <!-- Stage 7 -->
            <a href="{{ route('qc.index') }}" class="group p-3 bg-slate-50 hover:bg-blue-50 border hover:border-blue-300 rounded-lg text-center transition block">
                <div class="w-8 h-8 mx-auto mb-1.5 rounded-lg bg-green-100 text-green-700 flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-blue-600 block">7. QC Tahap 2</span>
                <p class="text-[10px] text-slate-600 mt-0.5">Uji Afur Air</p>
                <span class="inline-block mt-1.5 text-[9px] bg-green-100 text-green-800 px-1.5 py-0.5 rounded font-semibold">Siap Gudang</span>
            </a>

            <!-- Stage 8 -->
            <a href="{{ route('distribution.index') }}" class="group p-3 bg-slate-50 hover:bg-blue-50 border hover:border-blue-300 rounded-lg text-center transition block">
                <div class="w-8 h-8 mx-auto mb-1.5 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center">
                    <i data-lucide="truck" class="w-4 h-4"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-blue-600 block">8. Distribusi</span>
                <p class="text-[10px] text-slate-600 mt-0.5">Packing Kayu</p>
                <span class="inline-block mt-1.5 text-[9px] bg-purple-100 text-purple-800 px-1.5 py-0.5 rounded font-semibold">Kirim Ekspedisi</span>
            </a>
        </div>
    </div>

    <!-- CHARTS & SUMMARY SECTION (TOUR STEP 5) -->
    <div id="tour-step-5" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart 1: Tren Pengadaan vs Output -->
        <div class="lg:col-span-2 bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Tren Pengadaan Bahan vs Output Produksi (6 Bulan)</h2>
                    <p class="text-xs text-slate-600">Perbandingan volume material masuk (blok) vs barang jadi selesai (unit)</p>
                </div>
                <a href="{{ route('reports') }}" class="text-xs text-blue-600 hover:underline font-semibold flex items-center gap-1">
                    Detail PCE <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
            <div class="h-64">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Komposisi Bahan & Stok -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-800 mb-1">Komposisi Inventori Batuan Alam</h2>
                <p class="text-xs text-slate-600 mb-3">Distribusi volume bahan baku di gudang</p>
                <div class="h-44 flex items-center justify-center">
                    <canvas id="compositionChart"></canvas>
                </div>
            </div>
            <div class="pt-3 border-t border-slate-100 text-xs space-y-1.5">
                <div class="flex justify-between text-slate-600"><span>Marmer Alam</span><b>{{ $materialBreakdown['marmer'] ?? 42 }} Balok</b></div>
                <div class="flex justify-between text-slate-600"><span>Batu Kali</span><b>{{ $materialBreakdown['batu_kali'] ?? 65 }} Biji</b></div>
                <div class="flex justify-between text-slate-600"><span>Onyx Kristal</span><b>{{ $materialBreakdown['onix'] ?? 8 }} Bongkahan</b></div>
            </div>
        </div>
    </div>

    <!-- 5 SPK PRODUKSI TERKINI (GAP-09 SOLVED: RENDERING $recentWorkOrders) -->
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i data-lucide="clipboard-list" class="w-4 h-4 text-indigo-600"></i> SPK Produksi Terkini (Lantai Bengkel)
                </h2>
                <p class="text-xs text-slate-600">Daftar batch pengerjaan surat perintah kerja yang sedang aktif atau baru dibuat.</p>
            </div>
            <a href="{{ route('production.kanban') }}" class="text-xs bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-semibold px-3 py-1.5 rounded-lg border border-indigo-200 transition flex items-center gap-1">
                Buka Papan Kanban <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b">
                    <tr>
                        <th class="p-2.5 whitespace-nowrap">No. SPK</th>
                        <th class="p-2.5 whitespace-nowrap min-w-[160px]">Produk yang Dikerjakan</th>
                        <th class="p-2.5 whitespace-nowrap">Pelanggan / Tujuan</th>
                        <th class="p-2.5 whitespace-nowrap">Target & Selesai</th>
                        <th class="p-2.5 whitespace-nowrap">Status Pengerjaan</th>
                        <th class="p-2.5 whitespace-nowrap text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentWorkOrders as $wo)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-2.5 whitespace-nowrap">
                            <span class="inline-block whitespace-nowrap font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">{{ $wo->spk_number }}</span>
                        </td>
                        <td class="p-2.5 font-medium text-slate-800">
                            {{ $wo->product->name ?? 'Produk Custom' }}
                            <span class="block text-[10px] text-slate-600 font-normal">{{ $wo->product->product_code ?? '-' }}</span>
                        </td>
                        <td class="p-2.5 text-slate-700 whitespace-nowrap">{{ $wo->customer->name ?? 'Stok Buffer IKM' }}</td>
                        <td class="p-2.5 whitespace-nowrap text-slate-800 font-semibold">
                            {{ $wo->completed_quantity }} / {{ $wo->target_quantity }} Unit
                        </td>
                        <td class="p-2.5 whitespace-nowrap">
                            @php
                                $badgeColor = match($wo->status) {
                                    'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'scheduled' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'in_progress' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'qc_phase' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    default => 'bg-slate-100 text-slate-700 border-slate-200'
                                };
                                $statusLabel = match($wo->status) {
                                    'draft' => 'Draf Baru',
                                    'scheduled' => 'Terjadwal Slep',
                                    'in_progress' => 'Proses Bubut & Poles',
                                    'qc_phase' => 'Uji QC 2-Tahap',
                                    'completed' => 'Selesai (Gudang)',
                                    default => ucfirst($wo->status)
                                };
                            @endphp
                            <span class="inline-block whitespace-nowrap px-2.5 py-0.5 text-[10px] font-bold rounded border {{ $badgeColor }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="p-2.5 whitespace-nowrap text-right">
                            <a href="{{ route('production.wip') }}" class="text-blue-700 hover:text-blue-900 font-semibold text-xs inline-flex items-center gap-0.5">
                                Pantau WIP <i data-lucide="chevron-right" class="w-3 h-3"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-slate-500">Belum ada SPK produksi yang aktif.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TABEL MATERIAL KRITIS & STATUS MESIN (GAP-05 SOLVED: INTERACTIVE MACHINE WIDGET) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Tabel Stok Kritis -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-red-500"></i> Daftar Material Perlu Pengadaan Segera
                </h2>
                <a href="{{ route('materials.index') }}" class="text-xs text-blue-700 hover:underline font-medium">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b">
                        <tr>
                            <th class="p-2.5">Material</th>
                            <th class="p-2.5">Sisa Stok</th>
                            <th class="p-2.5">Batas Min</th>
                            <th class="p-2.5">Status</th>
                            <th class="p-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($criticalMaterials as $mat)
                        <tr>
                            <td class="p-2.5 font-medium text-slate-800">{{ $mat->name }}</td>
                            <td class="p-2.5 text-red-700 font-bold">{{ $mat->current_stock }} {{ $mat->unit }}</td>
                            <td class="p-2.5 text-slate-600">{{ $mat->minimum_stock }} {{ $mat->unit }}</td>
                            <td class="p-2.5">
                                <span class="inline-flex items-center gap-1 bg-red-100 text-red-800 px-2 py-0.5 rounded font-semibold text-[10px]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Kritis
                                </span>
                            </td>
                            <td class="p-2.5 text-right">
                                <a href="{{ route('materials.index') }}" class="text-blue-700 hover:text-blue-900 font-semibold text-xs">Kelola</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-3 text-center text-slate-500">Semua stok bahan baku dalam kondisi aman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Status 7 Mesin Bubut UD Cahaya Onix (Clickable to WIP) -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i data-lucide="gauge" class="w-4 h-4 text-indigo-500"></i> Status 7 Stasiun Mesin (UD Cahaya Onix)
                </h2>
                <a href="{{ route('production.wip') }}" class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-2.5 py-1 rounded font-semibold border border-indigo-200 transition flex items-center gap-1">
                    Buka Detail WIP <i data-lucide="chevron-right" class="w-3 h-3"></i>
                </a>
            </div>
            
            <!-- MOB-08 SOLVED: Balanced responsive grid for 7 machine stations -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-2.5 text-xs">
                <a href="{{ route('production.wip') }}" class="p-2.5 bg-slate-50 hover:bg-indigo-50 border hover:border-indigo-300 rounded-lg text-center transition block">
                    <p class="font-bold text-slate-800">Mesin Bubut 1</p>
                    <p class="text-[10px] text-blue-600 mt-0.5 truncate">Wastafel D40</p>
                    <span class="text-[10px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-semibold mt-1 inline-block">Running (85m)</span>
                </a>
                <a href="{{ route('production.wip') }}" class="p-2.5 bg-slate-50 hover:bg-indigo-50 border hover:border-indigo-300 rounded-lg text-center transition block">
                    <p class="font-bold text-slate-800">Mesin Bubut 2</p>
                    <p class="text-[10px] text-blue-600 mt-0.5 truncate">Wastafel B1</p>
                    <span class="text-[10px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-semibold mt-1 inline-block">Running (80m)</span>
                </a>
                <a href="{{ route('production.wip') }}" class="p-2.5 bg-slate-50 hover:bg-indigo-50 border hover:border-indigo-300 rounded-lg text-center transition block">
                    <p class="font-bold text-slate-800">Mesin Bubut 3</p>
                    <p class="text-[10px] text-amber-600 mt-0.5 truncate">Onix Oval</p>
                    <span class="text-[10px] bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-semibold mt-1 inline-block">Poles (90m)</span>
                </a>
                <a href="{{ route('production.wip') }}" class="p-2.5 bg-slate-50 hover:bg-indigo-50 border hover:border-indigo-300 rounded-lg text-center transition block">
                    <p class="font-bold text-slate-800">Mesin Bubut 4</p>
                    <p class="text-[10px] text-blue-600 mt-0.5 truncate">Mangkok</p>
                    <span class="text-[10px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-semibold mt-1 inline-block">Running (75m)</span>
                </a>
                <a href="{{ route('production.wip') }}" class="p-2.5 bg-slate-50 hover:bg-indigo-50 border hover:border-indigo-300 rounded-lg text-center transition block">
                    <p class="font-bold text-slate-800">Mesin Bubut 5</p>
                    <p class="text-[10px] text-blue-600 mt-0.5 truncate">Marmer Bakar</p>
                    <span class="text-[10px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-semibold mt-1 inline-block">Running (85m)</span>
                </a>
                <a href="{{ route('production.wip') }}" class="p-2.5 bg-slate-50 hover:bg-indigo-50 border hover:border-indigo-300 rounded-lg text-center transition block">
                    <p class="font-bold text-slate-800">Mesin Bubut 6</p>
                    <p class="text-[10px] text-blue-600 mt-0.5 truncate">Pedestal</p>
                    <span class="text-[10px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-semibold mt-1 inline-block">Running (90m)</span>
                </a>
                <a href="{{ route('production.wip') }}" class="p-2.5 bg-slate-50 hover:bg-indigo-50 border hover:border-indigo-300 rounded-lg text-center col-span-2 sm:col-span-3 md:col-span-2 lg:col-span-1 transition block">
                    <p class="font-bold text-slate-800">Mesin Slep 7</p>
                    <p class="text-[10px] text-slate-600 mt-0.5 truncate">Potong Sawmill</p>
                    <span class="text-[10px] bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded font-semibold mt-1 inline-block">Potong (60m)</span>
                </a>
            </div>
        </div>
    </div>

    <!-- TINGKAT 3: HELPDESK & PANDUAN PENGGUNA MINI BANNER -->
    <div id="tour-helpdesk-banner" class="bg-gradient-to-r from-blue-900 via-slate-900 to-indigo-950 p-4 sm:p-5 rounded-xl text-white shadow-sm border border-blue-800/40 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-blue-500/20 border border-blue-400/30 text-blue-300 flex items-center justify-center flex-shrink-0">
                <i data-lucide="book-open" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-white flex items-center gap-2">
                    Bantuan Operasional & Buku Pedoman Pengguna E-SCM
                    <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[9px] px-2 py-0.5 rounded-full font-semibold">Resmi Klaster IKM</span>
                </h4>
                <p class="text-xs text-slate-300 mt-0.5">Butuh panduan lengkap atau mengalami kendala teknis di lantai bengkel bubut? Buka tur interaktif atau pelajari manual SOP sistem.</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 flex-shrink-0">
            <button type="button" onclick="startDashboardTour()" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs px-3.5 py-2 rounded-lg transition flex items-center gap-1.5 shadow-sm">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Mulai Tur Fitur
            </button>
            <a href="https://wa.me/6281234567890?text=Halo%20Admin%20SCM%20Marmer,%20saya%20butuh%20bantuan%20operasional" target="_blank" class="bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium text-xs px-3 py-2 rounded-lg transition flex items-center gap-1.5 border border-slate-700">
                <i data-lucide="message-circle" class="w-3.5 h-3.5 text-emerald-400"></i> Helpdesk WA
            </a>
        </div>
    </div>

    <!-- INTERACTIVE DASHBOARD TOUR: CUTOUT SPOTLIGHT & CARD -->
    <div id="scm-tour-backdrop" class="hidden" style="position: fixed; inset: 0; z-index: 99980; background: rgba(0,0,0,0.01);" onclick="closeDashboardTour()"></div>
    <div id="scm-tour-spotlight" class="hidden" style="position: fixed; z-index: 99990; pointer-events: none; border-radius: 16px; border: 4px solid #f59e0b; box-shadow: 0 0 0 9999px rgba(15, 23, 42, 0.85), 0 0 30px rgba(245, 158, 11, 0.6); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);"></div>
    <div id="scm-tour-card" class="hidden max-w-sm sm:max-w-md bg-slate-900 text-white rounded-2xl p-5 shadow-2xl transition-all duration-300" style="position: fixed; z-index: 99999; border: 2px solid #f59e0b; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.75);">
        <div class="flex items-center justify-between gap-3 mb-3 border-b border-slate-800 pb-3">
            <div class="flex items-center gap-2">
                <span id="scm-tour-badge" class="bg-amber-500 text-slate-950 text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full shadow-sm">Langkah 1 dari 5</span>
                <span class="text-xs text-slate-400">Tur Panduan SCM</span>
            </div>
            <button type="button" onclick="closeDashboardTour()" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition" title="Tutup">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="space-y-2">
            <h3 id="scm-tour-title" class="text-sm sm:text-base font-bold text-amber-300">Judul Langkah</h3>
            <p id="scm-tour-desc" class="text-xs text-slate-200 leading-relaxed">Deskripsi alur panduan.</p>
        </div>
        <div class="flex items-center justify-between gap-2 mt-5 pt-3 border-t border-slate-800">
            <button type="button" id="scm-tour-btn-prev" onclick="prevTourStep()" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 transition flex items-center gap-1">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Sebelumnya
            </button>
            <div class="flex items-center gap-2">
                <button type="button" onclick="closeDashboardTour()" class="text-xs font-semibold px-3 py-1.5 text-slate-400 hover:text-slate-200 transition">
                    Tutup
                </button>
                <button type="button" id="scm-tour-btn-next" onclick="nextTourStep()" class="text-xs font-bold px-3.5 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 transition flex items-center gap-1 shadow-md">
                    Lanjut <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function initDashboardCharts() {
        if (typeof Chart === 'undefined') {
            setTimeout(initDashboardCharts, 50);
            return;
        }

        // Trend Chart (Dynamic Data with Dual Y-Axis for Blok vs Unit)
        const ctxTrend = document.getElementById('trendChart')?.getContext('2d');
        if (ctxTrend) {
            new Chart(ctxTrend, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels),
                    datasets: [
                        {
                            label: 'Bahan Baku Masuk (Blok)',
                            data: @json($chartMaterialsIn),
                            backgroundColor: 'rgba(59, 130, 246, 0.85)',
                            borderRadius: 6,
                            yAxisID: 'y1'
                        },
                        {
                            label: 'Barang Jadi Selesai (Unit)',
                            data: @json($chartOutputs),
                            backgroundColor: 'rgba(16, 185, 129, 0.85)',
                            borderRadius: 6,
                            yAxisID: 'y'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label || '';
                                    const val = context.raw || 0;
                                    return label.includes('Blok') ? `${label}: ${val} Blok` : `${label}: ${val} Unit`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Output Produk (Unit)',
                                font: { size: 10, weight: 'bold' }
                            },
                            grid: { color: 'rgba(226, 232, 240, 0.5)' }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Bahan Baku (Blok)',
                                font: { size: 10, weight: 'bold' }
                            },
                            grid: { drawOnChartArea: false }
                        }
                    }
                }
            });
        }

        // Composition Chart (Dynamic Doughnut)
        const ctxComp = document.getElementById('compositionChart')?.getContext('2d');
        if (ctxComp) {
            new Chart(ctxComp, {
                type: 'doughnut',
                data: {
                    labels: ['Marmer Alam', 'Batu Kali', 'Onyx'],
                    datasets: [{
                        data: [
                            {{ $materialBreakdown['marmer'] ?? 42 }},
                            {{ $materialBreakdown['batu_kali'] ?? 65 }},
                            {{ $materialBreakdown['onix'] ?? 8 }}
                        ],
                        backgroundColor: ['#3b82f6', '#64748b', '#f59e0b']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDashboardCharts);
    } else {
        initDashboardCharts();
    }

    // ==========================================
    // INTERACTIVE DASHBOARD TOUR ENGINE
    // ==========================================
    const scmTourSteps = [
        {
            elementId: 'tour-step-1',
            badge: 'Langkah 1 / 5',
            title: 'Pusat Kendali & Pintasan Cepat SCM',
            desc: 'Menampilkan peran aktif Anda dan tombol aksi cepat untuk mengelola pesanan, alur rantai pasok marmer, laporan PCE, dan peramalan AI ARIMA.'
        },
        {
            elementId: 'tour-step-2',
            badge: 'Langkah 2 / 5',
            title: 'Notifikasi & Validasi 2-Gate SPK',
            desc: 'Mekanisme pencegah pesanan fiktif. Pesanan e-commerce dari pembeli online wajib divalidasi bukti bayarnya (DP 50% atau Lunas) sebelum otomatis diterbitkan sebagai kartu SPK ke bengkel.'
        },
        {
            elementId: 'tour-step-3',
            badge: 'Langkah 3 / 5',
            title: '5 Kartu KPI Rantai Pasok Terpadu',
            desc: 'Ringkasan data real-time: Total Bahan Mentah di gudang batu, SPK aktif di lantai produksi, barang jadi lolos QC, pesanan online, dan valuasi total nilai inventori.'
        },
        {
            elementId: 'tour-step-4',
            badge: 'Langkah 4 / 5',
            title: 'Diagram Alur Rantai Pasok 8 Tahap',
            desc: 'Visualisasi aliran material batuan hulu-ke-hilir: 1. Tambang → 2. Gudang Batu → 3. Mesin Slep → 4. Pembubutan → 5. QC 1 → 6. Poles Halus → 7. QC 2 → 8. Distribusi. Setiap kartu dapat diklik langsung.'
        },
        {
            elementId: 'tour-step-5',
            badge: 'Langkah 5 / 5',
            title: 'Grafik Tren Produksi & Komposisi Batu',
            desc: 'Menganalisis perbandingan volume bahan baku masuk (balok) terhadap barang jadi yang selesai (unit), serta sebaran proporsi jenis batuan alam (Marmer, Batu Kali, Onyx).'
        }
    ];

    let currentTourStep = 0;
    let activeHighlightEl = null;

    function startDashboardTour() {
        currentTourStep = 0;
        const backdrop = document.getElementById('scm-tour-backdrop');
        const spotlight = document.getElementById('scm-tour-spotlight');
        const card = document.getElementById('scm-tour-card');
        if (!backdrop || !spotlight || !card) return;

        if (backdrop.parentElement !== document.body) {
            document.body.appendChild(backdrop);
            document.body.appendChild(spotlight);
            document.body.appendChild(card);
        }

        if (document.body.style.transform && document.body.style.transform !== 'none') {
            document.body.style.transform = 'none';
        }

        backdrop.classList.remove('hidden');
        spotlight.classList.remove('hidden');
        card.classList.remove('hidden');
        backdrop.style.display = 'block';
        spotlight.style.display = 'block';
        card.style.display = 'block';
        renderTourStep();
    }

    function renderTourStep() {
        const step = scmTourSteps[currentTourStep];
        const el = document.getElementById(step.elementId);
        if (!el) {
            closeDashboardTour();
            return;
        }

        // Smooth scroll element into view first
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });

        document.getElementById('scm-tour-badge').innerText = step.badge;
        document.getElementById('scm-tour-title').innerText = step.title;
        document.getElementById('scm-tour-desc').innerText = step.desc;

        const prevBtn = document.getElementById('scm-tour-btn-prev');
        const nextBtn = document.getElementById('scm-tour-btn-next');

        if (currentTourStep === 0) {
            prevBtn.classList.add('opacity-40', 'pointer-events-none');
        } else {
            prevBtn.classList.remove('opacity-40', 'pointer-events-none');
        }

        if (currentTourStep === scmTourSteps.length - 1) {
            nextBtn.innerHTML = 'Selesai ✓';
        } else {
            nextBtn.innerHTML = 'Lanjut <i data-lucide="arrow-right" class="w-3.5 h-3.5 inline"></i>';
        }

        // Position the cutout spotlight and the card
        setTimeout(() => {
            const rect = el.getBoundingClientRect();
            const spotlight = document.getElementById('scm-tour-spotlight');
            const card = document.getElementById('scm-tour-card');
            if (!spotlight || !card) return;

            const pad = 12;
            const spotTop = Math.max(0, rect.top - pad);
            const spotLeft = Math.max(0, rect.left - pad);
            const spotWidth = rect.width + (pad * 2);
            const spotHeight = rect.height + (pad * 2);

            spotlight.style.top = `${spotTop}px`;
            spotlight.style.left = `${spotLeft}px`;
            spotlight.style.width = `${spotWidth}px`;
            spotlight.style.height = `${spotHeight}px`;
            spotlight.style.boxShadow = '0 0 0 9999px rgba(15, 23, 42, 0.85), 0 0 30px rgba(245, 158, 11, 0.6)';
            spotlight.style.border = '4px solid #f59e0b';
            spotlight.style.borderRadius = '16px';
            spotlight.style.display = 'block';

            const cardHeight = card.offsetHeight || 220;
            const cardWidth = card.offsetWidth || 400;

            let cardTop = spotTop + spotHeight + 16;
            let cardLeft = spotLeft + (spotWidth / 2) - (cardWidth / 2);

            // If not enough room below, place above
            if (cardTop + cardHeight > window.innerHeight - 20) {
                cardTop = Math.max(20, spotTop - cardHeight - 16);
            }
            if (cardLeft < 20) cardLeft = 20;
            if (cardLeft + cardWidth > window.innerWidth - 20) {
                cardLeft = window.innerWidth - cardWidth - 20;
            }

            card.style.top = `${cardTop}px`;
            card.style.left = `${cardLeft}px`;
            card.style.display = 'block';

            if (window.lucide) lucide.createIcons();
        }, 140);
    }

    function nextTourStep() {
        if (currentTourStep < scmTourSteps.length - 1) {
            currentTourStep++;
            renderTourStep();
        } else {
            closeDashboardTour();
        }
    }

    function prevTourStep() {
        if (currentTourStep > 0) {
            currentTourStep--;
            renderTourStep();
        }
    }

    function closeDashboardTour() {
        const backdrop = document.getElementById('scm-tour-backdrop');
        const spotlight = document.getElementById('scm-tour-spotlight');
        const card = document.getElementById('scm-tour-card');
        if (backdrop) { backdrop.classList.add('hidden'); backdrop.style.display = 'none'; }
        if (spotlight) { spotlight.classList.add('hidden'); spotlight.style.display = 'none'; }
        if (card) { card.classList.add('hidden'); card.style.display = 'none'; }
    }
</script>
@endsection
