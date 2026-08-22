@extends('layouts.app')

@section('title', 'Quality Control (QC) Dua Tahap')
@section('page-title', 'Pengendalian Kualitas QC Dua Tahap')
@section('page-subtitle', 'Standar Inspeksi Serat Awal & Finishing Kilau Hi-Glossy')

@section('content')
<div class="space-y-6">

    <!-- Form QC Form -->
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-800">Form Input Hasil Inspeksi Quality Control</h3>
                <p class="text-xs text-slate-500">Pilih batch SPK untuk mencatat jumlah lolos (pass) dan pengerjaan ulang (rework).</p>
            </div>
        </div>

        <form action="{{ route('qc.inspect') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Pilih Batch SPK</label>
                    <select name="work_order_id" required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                        @foreach($activeWorkOrders as $wo)
                        <option value="{{ $wo->id }}">{{ $wo->spk_number }} - {{ $wo->product->name ?? 'Produk' }} (Target: {{ $wo->target_quantity }} Unit)</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-600">Tahap QC</label>
                    <select name="stage" required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white font-semibold">
                        <option value="qc1_raw_shape">Tahap 1: Bentuk Mentah (Cek Retak Serat)</option>
                        <option value="qc2_final_polish">Tahap 2: Akhir & Poles (Uji Afur & Kilau)</option>
                    </select>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-600">Jumlah Diperiksa (Unit)</label>
                    <input type="number" name="inspected_quantity" value="14" min="1" required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-600">Jumlah Lolos (Pass)</label>
                    <input type="number" name="pass_quantity" value="12" min="0" required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white text-emerald-600 font-bold">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Perlu Tambal Resin (Rework)</label>
                    <input type="number" name="rework_quantity" value="2" min="0" required class="w-full text-xs mt-1 border rounded-lg p-2 text-amber-600 font-bold">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Cacat / Pecah Total (Scrap)</label>
                    <input type="number" name="scrap_quantity" value="0" min="0" required class="w-full text-xs mt-1 border rounded-lg p-2 text-red-600 font-bold">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Tindakan Perbaikan</label>
                    <input type="text" name="rework_action" placeholder="Penambalan resin bening + poles ulang" class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-lg flex items-center gap-1.5 shadow-sm">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Simpan Hasil Inspeksi QC
                </button>
            </div>
        </form>
    </div>

    <!-- QC Logs Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-4 border-b">
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Histori Pemeriksaan QC Terbaru</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b">
                    <tr>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">No. SPK</th>
                        <th class="p-3">Tahap QC</th>
                        <th class="p-3">Diperiksa</th>
                        <th class="p-3">Pass (Lolos)</th>
                        <th class="p-3">Rework (Tambal)</th>
                        <th class="p-3">Inspektor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentQcLogs as $log)
                    <tr class="hover:bg-slate-50/80">
                        <td class="p-3">{{ $log->inspection_date->format('d M Y') }}</td>
                        <td class="p-3 font-mono font-bold text-blue-600">{{ $log->workOrder->spk_number ?? '-' }}</td>
                        <td class="p-3">
                            <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-semibold text-[10px]">
                                {{ $log->stage === 'qc1_raw_shape' ? 'QC 1 (Mentah)' : 'QC 2 (Poles Akhir)' }}
                            </span>
                        </td>
                        <td class="p-3 font-semibold">{{ $log->inspected_quantity }} Unit</td>
                        <td class="p-3 font-bold text-emerald-600">{{ $log->pass_quantity }} Unit</td>
                        <td class="p-3 font-bold text-amber-600">{{ $log->rework_quantity }} Unit</td>
                        <td class="p-3 text-slate-600">{{ $log->inspector->name ?? 'Inspektor' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-slate-400">Belum ada catatan inspeksi QC.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
