@extends('layouts.app')

@section('title', 'Dashboard Monitoring Rantai Pasok')
@section('page-title', 'Dashboard Monitoring Rantai Pasok')
@section('page-subtitle', 'Klaster IKM Marmer Kabupaten Tulungagung')

@section('topbar-actions')
    <a href="{{ route('production.kanban') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 shadow-sm transition">
        <i data-lucide="plus-circle" class="w-4 h-4"></i> Buat SPK Baru
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- ALERT BANNER: CRITICAL STOCK -->
    @if($criticalMaterials->isNotEmpty())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-red-100 text-red-600 rounded-full">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-red-900">Peringatan Stok Kritis Terdeteksi!</h4>
                <p class="text-xs text-red-700">Terdapat <b>{{ $criticalMaterials->count() }} material</b> yang berada di bawah ambang batas minimum stok. Segera lakukan pemesanan pengadaan.</p>
            </div>
        </div>
        <a href="{{ route('materials.index') }}" class="bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-3 py-1.5 rounded-md transition flex items-center gap-1">
            <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i> Periksa Stok
        </a>
    </div>
    @endif

    <!-- 4 KPI CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Bahan Mentah</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalRawMaterials, 0) }} Blok</h3>
                <p class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                    <i data-lucide="trending-up" class="w-3 h-3"></i> +12% bln ini
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i data-lucide="mountain" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">SPK Produksi Aktif</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $activeWorkOrders }} Batch</h3>
                <p class="text-[11px] text-blue-600 font-semibold mt-1">Mesin Bubut: 7/7 Beroperasi</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i data-lucide="cog" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Barang Jadi Siap Kirim</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $totalReadyGoods }} Unit</h3>
                <p class="text-[11px] text-emerald-600 font-semibold mt-1">Lolos Standar QC 2</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i data-lucide="package-check" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Nilai Inventori</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($totalInventoryValue / 1000000, 1) }} Jt</h3>
                <p class="text-[11px] text-slate-500 mt-1">Bahan Baku + Barang Jadi</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <i data-lucide="coins" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- INTERACTIVE SUPPLY CHAIN FLOW (8 TAHAP MARMER) -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i data-lucide="git-merge" class="w-5 h-5 text-blue-600"></i> Diagram Alur Rantai Pasok Marmer Terintegrasi
                </h3>
                <p class="text-xs text-slate-500">Visualisasi aliran dari tambang hingga pelanggan. Klik kotak tahap untuk langsung membuka modulnya.</p>
            </div>
            <span class="text-xs bg-slate-100 text-slate-700 px-3 py-1 rounded-md font-semibold border">8 Tahap Hulu-Hilir</span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-2.5 pt-2">
            <!-- Stage 1 -->
            <a href="{{ route('materials.index') }}" class="group p-3 bg-slate-50 hover:bg-blue-50 border hover:border-blue-300 rounded-lg text-center transition block">
                <div class="w-8 h-8 mx-auto mb-1.5 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                    <i data-lucide="pickaxe" class="w-4 h-4"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-800 group-hover:text-blue-600">1. Tambang</h5>
                <p class="text-[10px] text-slate-500 mt-0.5">3 Pemasok</p>
                <span class="inline-block mt-1.5 text-[9px] bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded font-semibold">Besole/CPD</span>
            </a>

            <!-- Stage 2 -->
            <a href="{{ route('materials.index') }}" class="group p-3 bg-slate-50 hover:bg-blue-50 border hover:border-blue-300 rounded-lg text-center transition block">
                <div class="w-8 h-8 mx-auto mb-1.5 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center">
                    <i data-lucide="boxes" class="w-4 h-4"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-800 group-hover:text-blue-600">2. Gudang Batu</h5>
                <p class="text-[10px] text-slate-500 mt-0.5">{{ number_format($totalRawMaterials, 0) }} Blok</p>
                <span class="inline-flex items-center gap-1 mt-1.5 text-[9px] bg-red-100 text-red-700 px-1.5 py-0.5 rounded font-semibold">
                    <span class="w-1 h-1 rounded-full bg-red-500"></span> 1 Kritis
                </span>
            </a>

            <!-- Stage 3 -->
            <a href="{{ route('production.kanban') }}" class="group p-3 bg-slate-50 hover:bg-blue-50 border hover:border-blue-300 rounded-lg text-center transition block">
                <div class="w-8 h-8 mx-auto mb-1.5 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center">
                    <i data-lucide="scissors" class="w-4 h-4"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-800 group-hover:text-blue-600">3. Mesin Slep</h5>
                <p class="text-[10px] text-slate-500 mt-0.5">Potong Blok</p>
                <span class="inline-block mt-1.5 text-[9px] bg-slate-200 text-slate-700 px-1.5 py-0.5 rounded font-semibold">Max 80 cm</span>
            </a>

            <!-- Stage 4 -->
            <a href="{{ route('production.wip') }}" class="group p-3 bg-slate-50 hover:bg-blue-50 border hover:border-blue-300 rounded-lg text-center transition block">
                <div class="w-8 h-8 mx-auto mb-1.5 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                    <i data-lucide="disc" class="w-4 h-4"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-800 group-hover:text-blue-600">4. Pembubutan</h5>
                <p class="text-[10px] text-slate-500 mt-0.5">7 Mesin Aktif</p>
                <span class="inline-block mt-1.5 text-[9px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded font-semibold">14 Unit/Hari</span>
            </a>

            <!-- Stage 5 -->
            <a href="{{ route('qc.index') }}" class="group p-3 bg-slate-50 hover:bg-blue-50 border hover:border-blue-300 rounded-lg text-center transition block">
                <div class="w-8 h-8 mx-auto mb-1.5 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center">
                    <i data-lucide="scan-search" class="w-4 h-4"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-800 group-hover:text-blue-600">5. QC Tahap 1</h5>
                <p class="text-[10px] text-slate-500 mt-0.5">Cek Serat Awal</p>
                <span class="inline-block mt-1.5 text-[9px] bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded font-semibold">Bebas Retak</span>
            </a>

            <!-- Stage 6 -->
            <a href="{{ route('production.kanban') }}" class="group p-3 bg-slate-50 hover:bg-blue-50 border hover:border-blue-300 rounded-lg text-center transition block">
                <div class="w-8 h-8 mx-auto mb-1.5 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-800 group-hover:text-blue-600">6. Finishing Poles</h5>
                <p class="text-[10px] text-slate-500 mt-0.5">Hi-Glossy</p>
                <span class="inline-block mt-1.5 text-[9px] bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded font-semibold">Kilau Super</span>
            </a>

            <!-- Stage 7 -->
            <a href="{{ route('qc.index') }}" class="group p-3 bg-slate-50 hover:bg-blue-50 border hover:border-blue-300 rounded-lg text-center transition block">
                <div class="w-8 h-8 mx-auto mb-1.5 rounded-lg bg-green-100 text-green-700 flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-800 group-hover:text-blue-600">7. QC Tahap 2</h5>
                <p class="text-[10px] text-slate-500 mt-0.5">Uji Afur</p>
                <span class="inline-block mt-1.5 text-[9px] bg-green-100 text-green-800 px-1.5 py-0.5 rounded font-semibold">Siap Gudang</span>
            </a>

            <!-- Stage 8 -->
            <a href="{{ route('distribution.index') }}" class="group p-3 bg-slate-50 hover:bg-blue-50 border hover:border-blue-300 rounded-lg text-center transition block">
                <div class="w-8 h-8 mx-auto mb-1.5 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center">
                    <i data-lucide="truck" class="w-4 h-4"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-800 group-hover:text-blue-600">8. Distribusi</h5>
                <p class="text-[10px] text-slate-500 mt-0.5">Packing Kayu</p>
                <span class="inline-block mt-1.5 text-[9px] bg-purple-100 text-purple-800 px-1.5 py-0.5 rounded font-semibold">Kirim Buyer</span>
            </a>
        </div>
    </div>

    <!-- CHARTS & SUMMARY SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart 1: Tren Pengadaan vs Output -->
        <div class="lg:col-span-2 bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="text-sm font-bold text-slate-800">Tren Pengadaan Bahan vs Output Produksi (6 Bulan)</h4>
                    <p class="text-xs text-slate-500">Perbandingan volume material masuk (blok) vs wastafel selesai (unit)</p>
                </div>
            </div>
            <div class="h-64">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Komposisi Bahan & Stok -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <h4 class="text-sm font-bold text-slate-800 mb-1">Komposisi Inventori Batuan Alam</h4>
                <p class="text-xs text-slate-500 mb-3">Distribusi volume bahan baku di gudang</p>
                <div class="h-44 flex items-center justify-center">
                    <canvas id="compositionChart"></canvas>
                </div>
            </div>
            <div class="pt-3 border-t border-slate-100 text-xs space-y-1.5">
                <div class="flex justify-between text-slate-600"><span>Marmer Putih</span><b>{{ $materialBreakdown['marmer'] ?? 18 }} Blok</b></div>
                <div class="flex justify-between text-slate-600"><span>Batu Kali</span><b>{{ $materialBreakdown['batu_kali'] ?? 35 }} Blok</b></div>
                <div class="flex justify-between text-slate-600"><span>Onyx Kristal</span><b>{{ $materialBreakdown['onix'] ?? 12 }} Blok</b></div>
            </div>
        </div>
    </div>

    <!-- TABEL MATERIAL KRITIS & STATUS MESIN -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Tabel Stok Kritis -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-red-500"></i> Daftar Material Perlu Pengadaan Segera
                </h4>
                <a href="{{ route('materials.index') }}" class="text-xs text-blue-600 hover:underline font-medium">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold border-b">
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
                            <td class="p-2.5 text-red-600 font-bold">{{ $mat->current_stock }} {{ $mat->unit }}</td>
                            <td class="p-2.5 text-slate-500">{{ $mat->minimum_stock }} {{ $mat->unit }}</td>
                            <td class="p-2.5">
                                <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-2 py-0.5 rounded font-semibold text-[10px]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Kritis
                                </span>
                            </td>
                            <td class="p-2.5 text-right">
                                <a href="{{ route('materials.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-xs">Kelola</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-3 text-center text-slate-400">Semua stok bahan baku dalam kondisi aman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Status 7 Mesin Bubut UD Cahaya Onix -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i data-lucide="gauge" class="w-4 h-4 text-indigo-500"></i> Status Utilisasi 7 Mesin Bubut (UD Cahaya Onix)
                </h4>
                <span class="text-xs bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded font-semibold">100% Aktif</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs">
                <div class="p-2.5 bg-slate-50 border rounded-lg text-center">
                    <p class="font-bold text-slate-800">Mesin Bubut 1</p>
                    <p class="text-[10px] text-blue-600 mt-0.5">Wastafel D40</p>
                    <span class="text-[9px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-semibold mt-1 inline-block">Running (85m)</span>
                </div>
                <div class="p-2.5 bg-slate-50 border rounded-lg text-center">
                    <p class="font-bold text-slate-800">Mesin Bubut 2</p>
                    <p class="text-[10px] text-blue-600 mt-0.5">Wastafel D40</p>
                    <span class="text-[9px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-semibold mt-1 inline-block">Running (80m)</span>
                </div>
                <div class="p-2.5 bg-slate-50 border rounded-lg text-center">
                    <p class="font-bold text-slate-800">Mesin Bubut 3</p>
                    <p class="text-[10px] text-amber-600 mt-0.5">Onyx Oval</p>
                    <span class="text-[9px] bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-semibold mt-1 inline-block">Poles (90m)</span>
                </div>
                <div class="p-2.5 bg-slate-50 border rounded-lg text-center">
                    <p class="font-bold text-slate-800">Mesin Bubut 4</p>
                    <p class="text-[10px] text-blue-600 mt-0.5">Wastafel Mangkok</p>
                    <span class="text-[9px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-semibold mt-1 inline-block">Running (75m)</span>
                </div>
                <div class="p-2.5 bg-slate-50 border rounded-lg text-center">
                    <p class="font-bold text-slate-800">Mesin Bubut 5</p>
                    <p class="text-[10px] text-blue-600 mt-0.5">Wastafel D40</p>
                    <span class="text-[9px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-semibold mt-1 inline-block">Running (85m)</span>
                </div>
                <div class="p-2.5 bg-slate-50 border rounded-lg text-center">
                    <p class="font-bold text-slate-800">Mesin Bubut 6</p>
                    <p class="text-[10px] text-blue-600 mt-0.5">Wastafel Kotak</p>
                    <span class="text-[9px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-semibold mt-1 inline-block">Running (90m)</span>
                </div>
                <div class="p-2.5 bg-slate-50 border rounded-lg text-center col-span-2 sm:col-span-2">
                    <p class="font-bold text-slate-800">Mesin Bubut 7 & Mesin Slep</p>
                    <p class="text-[10px] text-slate-600 mt-0.5">Persiapan Batch SPK-2026-04</p>
                    <span class="text-[9px] bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded font-semibold mt-1 inline-block">Potong Slep (60m)</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    window.addEventListener('DOMContentLoaded', () => {
        // Trend Chart
        const ctxTrend = document.getElementById('trendChart')?.getContext('2d');
        if (ctxTrend) {
            new Chart(ctxTrend, {
                type: 'bar',
                data: {
                    labels: ['Nov 25', 'Des 25', 'Jan 26', 'Feb 26', 'Mar 26', 'Apr 26'],
                    datasets: [
                        {
                            label: 'Bahan Baku Masuk (Blok)',
                            data: [35, 42, 38, 45, 52, 48],
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderRadius: 6
                        },
                        {
                            label: 'Wastafel Selesai (Unit)',
                            data: [70, 84, 76, 90, 104, 96],
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top' } }
                }
            });
        }

        // Composition Chart
        const ctxComp = document.getElementById('compositionChart')?.getContext('2d');
        if (ctxComp) {
            new Chart(ctxComp, {
                type: 'doughnut',
                data: {
                    labels: ['Marmer Putih', 'Batu Kali', 'Onyx'],
                    datasets: [{
                        data: [
                            {{ $materialBreakdown['marmer'] ?? 18 }},
                            {{ $materialBreakdown['batu_kali'] ?? 35 }},
                            {{ $materialBreakdown['onix'] ?? 12 }}
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
    });
</script>
@endsection
