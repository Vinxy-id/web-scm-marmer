@extends('layouts.app')

@section('title', 'WIP Tracking 7 Mesin Bubut')
@section('page-title', 'Tracking Barang dalam Proses (WIP)')
@section('page-subtitle', 'Monitoring Durasi dan Stasiun Kerja 7 Unit Mesin Bubut UD Cahaya Onix')

@section('topbar-actions')
    <a href="{{ route('production.kanban') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 shadow-sm transition">
        <i data-lucide="kanban-square" class="w-4 h-4"></i> Buka Kanban SPK
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- 7 MACHINE STATIONS GRID (UD CAHAYA ONIX) -->
    <div class="bg-white p-4 sm:p-5 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4 pb-3 border-b border-slate-100">
            <div>
                <h4 class="text-sm font-bold text-slate-800">Status 7 Unit Mesin Lantai Produksi</h4>
                <p class="text-xs text-slate-500">Kapasitas pembubutan wastafel batu marmer, onyx, dan batu kali</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 text-xs text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200 font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    7/7 Mesin Siap Beroperasi
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
            <!-- Mesin Slep -->
            <div class="p-3 rounded-lg border border-blue-200 bg-blue-50/50 flex flex-col justify-between space-y-2">
                <div>
                    <span class="text-[10px] font-bold uppercase text-blue-600 bg-blue-100 px-1.5 py-0.5 rounded">Stasiun 1</span>
                    <h5 class="text-xs font-bold text-slate-800 mt-1">Mesin Slep</h5>
                    <p class="text-[10px] text-slate-500">Pak Slamet</p>
                </div>
                <span class="text-[10px] text-blue-700 font-semibold flex items-center gap-1">
                    <i data-lucide="activity" class="w-3 h-3"></i> Potong Blok
                </span>
            </div>

            <!-- Mesin Bubut 1 -->
            <div class="p-3 rounded-lg border border-indigo-200 bg-indigo-50/50 flex flex-col justify-between space-y-2">
                <div>
                    <span class="text-[10px] font-bold uppercase text-indigo-600 bg-indigo-100 px-1.5 py-0.5 rounded">Bubut 1</span>
                    <h5 class="text-xs font-bold text-slate-800 mt-1">Bentuk Kasar</h5>
                    <p class="text-[10px] text-slate-500">Pak Roni</p>
                </div>
                <span class="text-[10px] text-indigo-700 font-semibold flex items-center gap-1">
                    <i data-lucide="disc" class="w-3 h-3"></i> Aktif
                </span>
            </div>

            <!-- Mesin Bubut 2 -->
            <div class="p-3 rounded-lg border border-indigo-200 bg-indigo-50/50 flex flex-col justify-between space-y-2">
                <div>
                    <span class="text-[10px] font-bold uppercase text-indigo-600 bg-indigo-100 px-1.5 py-0.5 rounded">Bubut 2</span>
                    <h5 class="text-xs font-bold text-slate-800 mt-1">Lubang Afur</h5>
                    <p class="text-[10px] text-slate-500">Pak Agus</p>
                </div>
                <span class="text-[10px] text-indigo-700 font-semibold flex items-center gap-1">
                    <i data-lucide="disc" class="w-3 h-3"></i> Aktif
                </span>
            </div>

            <!-- Mesin Bubut 3 -->
            <div class="p-3 rounded-lg border border-indigo-200 bg-indigo-50/50 flex flex-col justify-between space-y-2">
                <div>
                    <span class="text-[10px] font-bold uppercase text-indigo-600 bg-indigo-100 px-1.5 py-0.5 rounded">Bubut 3</span>
                    <h5 class="text-xs font-bold text-slate-800 mt-1">Bibir Halus</h5>
                    <p class="text-[10px] text-slate-500">Pak Yanto</p>
                </div>
                <span class="text-[10px] text-indigo-700 font-semibold flex items-center gap-1">
                    <i data-lucide="disc" class="w-3 h-3"></i> Aktif
                </span>
            </div>

            <!-- Mesin Bubut 4 -->
            <div class="p-3 rounded-lg border border-indigo-200 bg-indigo-50/50 flex flex-col justify-between space-y-2">
                <div>
                    <span class="text-[10px] font-bold uppercase text-indigo-600 bg-indigo-100 px-1.5 py-0.5 rounded">Bubut 4</span>
                    <h5 class="text-xs font-bold text-slate-800 mt-1">Batu Kali</h5>
                    <p class="text-[10px] text-slate-500">Pak Eko</p>
                </div>
                <span class="text-[10px] text-indigo-700 font-semibold flex items-center gap-1">
                    <i data-lucide="disc" class="w-3 h-3"></i> Aktif
                </span>
            </div>

            <!-- Mesin Bubut Poles 1 -->
            <div class="p-3 rounded-lg border border-emerald-200 bg-emerald-50/50 flex flex-col justify-between space-y-2">
                <div>
                    <span class="text-[10px] font-bold uppercase text-emerald-600 bg-emerald-100 px-1.5 py-0.5 rounded">Poles 1</span>
                    <h5 class="text-xs font-bold text-slate-800 mt-1">Amplas Halus</h5>
                    <p class="text-[10px] text-slate-500">Pak Budi</p>
                </div>
                <span class="text-[10px] text-emerald-700 font-semibold flex items-center gap-1">
                    <i data-lucide="sparkles" class="w-3 h-3"></i> Poles Wet
                </span>
            </div>

            <!-- Mesin Bubut Poles 2 -->
            <div class="p-3 rounded-lg border border-emerald-200 bg-emerald-50/50 flex flex-col justify-between space-y-2">
                <div>
                    <span class="text-[10px] font-bold uppercase text-emerald-600 bg-emerald-100 px-1.5 py-0.5 rounded">Poles 2</span>
                    <h5 class="text-xs font-bold text-slate-800 mt-1">Finishing Glossy</h5>
                    <p class="text-[10px] text-slate-500">Pak Dani</p>
                </div>
                <span class="text-[10px] text-emerald-700 font-semibold flex items-center gap-1">
                    <i data-lucide="sparkles" class="w-3 h-3"></i> Wax Poles
                </span>
            </div>
        </div>
    </div>

    <!-- WIP BATCHES TABLE WITH ACTION BUTTONS -->
    <div class="bg-white p-4 sm:p-5 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <h4 class="text-sm font-bold text-slate-800">Daftar Batch Sedang Dikerjakan di Lantai Produksi</h4>
                <p class="text-xs text-slate-500">Klik tombol Update Progres untuk memperbarui hasil bubut & memindahkan stasiun kerja</p>
            </div>
        </div>
        
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-3">No. SPK</th>
                        <th class="p-3">Produk Wastafel</th>
                        <th class="p-3">Target & Selesai</th>
                        <th class="p-3">Stasiun Kerja</th>
                        <th class="p-3">Persentase Progres</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-center">Aksi / Update</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($workOrders as $wo)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-3 font-mono font-bold text-blue-600">
                            <span class="bg-blue-50 px-2 py-0.5 rounded border border-blue-100">{{ $wo->spk_number }}</span>
                        </td>
                        <td class="p-3 font-semibold text-slate-800">
                            {{ $wo->product->name ?? '-' }}
                            <span class="block text-[10px] text-slate-400 font-normal">Pemesan: {{ $wo->customer->name ?? 'Stok Gudang' }}</span>
                        </td>
                        <td class="p-3">
                            <span class="font-bold text-slate-800">{{ $wo->completed_quantity }} / {{ $wo->target_quantity }} Unit</span>
                            @if($wo->scrap_quantity > 0)
                            <span class="block text-[10px] text-amber-600 font-medium">Tambal: {{ $wo->scrap_quantity }} Unit</span>
                            @endif
                        </td>
                        <td class="p-3">
                            <span class="bg-indigo-50 text-indigo-700 border border-indigo-200 px-2 py-0.5 rounded font-semibold text-[10px]">
                                Mesin Bubut 1-4
                            </span>
                        </td>
                        <td class="p-3 w-44">
                            @php
                                $pct = $wo->target_quantity > 0 ? round(($wo->completed_quantity / $wo->target_quantity) * 100) : 0;
                            @endphp
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-blue-600 h-full rounded-full transition-all duration-300" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-[10px] text-slate-500 mt-1 block font-medium">{{ $pct }}% selesai</span>
                        </td>
                        <td class="p-3">
                            @if($wo->status === 'qc_phase')
                                <span class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full font-semibold text-[10px] uppercase">
                                    Inspeksi QC
                                </span>
                            @elseif($wo->status === 'in_progress')
                                <span class="bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full font-semibold text-[10px] uppercase">
                                    Sedang Bubut
                                </span>
                            @else
                                <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded-full font-semibold text-[10px] uppercase">
                                    {{ str_replace('_', ' ', $wo->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="openWipModal({{ json_encode($wo) }})" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-2.5 py-1.5 rounded-lg flex items-center gap-1 shadow-sm transition">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Update Progres
                                </button>
                                @if($wo->status !== 'qc_phase')
                                <form action="{{ route('production.work-order.update-status', $wo->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="qc_phase">
                                    <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-300 text-xs font-semibold px-2.5 py-1.5 rounded-lg flex items-center gap-1 transition" title="Kirim ke Inspeksi QC">
                                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> Kirim QC
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('qc.index') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-2.5 py-1.5 rounded-lg flex items-center gap-1 shadow-sm transition">
                                    <i data-lucide="clipboard-check" class="w-3.5 h-3.5"></i> Buka QC
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-slate-400">Tidak ada batch pengerjaan aktif saat ini. Silakan buat SPK baru di menu Kanban.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL UPDATE PROGRES WIP -->
<div id="modal-update-wip" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 border border-slate-100">
        <div class="flex items-center justify-between pb-3 border-b">
            <div>
                <h3 class="text-base font-bold text-slate-800">Update Progres & Output Mesin Bubut</h3>
                <p id="modal-wip-spk-title" class="text-xs text-blue-600 font-mono font-bold mt-0.5">SPK/---</p>
            </div>
            <button onclick="document.getElementById('modal-update-wip').classList.add('hidden')" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="form-update-wip" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Produk Wastafel</label>
                <input type="text" id="modal-wip-product-name" readonly class="w-full text-xs bg-slate-100 border border-slate-200 rounded-lg p-2.5 text-slate-700 font-medium">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Target Total</label>
                    <input type="number" id="modal-wip-target-qty" readonly class="w-full text-xs bg-slate-100 border border-slate-200 rounded-lg p-2.5 text-slate-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Unit Selesai Saat Ini <span class="text-red-500">*</span></label>
                    <input type="number" name="completed_quantity" id="modal-wip-completed-qty" required min="0" class="w-full text-xs border rounded-lg p-2.5 focus:outline-blue-500 font-bold text-blue-700">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Unit Tambal Resin / Rework</label>
                    <input type="number" name="scrap_quantity" id="modal-wip-scrap-qty" min="0" class="w-full text-xs border rounded-lg p-2.5 focus:outline-blue-500 text-amber-700 font-medium">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status Pengerjaan <span class="text-red-500">*</span></label>
                    <select name="status" id="modal-wip-status" required class="w-full text-xs border rounded-lg p-2.5 bg-white focus:outline-blue-500">
                        <option value="in_progress">Sedang Dikerjakan (In Progress)</option>
                        <option value="qc_phase">Kirim ke Inspeksi QC (QC Phase)</option>
                        <option value="completed">Selesai Produksi (Ready Stock)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Stasiun Mesin Pengerjaan</label>
                <select name="machine_station" class="w-full text-xs border rounded-lg p-2.5 bg-white focus:outline-blue-500">
                    <option value="Mesin Slep Utama">Stasiun 1: Mesin Slep Utama (Potong Blok)</option>
                    <option value="Mesin Bubut 1-4" selected>Stasiun 2: Mesin Bubut 1-4 (Bentuk Kasar & Lubang Afur)</option>
                    <option value="Mesin Bubut Poles 1-2">Stasiun 3: Mesin Bubut Poles (Penghalusan Glossy)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Operator / Mandor</label>
                <textarea name="notes" id="modal-wip-notes" rows="2" placeholder="Catatan kondisi batu, serat alami, atau kendala bubut..." class="w-full text-xs border rounded-lg p-2.5 focus:outline-blue-500"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t">
                <button type="button" onclick="document.getElementById('modal-update-wip').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center gap-1.5 shadow-sm">
                    <i data-lucide="check" class="w-4 h-4"></i> Simpan Progres
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    function openWipModal(wo) {
        document.getElementById('modal-wip-spk-title').innerText = wo.spk_number + ' - ' + (wo.product ? wo.product.name : '');
        document.getElementById('modal-wip-product-name').value = (wo.product ? wo.product.name : '-');
        document.getElementById('modal-wip-target-qty').value = wo.target_quantity;
        document.getElementById('modal-wip-completed-qty').value = wo.completed_quantity;
        document.getElementById('modal-wip-scrap-qty').value = wo.scrap_quantity || 0;
        document.getElementById('modal-wip-status').value = wo.status;
        document.getElementById('modal-wip-notes').value = wo.notes || '';

        // Set form action URL
        const form = document.getElementById('form-update-wip');
        form.action = '/production/work-order/' + wo.id + '/wip-progress';

        document.getElementById('modal-update-wip').classList.remove('hidden');
    }
</script>
@endsection
@endsection
