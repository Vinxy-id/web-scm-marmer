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

        <form action="{{ route('qc.inspect') }}" method="POST" class="space-y-4" id="qc-form">
            @csrf
            <!-- MOB-04 & MOB-12 SOLVED: Responsive form grids and full-width mobile button -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Pilih Batch SPK</label>
                    <select name="work_order_id" id="qc-work-order-select" required onchange="handleWorkOrderChange(this)" class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                        <option value="">-- Pilih Batch SPK --</option>
                        @foreach($activeWorkOrders as $wo)
                        @php
                            $hasQc1 = $wo->qcLogs->where('stage', 'qc1_raw_shape')->count() > 0;
                            $hasQc2 = $wo->qcLogs->where('stage', 'qc2_final_polish')->count() > 0;
                        @endphp
                        <option value="{{ $wo->id }}" 
                                data-target="{{ $wo->target_quantity }}" 
                                data-product="{{ $wo->product->name ?? 'Produk' }}" 
                                data-has-qc1="{{ $hasQc1 ? '1' : '0' }}"
                                data-has-qc2="{{ $hasQc2 ? '1' : '0' }}"
                                data-status="{{ $wo->status }}">
                            {{ $wo->spk_number }} - {{ $wo->product->name ?? 'Produk' }} (Target: {{ $wo->target_quantity }} Unit) [{{ $hasQc1 ? 'Sudah QC1' : 'Belum QC1' }}]
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-600">Tahap QC</label>
                    <select name="stage" id="qc-stage-select" required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white font-semibold">
                        <option value="qc1_raw_shape">Tahap 1: Bentuk Mentah (Cek Retak Serat)</option>
                        <option value="qc2_final_polish">Tahap 2: Akhir & Poles (Uji Afur & Kilau)</option>
                    </select>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-600">Jumlah Diperiksa (Unit)</label>
                    <input type="number" id="qc-inspected-qty" name="inspected_quantity" value="" placeholder="Contoh: 10" min="1" required oninput="recalculateQcPass()" class="w-full text-xs mt-1 border rounded-lg p-2 bg-white font-bold text-slate-800">
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-600">Jumlah Lolos (Pass)</label>
                    <input type="number" id="qc-pass-qty" name="pass_quantity" value="" placeholder="0" min="0" required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white text-emerald-600 font-bold">
                </div>
            </div>

            <!-- QC-05 SOLVED: Standar Kategori Cacat & Tindakan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Perlu Tambal Resin (Rework)</label>
                    <input type="number" id="qc-rework-qty" name="rework_quantity" value="0" min="0" required oninput="recalculateQcPass()" class="w-full text-xs mt-1 border rounded-lg p-2 text-amber-600 font-bold">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Cacat / Pecah Total (Scrap)</label>
                    <input type="number" id="qc-scrap-qty" name="scrap_quantity" value="0" min="0" required oninput="recalculateQcPass()" class="w-full text-xs mt-1 border rounded-lg p-2 text-red-600 font-bold">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Jenis Cacat Batuan (Defect)</label>
                    <select name="defect_type" class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                        <option value="">-- Tidak Ada Cacat (Lolos Sempurna) --</option>
                        <option value="retak_serat">Retak Serat Alami Batuan (Crack)</option>
                        <option value="lubang_afur_miring">Lubang Afur Miring / Kurang Presisi</option>
                        <option value="permukaan_kasar">Permukaan Kasar / Gelombang Poles</option>
                        <option value="baret_poles">Baret / Scratching Resin Poles</option>
                        <option value="pecah_bibir">Pecah / Chipping Bibir Wastafel</option>
                        <option value="lainnya">Lainnya (Tulis di Catatan)</option>
                    </select>
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Tindakan Perbaikan</label>
                    <input type="text" name="rework_action" placeholder="Penambalan resin bening + poles ulang" class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="submit" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-6 py-2.5 min-h-[38px] rounded-xl flex items-center justify-center gap-1.5 shadow-sm transition">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Simpan Hasil Inspeksi QC
                </button>
            </div>
        </form>
    </div>

    <!-- QC Logs Table (QC-09 & QC-13 SOLVED) -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm space-y-3">
        <div class="p-4 border-b flex items-center justify-between">
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Histori Pemeriksaan QC Lengkap</h4>
            <span class="text-[11px] text-slate-500 font-medium">Total: {{ $recentQcLogs->total() }} Laporan Inspeksi</span>
        </div>
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b">
                    <tr>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">No. SPK & Produk</th>
                        <th class="p-3">Tahap QC</th>
                        <th class="p-3">Diperiksa</th>
                        <th class="p-3">Pass (Lolos)</th>
                        <th class="p-3">Rework (Tambal)</th>
                        <th class="p-3">Scrap (Pecah)</th>
                        <th class="p-3 hidden md:table-cell">Jenis Cacat</th>
                        <th class="p-3 hidden lg:table-cell">Tindakan / Solusi</th>
                        <th class="p-3 hidden sm:table-cell">Inspektor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentQcLogs as $log)
                    <tr class="hover:bg-slate-50/80">
                        <td class="p-3 font-mono text-slate-500">{{ $log->inspection_date->format('d M Y') }}</td>
                        <td class="p-3">
                            <span class="font-mono font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">{{ $log->workOrder->spk_number ?? '-' }}</span>
                            <span class="block text-[10px] text-slate-500 font-medium mt-0.5">{{ $log->workOrder->product->name ?? '-' }}</span>
                        </td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded font-semibold text-[10px] {{ $log->stage === 'qc1_raw_shape' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                {{ $log->stage === 'qc1_raw_shape' ? 'QC 1 (Mentah)' : 'QC 2 (Poles Akhir)' }}
                            </span>
                        </td>
                        <td class="p-3 font-semibold">{{ $log->inspected_quantity }} Unit</td>
                        <td class="p-3 font-bold text-emerald-600">{{ $log->pass_quantity }} Unit</td>
                        <td class="p-3 font-bold text-amber-600">{{ $log->rework_quantity }} Unit</td>
                        <td class="p-3 font-bold text-red-600">{{ $log->scrap_quantity }} Unit</td>
                        <td class="p-3 text-slate-600 hidden md:table-cell">
                            {{ $log->defect_type ? ucwords(str_replace('_', ' ', $log->defect_type)) : '-' }}
                        </td>
                        <td class="p-3 text-slate-500 text-[11px] max-w-xs truncate hidden lg:table-cell" title="{{ $log->rework_action ?: $log->notes }}">
                            {{ $log->rework_action ?: ($log->notes ?: '-') }}
                        </td>
                        <td class="p-3 text-slate-600 font-medium hidden sm:table-cell">{{ $log->inspector->name ?? 'Inspektor' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="p-4 text-center text-slate-400">Belum ada catatan inspeksi QC.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($recentQcLogs->hasPages())
        <div class="p-3 border-t">
            {{ $recentQcLogs->links() }}
        </div>
        @endif
    </div>

</div>

<script>
function handleWorkOrderChange(selectEl) {
    const selectedOption = selectEl.options[selectEl.selectedIndex];
    if (!selectedOption || !selectedOption.value) return;

    const targetQty = parseInt(selectedOption.getAttribute('data-target'), 10) || 0;
    const hasQc1 = selectedOption.getAttribute('data-has-qc1') === '1';
    const stageSelect = document.getElementById('qc-stage-select');

    if (hasQc1) {
        stageSelect.value = 'qc2_final_polish';
    } else {
        stageSelect.value = 'qc1_raw_shape';
    }

    document.getElementById('qc-inspected-qty').value = targetQty;
    document.getElementById('qc-rework-qty').value = 0;
    document.getElementById('qc-scrap-qty').value = 0;
    document.getElementById('qc-pass-qty').value = targetQty;
}

function recalculateQcPass() {
    const inspected = parseInt(document.getElementById('qc-inspected-qty').value, 10) || 0;
    const rework = parseInt(document.getElementById('qc-rework-qty').value, 10) || 0;
    const scrap = parseInt(document.getElementById('qc-scrap-qty').value, 10) || 0;

    const pass = Math.max(0, inspected - (rework + scrap));
    document.getElementById('qc-pass-qty').value = pass;
}
</script>

</div>
@endsection
