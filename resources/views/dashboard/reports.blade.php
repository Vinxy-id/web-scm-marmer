@extends('layouts.app')

@section('title', 'Laporan & Efisiensi Rantai Pasok')
@section('page-title', 'Laporan Analitik & Efisiensi Rantai Pasok')
@section('page-subtitle', 'Evaluasi Kinerja Pengadaan, Produksi, dan Waste Reduction')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase">Process Cycle Efficiency (PCE)</p>
            <h3 class="text-2xl font-bold text-blue-600 mt-1">64.58 %</h3>
            <p class="text-[11px] text-slate-500 mt-1">Target pasca-SCM: > 75%</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase">Waste Handling Reduksi</p>
            <h3 class="text-2xl font-bold text-emerald-600 mt-1">390 mnt/mgg</h3>
            <p class="text-[11px] text-slate-500 mt-1">Penghematan waktu handling residu</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase">Rasio Rework QC Tahap 1</p>
            <h3 class="text-2xl font-bold text-amber-600 mt-1">14.28 %</h3>
            <p class="text-[11px] text-slate-500 mt-1">Tertangani di tahap mentah</p>
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
        <h4 class="text-sm font-bold text-slate-800 mb-3">Tabel Mutasi Stok Tahunan (Per Bulan)</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b">
                    <tr>
                        <th class="p-3">Bulan</th>
                        <th class="p-3">Total Material Masuk (Blok)</th>
                        <th class="p-3">Total Material Keluar Produksi (Blok)</th>
                        <th class="p-3">Net Aliran Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($monthlyTransactions as $row)
                    <tr>
                        <td class="p-3 font-semibold">Bulan ke-{{ $row->month }}</td>
                        <td class="p-3 text-emerald-600 font-bold">+{{ number_format($row->total_in, 0, ',', '.') }}</td>
                        <td class="p-3 text-red-600 font-bold">-{{ number_format($row->total_out, 0, ',', '.') }}</td>
                        <td class="p-3 font-bold text-slate-800">{{ number_format($row->total_in - $row->total_out, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td class="p-3 font-semibold">April 2026 (Aktual)</td>
                        <td class="p-3 text-emerald-600 font-bold">+48</td>
                        <td class="p-3 text-red-600 font-bold">-42</td>
                        <td class="p-3 font-bold text-slate-800">+6</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
