@extends('layouts.app')

@section('title', 'Hilirisasi Residu & Limbah Marmer')
@section('page-title', 'Hilirisasi Residu & Pengelolaan Limbah Marmer')
@section('page-subtitle', 'Pencatatan Potongan Layak Cladding/Stepping Stone & Residu Bubut (UD Putra Abadi)')

@section('topbar-actions')
    <button onclick="document.getElementById('modal-add-waste').classList.remove('hidden')" class="bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 shadow-sm transition">
        <i data-lucide="plus" class="w-4 h-4"></i> Catat Residu Baru
    </button>
@endsection

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Residu Layak Cladding / Stepping</p>
                <h3 class="text-2xl font-bold text-teal-600 mt-1">{{ number_format($totalCladdingWaste, 1) }} Kg</h3>
                <p class="text-[11px] text-slate-500 mt-1">Dapat didaur ulang menjadi produk bernilai</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                <i data-lucide="recycle" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Limbah Lumpur Bubut (Sludge)</p>
                <h3 class="text-2xl font-bold text-slate-700 mt-1">{{ number_format($totalSludgeWaste, 1) }} Kg</h3>
                <p class="text-[11px] text-slate-500 mt-1">Dialirkan ke bak sedimentasi</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                <i data-lucide="trash-2" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Waste Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b">
                    <tr>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Batch SPK</th>
                        <th class="p-3">Jenis Residu</th>
                        <th class="p-3">Berat (Kg)</th>
                        <th class="p-3">Rencana Penggunaan</th>
                        <th class="p-3">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($wasteLogs as $log)
                    <tr class="hover:bg-slate-50/80">
                        <td class="p-3">{{ $log->logged_at->format('d M Y') }}</td>
                        <td class="p-3 font-mono font-bold text-blue-600">{{ $log->workOrder->spk_number ?? '-' }}</td>
                        <td class="p-3">
                            <span class="bg-teal-100 text-teal-800 px-2 py-0.5 rounded font-semibold text-[10px]">
                                {{ str_replace('_', ' ', $log->waste_type) }}
                            </span>
                        </td>
                        <td class="p-3 font-bold text-slate-800">{{ number_format($log->weight_kg, 2) }} Kg</td>
                        <td class="p-3">
                            <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-[10px]">
                                {{ str_replace('_', ' ', $log->reuse_status) }}
                            </span>
                        </td>
                        <td class="p-3 text-slate-500">{{ $log->notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-slate-400">Belum ada pencatatan residu limbah.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-t">
            {{ $wasteLogs->links() }}
        </div>
    </div>

</div>

<!-- MODAL ADD WASTE -->
<div id="modal-add-waste" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl border">
        <div class="flex items-center justify-between border-b pb-3">
            <h4 class="text-sm font-bold text-slate-800">Catat Residu / Limbah Marmer</h4>
            <button onclick="document.getElementById('modal-add-waste').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('waste.store') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="text-[11px] font-bold text-slate-600">Pilih Batch SPK</label>
                <select name="work_order_id" required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                    @foreach($workOrders as $wo)
                    <option value="{{ $wo->id }}">{{ $wo->spk_number }} - {{ $wo->product->name ?? 'Produk' }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Jenis Residu</label>
                    <select name="waste_type" required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                        <option value="sisa_layak_cladding">Sisa Layak Cladding</option>
                        <option value="serbuk_bubut_sludge">Serbuk Bubut / Sludge</option>
                        <option value="bongkahan_urukan">Bongkahan Urukan</option>
                    </select>
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Berat (Kg)</label>
                    <input type="number" step="0.1" name="weight_kg" value="25.0" required class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
            </div>

            <div>
                <label class="text-[11px] font-bold text-slate-600">Rencana Pengelolaan</label>
                <select name="reuse_status" required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                    <option value="disimpan_daur_ulang">Disimpan untuk Daur Ulang Cladding</option>
                    <option value="dijual_ke_pihak3">Dijual ke Pihak Ketiga</option>
                    <option value="dibuang_ke_urukan">Dibuang ke Tempat Urukan</option>
                </select>
            </div>

            <div>
                <label class="text-[11px] font-bold text-slate-600">Catatan Tambahan</label>
                <textarea name="notes" rows="2" placeholder="Contoh: Potongan tebal 4cm sisa slep" class="w-full text-xs mt-1 border rounded-lg p-2"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <button type="button" onclick="document.getElementById('modal-add-waste').classList.add('hidden')" class="text-xs px-4 py-2 border rounded-lg text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="text-xs px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg">Simpan Residu</button>
            </div>
        </form>
    </div>
</div>
@endsection
