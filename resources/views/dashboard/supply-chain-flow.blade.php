@extends('layouts.app')

@section('title', 'Alur Rantai Pasok (End-to-End Supply Chain Flow)')
@section('page-title', 'Alur Rantai Pasok (Supply Chain Flow)')
@section('page-subtitle', 'Visualisasi lengkap dari bahan mentah batuan alam hingga produk sampai ke pelanggan klaster IKM Marmer')

@section('topbar-actions')
    <a href="{{ route('dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 transition">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Dashboard
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- 1. HORIZONTAL 8 STAGES PROGRESSION RIBBON -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span class="p-1.5 bg-blue-50 text-blue-600 rounded-lg"><i data-lucide="git-commit" class="w-4 h-4"></i></span>
                    Tahapan Aliran Material & Nilai Tambah (8 Tahap Hulu - Hilir)
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Pemetaan tahapan siklus rantai pasok batuan alam dari tambang lokal hingga pelanggan</p>
            </div>
            <span class="text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Aliran SCM Aktif
            </span>
        </div>

        <div class="overflow-x-auto pb-2">
            <div class="flex items-center justify-between min-w-[900px] gap-2">

                <!-- Stage 1: Bahan Baku -->
                <div class="flex-1 bg-slate-50 hover:bg-blue-50/50 p-3 rounded-xl border border-slate-200 transition group flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-slate-200 group-hover:bg-blue-100 text-slate-700 group-hover:text-blue-600 rounded-lg transition">
                            <i data-lucide="gem" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 font-mono">01</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">1. Bahan Baku</h4>
                        <p class="text-[11px] font-semibold text-purple-600 mt-0.5">Inv. Accuracy</p>
                        <p class="text-xs font-bold text-slate-700">99.2%</p>
                    </div>
                    <a href="{{ route('materials.index') }}" class="text-[10px] font-bold text-blue-600 hover:text-blue-700 mt-2 flex items-center gap-0.5 group-hover:underline">
                        Lihat <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>

                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 flex-shrink-0"></i>

                <!-- Stage 2: QC Input -->
                <div class="flex-1 bg-slate-50 hover:bg-cyan-50/50 p-3 rounded-xl border border-slate-200 transition group flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-slate-200 group-hover:bg-cyan-100 text-slate-700 group-hover:text-cyan-600 rounded-lg transition">
                            <i data-lucide="microscope" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 font-mono">02</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">2. QC Input</h4>
                        <p class="text-[11px] font-semibold text-cyan-600 mt-0.5">Pass Rate</p>
                        <p class="text-xs font-bold text-slate-700">98%</p>
                    </div>
                    <a href="{{ route('qc.index') }}" class="text-[10px] font-bold text-cyan-600 hover:text-cyan-700 mt-2 flex items-center gap-0.5 group-hover:underline">
                        Lihat <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>

                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 flex-shrink-0"></i>

                <!-- Stage 3: Produksi -->
                <div class="flex-1 bg-slate-50 hover:bg-amber-50/50 p-3 rounded-xl border border-slate-200 transition group flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-slate-200 group-hover:bg-amber-100 text-slate-700 group-hover:text-amber-600 rounded-lg transition">
                            <i data-lucide="factory" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 font-mono">03</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">3. Produksi</h4>
                        <p class="text-[11px] font-semibold text-amber-600 mt-0.5">OEE</p>
                        <p class="text-xs font-bold text-slate-700">87%</p>
                    </div>
                    <a href="{{ route('production.kanban') }}" class="text-[10px] font-bold text-amber-600 hover:text-amber-700 mt-2 flex items-center gap-0.5 group-hover:underline">
                        Lihat <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>

                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 flex-shrink-0"></i>

                <!-- Stage 4: WIP -->
                <div class="flex-1 bg-slate-50 hover:bg-orange-50/50 p-3 rounded-xl border border-slate-200 transition group flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-slate-200 group-hover:bg-orange-100 text-slate-700 group-hover:text-orange-600 rounded-lg transition">
                            <i data-lucide="cog" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 font-mono">04</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">4. WIP Mesin</h4>
                        <p class="text-[11px] font-semibold text-orange-600 mt-0.5">Cycle Time</p>
                        <p class="text-xs font-bold text-slate-700">2.4 Hari</p>
                    </div>
                    <a href="{{ route('production.wip') }}" class="text-[10px] font-bold text-orange-600 hover:text-orange-700 mt-2 flex items-center gap-0.5 group-hover:underline">
                        Lihat <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>

                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 flex-shrink-0"></i>

                <!-- Stage 5: QC Output -->
                <div class="flex-1 bg-slate-50 hover:bg-emerald-50/50 p-3 rounded-xl border border-slate-200 transition group flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-slate-200 group-hover:bg-emerald-100 text-slate-700 group-hover:text-emerald-600 rounded-lg transition">
                            <i data-lucide="sparkles" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 font-mono">05</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">5. QC Output</h4>
                        <p class="text-[11px] font-semibold text-emerald-600 mt-0.5">Defect Rate</p>
                        <p class="text-xs font-bold text-slate-700">0.8%</p>
                    </div>
                    <a href="{{ route('qc.index') }}" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700 mt-2 flex items-center gap-0.5 group-hover:underline">
                        Lihat <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>

                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 flex-shrink-0"></i>

                <!-- Stage 6: Barang Jadi -->
                <div class="flex-1 bg-slate-50 hover:bg-green-50/50 p-3 rounded-xl border border-slate-200 transition group flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-slate-200 group-hover:bg-green-100 text-slate-700 group-hover:text-green-600 rounded-lg transition">
                            <i data-lucide="package" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 font-mono">06</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">6. Barang Jadi</h4>
                        <p class="text-[11px] font-semibold text-green-600 mt-0.5">Stock Coverage</p>
                        <p class="text-xs font-bold text-slate-700">14 Hari</p>
                    </div>
                    <a href="{{ route('catalog') }}" class="text-[10px] font-bold text-green-600 hover:text-green-700 mt-2 flex items-center gap-0.5 group-hover:underline">
                        Lihat <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>

                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 flex-shrink-0"></i>

                <!-- Stage 7: Distribusi -->
                <div class="flex-1 bg-slate-50 hover:bg-indigo-50/50 p-3 rounded-xl border border-slate-200 transition group flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-slate-200 group-hover:bg-indigo-100 text-slate-700 group-hover:text-indigo-600 rounded-lg transition">
                            <i data-lucide="truck" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 font-mono">07</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">7. Distribusi</h4>
                        <p class="text-[11px] font-semibold text-indigo-600 mt-0.5">OTD Rate</p>
                        <p class="text-xs font-bold text-slate-700">94%</p>
                    </div>
                    <a href="{{ route('distribution.index') }}" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-700 mt-2 flex items-center gap-0.5 group-hover:underline">
                        Lihat <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>

                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 flex-shrink-0"></i>

                <!-- Stage 8: Terkirim -->
                <div class="flex-1 bg-slate-50 hover:bg-teal-50/50 p-3 rounded-xl border border-slate-200 transition group flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-slate-200 group-hover:bg-teal-100 text-slate-700 group-hover:text-teal-600 rounded-lg transition">
                            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 font-mono">08</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">8. Terkirim</h4>
                        <p class="text-[11px] font-semibold text-teal-600 mt-0.5">Kepuasan (CSAT)</p>
                        <p class="text-xs font-bold text-slate-700">4.8 / 5.0</p>
                    </div>
                    <a href="{{ route('distribution.index') }}" class="text-[10px] font-bold text-teal-600 hover:text-teal-700 mt-2 flex items-center gap-0.5 group-hover:underline">
                        Lihat <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- 2. 4 MAIN SUPPLY CHAIN KPI METRICS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Card 1: Lead Time Rata-rata -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Lead Time Rata-rata</span>
                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                    <i data-lucide="timer" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">8.4</h3>
                    <span class="text-xs font-bold text-slate-400">Hari</span>
                </div>
                <p class="text-[11px] text-slate-500 mt-1">PO bahan baku $\rightarrow$ produk jadi siap kirim</p>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center text-[10px] font-semibold text-emerald-600 gap-1">
                <i data-lucide="trending-down" class="w-3.5 h-3.5"></i> -1.2 hari dari baseline manual
            </div>
        </div>

        <!-- Card 2: Inventory Turns -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Inventory Turns</span>
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">6.8</h3>
                    <span class="text-xs font-bold text-slate-400">x / Tahun</span>
                </div>
                <p class="text-[11px] text-slate-500 mt-1">Perputaran inventori bahan baku & barang</p>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center text-[10px] font-semibold text-emerald-600 gap-1">
                <i data-lucide="trending-up" class="w-3.5 h-3.5"></i> Optimal (Klaster Marmer)
            </div>
        </div>

        <!-- Card 3: Throughput Harian -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Throughput Kapasitas</span>
                <div class="p-2 bg-amber-50 text-amber-600 rounded-xl">
                    <i data-lucide="gauge" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">14</h3>
                    <span class="text-xs font-bold text-slate-400">Biji / Hari</span>
                </div>
                <p class="text-[11px] text-slate-500 mt-1">Kapasitas 7 Mesin Bubut (650-750 Unit/Bln)</p>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center text-[10px] font-semibold text-blue-600 gap-1">
                <i data-lucide="activity" class="w-3.5 h-3.5"></i> Utilisasi Mesin 87%
            </div>
        </div>

        <!-- Card 4: Total Nilai Aliran SCM -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Nilai Aliran</span>
                <div class="p-2 bg-purple-50 text-purple-600 rounded-xl">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-2xl font-black text-purple-700 tracking-tight">Rp {{ number_format($totalInventoryValue / 1000000, 1) }}M</h3>
                    <span class="text-xs font-bold text-slate-400">Jt Rupiah</span>
                </div>
                <p class="text-[11px] text-slate-500 mt-1">Nilai total inventori di semua tahap rantai pasok</p>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center text-[10px] font-semibold text-purple-600 gap-1">
                <i data-lucide="layers" class="w-3.5 h-3.5"></i> Bahan Baku + WIP + Ready
            </div>
        </div>

    </div>

    <!-- 3. DETAIL PER TAHAP TABLE (TABEL MONITORING STATUS, VOLUME, KPI & BOTTLENECK) -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <i data-lucide="table" class="w-4 h-4 text-blue-600"></i>
                    Detail Per Tahap Rantai Pasok
                </h4>
                <p class="text-[11px] text-slate-500">Status operasional, volume inventori saat ini, metrik KPI, dan analisis potensi bottleneck</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b">
                    <tr>
                        <th class="p-3.5">Tahap Aliran</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5">Volume Saat Ini</th>
                        <th class="p-3.5">KPI Utama</th>
                        <th class="p-3.5">Potensi Bottleneck & Keterangan</th>
                        <th class="p-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    
                    <!-- Row 1: Bahan Baku -->
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-3.5 font-bold text-slate-800 flex items-center gap-2.5">
                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                <i data-lucide="gem" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span>Bahan Baku</span>
                                <p class="text-[10px] text-slate-400 font-normal">Hulu & Penambang</p>
                            </div>
                        </td>
                        <td class="p-3.5">
                            @if($criticalCount > 0)
                            <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                                <i data-lucide="alert-triangle" class="w-3 h-3"></i> {{ $criticalCount }} Kritis
                            </span>
                            @else
                            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                                <i data-lucide="check" class="w-3 h-3"></i> Aman
                            </span>
                            @endif
                        </td>
                        <td class="p-3.5 font-semibold text-slate-700">
                            {{ $materialsCount }} Item
                            <p class="text-[10px] text-slate-400 font-normal">({{ number_format($totalRawStock, 0) }} Balok & Biji)</p>
                        </td>
                        <td class="p-3.5 font-mono text-slate-600">
                            Inventory Acc. 99.2%
                        </td>
                        <td class="p-3.5 text-slate-600">
                            <span class="text-slate-700 font-medium">Stok Marmer Putih Besole aman</span>, pasokan batu kali Boyolangu lancar.
                        </td>
                        <td class="p-3.5 text-right">
                            <a href="{{ route('materials.index') }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-2.5 py-1 rounded-lg transition inline-flex items-center gap-1">
                                Lihat <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- Row 2: Produksi (Slep & Bubut) -->
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-3.5 font-bold text-slate-800 flex items-center gap-2.5">
                            <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                                <i data-lucide="factory" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span>Produksi (SPK)</span>
                                <p class="text-[10px] text-slate-400 font-normal">Mesin Slep & Bubut</p>
                            </div>
                        </td>
                        <td class="p-3.5">
                            <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                                <i data-lucide="play" class="w-3 h-3"></i> Berjalan
                            </span>
                        </td>
                        <td class="p-3.5 font-semibold text-slate-700">
                            {{ $activeWorkOrders }} Batch Aktif
                            <p class="text-[10px] text-slate-400 font-normal">({{ $activeBatchQty }} Unit Target)</p>
                        </td>
                        <td class="p-3.5 font-mono text-slate-600">
                            OEE 87%
                        </td>
                        <td class="p-3.5 text-slate-600">
                            <span class="text-slate-700 font-medium">Normal</span>. Kapasitas potong mesin slep optimal 14 unit/hari.
                        </td>
                        <td class="p-3.5 text-right">
                            <a href="{{ route('production.kanban') }}" class="text-[11px] font-bold text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 px-2.5 py-1 rounded-lg transition inline-flex items-center gap-1">
                                Lihat <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- Row 3: WIP (Stasiun Mesin) -->
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-3.5 font-bold text-slate-800 flex items-center gap-2.5">
                            <div class="p-2 bg-orange-50 text-orange-600 rounded-lg">
                                <i data-lucide="cog" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span>WIP Stasiun Mesin</span>
                                <p class="text-[10px] text-slate-400 font-normal">7 Mesin Bubut & Poles</p>
                            </div>
                        </td>
                        <td class="p-3.5">
                            <span class="bg-orange-100 text-orange-800 text-[10px] font-bold px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                                <i data-lucide="activity" class="w-3 h-3"></i> 3 Proses
                            </span>
                        </td>
                        <td class="p-3.5 font-semibold text-slate-700">
                            22 Unit Dikerjakan
                            <p class="text-[10px] text-slate-400 font-normal">(Slep, Bubut 1-4, Poles)</p>
                        </td>
                        <td class="p-3.5 font-mono text-slate-600">
                            Cycle Time 2.4 Hari (480 mnt)
                        </td>
                        <td class="p-3.5 text-slate-600">
                            <span class="text-amber-700 font-medium">Waktu handling sisa batu 390 mnt/mgg</span> dipangkas melalui modul hilirisasi residu.
                        </td>
                        <td class="p-3.5 text-right">
                            <a href="{{ route('production.wip') }}" class="text-[11px] font-bold text-orange-600 hover:text-orange-700 bg-orange-50 hover:bg-orange-100 px-2.5 py-1 rounded-lg transition inline-flex items-center gap-1">
                                Lihat <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- Row 4: Quality Control -->
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-3.5 font-bold text-slate-800 flex items-center gap-2.5">
                            <div class="p-2 bg-cyan-50 text-cyan-600 rounded-lg">
                                <i data-lucide="sparkles" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span>Quality Control (QC)</span>
                                <p class="text-[10px] text-slate-400 font-normal">Inspeksi 2-Tahap</p>
                            </div>
                        </td>
                        <td class="p-3.5">
                            <span class="bg-cyan-100 text-cyan-800 text-[10px] font-bold px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                                <i data-lucide="check-circle" class="w-3 h-3"></i> 2 Tahap Aktif
                            </span>
                        </td>
                        <td class="p-3.5 font-semibold text-slate-700">
                            {{ $qcLogsCount }} Log Terdata
                            <p class="text-[10px] text-slate-400 font-normal">(QC 1 Mentah & QC 2 Poles)</p>
                        </td>
                        <td class="p-3.5 font-mono text-slate-600">
                            Defect Rate 0.8% (Hi-Glossy 95 GU)
                        </td>
                        <td class="p-3.5 text-slate-600">
                            <span class="text-slate-700 font-medium">Normal</span>. Rework pori-pori mikro ditambal resin bening sebelum masuk poles akhir.
                        </td>
                        <td class="p-3.5 text-right">
                            <a href="{{ route('qc.index') }}" class="text-[11px] font-bold text-cyan-600 hover:text-cyan-700 bg-cyan-50 hover:bg-cyan-100 px-2.5 py-1 rounded-lg transition inline-flex items-center gap-1">
                                Lihat <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- Row 5: Barang Jadi & Gudang -->
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-3.5 font-bold text-slate-800 flex items-center gap-2.5">
                            <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                                <i data-lucide="package" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span>Barang Jadi</span>
                                <p class="text-[10px] text-slate-400 font-normal">Stok Siap Jual & Ekspor</p>
                            </div>
                        </td>
                        <td class="p-3.5">
                            <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                                <i data-lucide="check" class="w-3 h-3"></i> Normal
                            </span>
                        </td>
                        <td class="p-3.5 font-semibold text-slate-700">
                            {{ $totalReadyStock }} Unit
                            <p class="text-[10px] text-slate-400 font-normal">({{ $readyProductsCount }} Varian Wastafel)</p>
                        </td>
                        <td class="p-3.5 font-mono text-slate-600">
                            Stock Coverage 14 Hari
                        </td>
                        <td class="p-3.5 text-slate-600">
                            <span class="text-slate-700 font-medium">Stok Wastafel Marmer Putih B1 & Stepping Stone aman</span> untuk order reguler.
                        </td>
                        <td class="p-3.5 text-right">
                            <a href="{{ route('catalog') }}" class="text-[11px] font-bold text-green-600 hover:text-green-700 bg-green-50 hover:bg-green-100 px-2.5 py-1 rounded-lg transition inline-flex items-center gap-1">
                                Lihat <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- Row 6: Distribusi & Ekspedisi -->
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-3.5 font-bold text-slate-800 flex items-center gap-2.5">
                            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                                <i data-lucide="truck" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span>Distribusi & Ekspedisi</span>
                                <p class="text-[10px] text-slate-400 font-normal">Packing Kayu & Kargo</p>
                            </div>
                        </td>
                        <td class="p-3.5">
                            <span class="bg-indigo-100 text-indigo-800 text-[10px] font-bold px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                                <i data-lucide="navigation" class="w-3 h-3"></i> {{ $activeShipments }} Aktif
                            </span>
                        </td>
                        <td class="p-3.5 font-semibold text-slate-700">
                            {{ $activeShipments + $deliveredShipments }} Surat Jalan
                            <p class="text-[10px] text-slate-400 font-normal">(Bali Mandiri & Kobra Express)</p>
                        </td>
                        <td class="p-3.5 font-mono text-slate-600">
                            On-Time Delivery (OTD) 94%
                        </td>
                        <td class="p-3.5 text-slate-600">
                            <span class="text-slate-700 font-medium">Normal</span>. Checklist packing peti kayu solid terverifikasi 100% anti-pecah.
                        </td>
                        <td class="p-3.5 text-right">
                            <a href="{{ route('distribution.index') }}" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg transition inline-flex items-center gap-1">
                                Lihat <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </a>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
