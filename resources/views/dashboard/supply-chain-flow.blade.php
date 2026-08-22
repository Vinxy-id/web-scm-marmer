@extends('layouts.app')

@section('title', 'Alur Rantai Pasok Marmer (8 Tahap)')
@section('page-title', 'Diagram Alur Rantai Pasok Marmer (8 Tahap)')
@section('page-subtitle', 'Visualisasi End-to-End Aliran Material & Nilai Tambah IKM Tulungagung')

@section('content')
<div class="space-y-6">

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        <div>
            <h3 class="text-base font-bold text-slate-800">Aliran Rantai Pasok Batuan Alam Hulu - Hilir</h3>
            <p class="text-xs text-slate-500 mt-1">Studi empiris UD Cahaya Onix & UD Putra Abadi: Pemetaan aliran bahan dari penambang hingga pengiriman ke galeri buyer.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Tahap 1 -->
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">TAHAP 1</span>
                    <i data-lucide="pickaxe" class="w-5 h-5 text-blue-600"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-800">Penambangan Batuan</h4>
                <p class="text-xs text-slate-600">Pemasok dari Gunung Besole, Campurdarat, dan bantaran Kali Song Tulungagung.</p>
                <div class="text-[11px] text-slate-500 pt-2 border-t">
                    <span>Lead time order: <b>1-3 hari</b></span>
                </div>
            </div>

            <!-- Tahap 2 -->
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded">TAHAP 2</span>
                    <i data-lucide="boxes" class="w-5 h-5 text-amber-600"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-800">Gudang Bahan Baku</h4>
                <p class="text-xs text-slate-600">Penyimpanan bongkahan batu alam berdasarkan grade, jenis, dan nomor blok.</p>
                <div class="text-[11px] text-slate-500 pt-2 border-t">
                    <span>Stok minimum: <b>5-15 Blok</b></span>
                </div>
            </div>

            <!-- Tahap 3 -->
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">TAHAP 3</span>
                    <i data-lucide="scissors" class="w-5 h-5 text-indigo-600"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-800">Pemotongan Slep</h4>
                <p class="text-xs text-slate-600">Membelah bongkahan batu besar menjadi ukuran kubus wastafel 40-50 cm.</p>
                <div class="text-[11px] text-slate-500 pt-2 border-t">
                    <span>Durasi rata-rata: <b>60 menit/blok</b></span>
                </div>
            </div>

            <!-- Tahap 4 -->
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">TAHAP 4</span>
                    <i data-lucide="disc" class="w-5 h-5 text-blue-600"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-800">Pembubutan Bentuk</h4>
                <p class="text-xs text-slate-600">7 unit mesin bubut berputar membentuk mangkok, oval, atau kotak wastafel.</p>
                <div class="text-[11px] text-slate-500 pt-2 border-t">
                    <span>Kapasitas: <b>14 unit/hari</b></span>
                </div>
            </div>

            <!-- Tahap 5 -->
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">TAHAP 5</span>
                    <i data-lucide="scan-search" class="w-5 h-5 text-indigo-600"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-800">QC Tahap 1 (Bentuk Mentah)</h4>
                <p class="text-xs text-slate-600">Pemeriksaan serat alam retak rambut & penambalan resin bening.</p>
                <div class="text-[11px] text-slate-500 pt-2 border-t">
                    <span>Mencegah rework lanjut: <b>Efisiensi 85%</b></span>
                </div>
            </div>

            <!-- Tahap 6 -->
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">TAHAP 6</span>
                    <i data-lucide="sparkles" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-800">Finishing Poles Hi-Glossy</h4>
                <p class="text-xs text-slate-600">Pengamplasan bertingkat (#100 s.d #2000) dan pengilapan chemical wax.</p>
                <div class="text-[11px] text-slate-500 pt-2 border-t">
                    <span>Kilau: <b>Hi-Glossy Translucent</b></span>
                </div>
            </div>

            <!-- Tahap 7 -->
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded">TAHAP 7</span>
                    <i data-lucide="shield-check" class="w-5 h-5 text-green-600"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-800">QC Tahap 2 (Akhir & Afur)</h4>
                <p class="text-xs text-slate-600">Pengujian dimensi lubang pipa afur pembuangan dan kelicinan permukaan.</p>
                <div class="text-[11px] text-slate-500 pt-2 border-t">
                    <span>Standar lolos: <b>100% Bebas Cacat</b></span>
                </div>
            </div>

            <!-- Tahap 8 -->
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded">TAHAP 8</span>
                    <i data-lucide="truck" class="w-5 h-5 text-purple-600"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-800">Distribusi & Packing Kayu</h4>
                <p class="text-xs text-slate-600">Pengepakan krat kayu solid dan pengiriman via ekspedisi kargo.</p>
                <div class="text-[11px] text-slate-500 pt-2 border-t">
                    <span>Destinasi: <b>Surabaya & Bali Export</b></span>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
