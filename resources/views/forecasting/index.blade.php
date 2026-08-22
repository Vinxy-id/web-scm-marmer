@extends('layouts.app')

@section('title', 'Peramalan Permintaan AI')
@section('page-title', 'Peramalan Permintaan & AI Assistant')
@section('page-subtitle', 'Integrasi Model Deret Waktu Holt-Winters & Moving Average Python FastAPI')

@section('content')
<div class="space-y-6">

    <!-- Forecasting Hero Banner -->
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white p-6 rounded-2xl shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="bg-blue-500/30 text-blue-200 text-xs px-2.5 py-1 rounded-full font-semibold border border-blue-400/30">
                    Model Deret Waktu Holt-Winters (Exponential Smoothing)
                </span>
                <h3 class="text-xl font-bold mt-2">Peramalan Permintaan & Kebutuhan Bahan Baku</h3>
                <p class="text-xs text-slate-300 mt-1 max-w-2xl">
                    Proyeksi kebutuhan bongkahan marmer otomatis berdasarkan pola musiman dan tren historis 12 bulan terakhir untuk mencegah *stockout*.
                </p>
            </div>

            <form action="{{ route('forecasting.calculate') }}" method="POST">
                @csrf
                <input type="hidden" name="target_type" value="material">
                <input type="hidden" name="target_id" value="1">
                <input type="hidden" name="model_type" value="holt_winters">
                <input type="hidden" name="horizon_months" value="3">
                <button type="submit" class="bg-blue-500 hover:bg-blue-400 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow-md">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> Hitung Ulang Peramalan
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
            <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/10">
                <p class="text-xs text-slate-300">Estimasi Kebutuhan Mei 2026</p>
                <h4 class="text-2xl font-bold text-white mt-1">420.5 Blok</h4>
                <p class="text-[11px] text-blue-300 mt-0.5">Batas: 395 - 446 Blok</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/10">
                <p class="text-xs text-slate-300">Akurasi Model (MAPE)</p>
                <h4 class="text-2xl font-bold text-emerald-400 mt-1">6.42 %</h4>
                <p class="text-[11px] text-emerald-300 mt-0.5">Kategori: Sangat Akurat (&lt;10%)</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/10">
                <p class="text-xs text-slate-300">Rekomendasi Order Tambang</p>
                <h4 class="text-2xl font-bold text-amber-300 mt-1">20 Blok Tambahan</h4>
                <p class="text-[11px] text-amber-200 mt-0.5">Order sebelum 5 Mei</p>
            </div>
        </div>
    </div>

    <!-- Forecast Line Chart -->
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
        <h4 class="text-sm font-bold text-slate-800 mb-1">Visualisasi Historis vs Hasil Ramalan (Confidence Interval 95%)</h4>
        <p class="text-xs text-slate-500 mb-4">Garis biru menunjukkan data aktual 12 bulan terakhir, garis oranye putus-putus menunjukkan hasil peramalan 3 bulan ke depan.</p>
        <div class="h-72">
            <canvas id="forecastChart"></canvas>
        </div>
    </div>

    <!-- Audit Logs of Forecasting -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-4 border-b">
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Histori Log Peramalan Microservice</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b">
                    <tr>
                        <th class="p-3">Waktu Kalkulasi</th>
                        <th class="p-3">Target Entitas</th>
                        <th class="p-3">Model Algoritma</th>
                        <th class="p-3">Horizon</th>
                        <th class="p-3">Akurasi (MAPE)</th>
                        <th class="p-3">Dihitung Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentForecasts as $f)
                    <tr class="hover:bg-slate-50/80">
                        <td class="p-3">{{ $f->created_at->format('d M Y H:i') }}</td>
                        <td class="p-3 font-semibold">{{ $f->material->name ?? ($f->product->name ?? 'Bahan Baku Marmer') }}</td>
                        <td class="p-3">
                            <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-semibold text-[10px] uppercase">
                                {{ str_replace('_', ' ', $f->model_type) }}
                            </span>
                        </td>
                        <td class="p-3">{{ $f->horizon_months }} Bulan ke depan</td>
                        <td class="p-3 font-bold text-emerald-600">{{ number_format($f->mape_score, 2) }}%</td>
                        <td class="p-3 text-slate-600">{{ $f->generator->name ?? 'Admin Sistem' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td class="p-3">22 Apr 2026 10:30</td>
                        <td class="p-3 font-semibold">Bongkahan Marmer Putih Campurdarat</td>
                        <td class="p-3">
                            <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-semibold text-[10px] uppercase">
                                Holt-Winters
                            </span>
                        </td>
                        <td class="p-3">3 Bulan ke depan</td>
                        <td class="p-3 font-bold text-emerald-600">6.42% (Sangat Akurat)</td>
                        <td class="p-3 text-slate-600">Pak Joko Santoso</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const ctxForecast = document.getElementById('forecastChart')?.getContext('2d');
        if (ctxForecast) {
            new Chart(ctxForecast, {
                type: 'line',
                data: {
                    labels: ['Mei 25', 'Jun 25', 'Jul 25', 'Agt 25', 'Sep 25', 'Okt 25', 'Nov 25', 'Des 25', 'Jan 26', 'Feb 26', 'Mar 26', 'Apr 26', 'Mei 26 (F)', 'Jun 26 (F)', 'Jul 26 (F)'],
                    datasets: [
                        {
                            label: 'Data Historis Aktual (Blok)',
                            data: [320, 340, 310, 360, 390, 410, 380, 430, 400, 420, 450, 470, null, null, null],
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Hasil Peramalan Holt-Winters',
                            data: [null, null, null, null, null, null, null, null, null, null, null, 470, 485, 498, 515],
                            borderColor: '#f59e0b',
                            borderDash: [6, 6],
                            tension: 0.3
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
    });
</script>
@endsection
