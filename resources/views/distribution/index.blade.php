@extends('layouts.app')

@section('title', 'Distribusi & Packing Kayu')
@section('page-title', 'Distribusi & Checklist Packing Kayu')
@section('page-subtitle', 'Persetujuan ACC Pengiriman, Manajemen Surat Jalan, dan Verifikasi Packing Peti Krat Kayu')

@section('topbar-actions')
    <button onclick="document.getElementById('modal-add-shipment').classList.remove('hidden')" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 shadow-sm transition">
        <i data-lucide="plus" class="w-4 h-4"></i> Terbitkan Surat Jalan
    </button>
@endsection

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-purple-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 font-medium">Antrean Menunggu ACC</p>
                <h3 class="text-2xl font-black text-purple-700 mt-1">{{ $stats['pending_approval'] ?? 0 }} <span class="text-xs font-normal text-slate-400">SPK</span></h3>
            </div>
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-amber-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 font-medium">Packing & Siap Angkut</p>
                <h3 class="text-2xl font-black text-amber-600 mt-1">{{ $stats['packed'] ?? 0 }} <span class="text-xs font-normal text-slate-400">Peti</span></h3>
            </div>
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <i data-lucide="package-check" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-blue-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 font-medium">Dalam Perjalanan Kargo</p>
                <h3 class="text-2xl font-black text-blue-600 mt-1">{{ $stats['in_transit'] ?? 0 }} <span class="text-xs font-normal text-slate-400">Truk</span></h3>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <i data-lucide="truck" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-emerald-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 font-medium">Telah Diterima Buyer</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $stats['delivered'] ?? 0 }} <span class="text-xs font-normal text-slate-400">Selesai</span></h3>
            </div>
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <i data-lucide="check-circle-2" class="w-5 h-5"></i>
            </div>
        </div>
    </div>

    <!-- ANTREAN SPK SIAP KIRIM (MENUNGGU ACC SURAT JALAN) -->
    @if(isset($pendingShipmentOrders) && $pendingShipmentOrders->count() > 0)
    <div class="bg-gradient-to-r from-purple-900 to-indigo-900 rounded-2xl p-5 text-white shadow-md">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="p-1.5 bg-purple-500/30 rounded-lg"><i data-lucide="package-check" class="w-4 h-4 text-purple-200"></i></span>
                    <h3 class="text-sm font-bold tracking-tight">Antrean SPK Selesai Produksi (Menunggu ACC Pengiriman)</h3>
                </div>
                <p class="text-xs text-purple-200 mt-0.5">Produk berikut telah lulus QC Akhir dan siap dikemas peti kayu solid untuk diterbitkan Surat Jalan.</p>
            </div>
            <span class="text-xs font-semibold bg-purple-500/40 text-purple-100 px-2.5 py-1 rounded-full w-fit">
                {{ $pendingShipmentOrders->count() }} Batch SPK Siap Kirim
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($pendingShipmentOrders as $pWo)
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-3.5 border border-white/15 space-y-2.5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-mono font-bold bg-white/20 text-white px-2 py-0.5 rounded">{{ $pWo->spk_number }}</span>
                        <span class="text-[10px] text-purple-200">{{ $pWo->due_date ? $pWo->due_date->format('d M Y') : '-' }}</span>
                    </div>
                    <h4 class="text-xs font-bold text-white mt-1.5">{{ $pWo->product->name ?? 'Wastafel' }}</h4>
                    <p class="text-[11px] text-purple-200 flex items-center gap-1 mt-0.5">
                        <i data-lucide="map-pin" class="w-3 h-3 text-purple-300"></i>
                        {{ $pWo->customer->company_name ?? ($pWo->customer->name ?? 'Stok Gudang') }} ({{ $pWo->customer->city ?? 'Tulungagung' }})
                    </p>
                </div>
                <div class="pt-2 border-t border-white/10 flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-300">{{ $pWo->completed_quantity }} Unit</span>
                    <button type="button" onclick="openAccShipmentModal({{ $pWo->id }}, '{{ $pWo->spk_number }}', {{ $pWo->customer_id ?? 'null' }}, '{{ addslashes($pWo->customer->company_name ?? $pWo->customer->name ?? 'Stok Gudang') }}', '{{ addslashes($pWo->product->name ?? '') }}', {{ $pWo->completed_quantity }})" class="text-[11px] font-bold bg-white text-purple-900 hover:bg-purple-50 px-2.5 py-1 rounded-lg transition shadow-sm flex items-center gap-1">
                        <i data-lucide="truck" class="w-3 h-3 text-purple-700"></i> ACC & Buat SJ
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Shipments Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Daftar Surat Jalan & Monitoring Logistik</h4>
                <p class="text-[11px] text-slate-500">Pelacakan pengiriman produk dari bengkel Campurdarat ke pelanggan</p>
            </div>
        </div>
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b">
                    <tr>
                        <th class="p-3">No. Surat Jalan</th>
                        <th class="p-3">Pelanggan / Destinasi</th>
                        <th class="p-3">Item SPK Terkait</th>
                        <!-- MOB-05 SOLVED: Responsive column hiding on mobile -->
                        <th class="p-3 hidden sm:table-cell">Tgl Kirim</th>
                        <th class="p-3 hidden md:table-cell">Ekspedisi & Driver</th>
                        <th class="p-3 hidden lg:table-cell">Checklist Packing Kayu</th>
                        <th class="p-3">Status Logistik</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($shipments as $sh)
                    <tr class="hover:bg-slate-50/80">
                        <td class="p-3">
                            <span class="font-mono font-bold text-purple-700">{{ $sh->shipment_code }}</span>
                            <p class="text-[10px] text-slate-400 sm:hidden">{{ $sh->shipment_date->format('d M Y') }}</p>
                            @if($sh->tracking_number)
                            <p class="text-[10px] text-slate-500 font-mono">Resi: {{ $sh->tracking_number }}</p>
                            @endif
                        </td>
                        <td class="p-3 font-semibold text-slate-800">
                            {{ $sh->customer->company_name ?? $sh->customer->name }}
                            <p class="text-[10px] text-slate-400 font-normal">{{ $sh->customer->city }}</p>
                            <!-- Mobile-only expedition name -->
                            <p class="text-[10px] text-blue-600 md:hidden mt-0.5">
                                {{ $sh->expedition_name ?? 'Kargo Truk' }}
                            </p>
                        </td>
                        <td class="p-3">
                            @if($sh->workOrder)
                            <span class="text-[10px] font-mono font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">{{ $sh->workOrder->spk_number }}</span>
                            <p class="text-[10px] text-slate-600 mt-0.5">{{ $sh->workOrder->product->name ?? 'Wastafel' }} ({{ $sh->workOrder->completed_quantity }} Unit)</p>
                            @else
                            <span class="text-slate-400 text-[10px]">Stok Reguler</span>
                            @endif
                        </td>
                        <td class="p-3 whitespace-nowrap hidden sm:table-cell">{{ $sh->shipment_date->format('d M Y') }}</td>
                        <td class="p-3 text-slate-600 hidden md:table-cell">
                            <span class="font-semibold">{{ $sh->expedition_name ?? 'Kargo Truk Sendiri' }}</span>
                            <p class="text-[10px] text-slate-400">Driver: {{ $sh->driver_name ?? '-' }} ({{ $sh->vehicle_plate ?? '-' }})</p>
                        </td>
                        <td class="p-3 hidden lg:table-cell">
                            @if($sh->packing_verified)
                            <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded font-semibold text-[10px]">
                                <i data-lucide="check" class="w-3 h-3 text-emerald-600"></i> Terverifikasi Solid
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 px-2 py-0.5 rounded font-semibold text-[10px]">
                                Belum Terverifikasi
                            </span>
                            @endif
                        </td>
                        <td class="p-3">
                            @if($sh->delivery_status === 'delivered')
                            <span class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded font-semibold text-[10px] uppercase">
                                TELAH DITERIMA
                            </span>
                            @elseif($sh->delivery_status === 'in_transit')
                            <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-semibold text-[10px] uppercase">
                                DALAM PERJALANAN
                            </span>
                            @elseif($sh->delivery_status === 'returned')
                            <span class="bg-rose-100 text-rose-800 px-2 py-0.5 rounded font-semibold text-[10px] uppercase">
                                RETUR
                            </span>
                            @else
                            <span class="bg-purple-100 text-purple-800 px-2 py-0.5 rounded font-semibold text-[10px] uppercase">
                                PACKING / SIAP
                            </span>
                            @endif
                        </td>
                        <td class="p-3 text-right space-x-1">
                            <!-- MOB-13 SOLVED: Touch Target >= 34px -->
                            @if($sh->delivery_status === 'packed')
                            <form action="{{ route('distribution.shipment.update-status', $sh->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="delivery_status" value="in_transit">
                                <button type="submit" class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold px-3 py-1.5 min-h-[34px] rounded-lg transition inline-flex items-center gap-1">
                                    <i data-lucide="truck" class="w-3.5 h-3.5"></i> Kirim
                                </button>
                            </form>
                            @elseif($sh->delivery_status === 'in_transit')
                            <div class="flex items-center justify-end gap-1">
                                <form action="{{ route('distribution.shipment.update-status', $sh->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="delivery_status" value="delivered">
                                    <button type="submit" class="text-xs bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold px-2.5 py-1.5 min-h-[34px] rounded-lg transition inline-flex items-center gap-1">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i> Diterima
                                    </button>
                                </form>
                                <!-- DST-10 SOLVED: Action button for returned shipments -->
                                <form action="{{ route('distribution.shipment.update-status', $sh->id) }}" method="POST" class="inline" onsubmit="return confirm('Tandai pengiriman ini sebagai RETUR / DIKEMBALIKAN?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="delivery_status" value="returned">
                                    <button type="submit" class="text-xs bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold px-2.5 py-1.5 min-h-[34px] rounded-lg transition inline-flex items-center gap-1" title="Barang Retur / Dikembalikan">
                                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Retur
                                    </button>
                                </form>
                            </div>
                            @elseif($sh->delivery_status === 'returned')
                            <span class="text-[10px] text-rose-600 font-semibold flex items-center justify-end gap-0.5">
                                <i data-lucide="alert-triangle" class="w-3 h-3"></i> Retur Selesai
                            </span>
                            @else
                            <span class="text-[10px] text-emerald-600 font-semibold flex items-center justify-end gap-0.5">
                                <i data-lucide="check-check" class="w-3 h-3"></i> Selesai
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-4 text-center text-slate-400">Belum ada data pengiriman surat jalan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($shipments->hasPages())
        <div class="p-3 border-t">
            {{ $shipments->links() }}
        </div>
        @endif
    </div>

</div>

<!-- MODAL ADD SHIPMENT -->
<div id="modal-add-shipment" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
    <!-- MOB-02 SOLVED: max-h-[90vh] overflow-y-auto to prevent mobile button cutoff -->
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl border max-h-[90vh] overflow-y-auto custom-scrollbar">
        <div class="flex items-center justify-between border-b pb-3">
            <div class="flex items-center gap-2">
                <div class="p-2 bg-purple-100 rounded-lg text-purple-700">
                    <i data-lucide="truck" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800">Terbitkan Surat Jalan Pengiriman Baru</h4>
                    <p class="text-[11px] text-slate-500">Persetujuan ACC pengiriman dan verifikasi packing peti kayu</p>
                </div>
            </div>
            <button onclick="document.getElementById('modal-add-shipment').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- DST-11 SOLVED: SPK Confirmation Summary Card in Modal -->
        <div id="modal_spk_summary_card" class="hidden p-3 bg-purple-50 rounded-xl border border-purple-200 text-xs text-purple-900 space-y-1">
            <div class="flex justify-between items-center">
                <span class="font-bold font-mono text-purple-700" id="modal_summary_spk_num">SPK-XXX</span>
                <span class="bg-purple-200/80 text-purple-800 px-2 py-0.5 rounded text-[10px] font-bold" id="modal_summary_qty">0 Unit Siap Kirim</span>
            </div>
            <p class="font-bold text-slate-800" id="modal_summary_product">Produk</p>
            <p class="text-[11px] text-slate-600" id="modal_summary_customer">Pelanggan</p>
        </div>

        <form action="{{ route('distribution.shipment.store') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="text-[11px] font-bold text-slate-600">Pilih Batch SPK (Wajib Lulus QC)</label>
                <select name="work_order_id" id="modal_work_order_id" class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                    <option value="">-- Pengiriman Stok Reguler Gudang --</option>
                    @foreach($workOrders as $wo)
                    <option value="{{ $wo->id }}" {{ (isset($prefillSpkId) && $prefillSpkId == $wo->id) ? 'selected' : '' }}>
                        {{ $wo->spk_number }} - {{ $wo->product->name ?? 'Produk' }} ({{ $wo->completed_quantity }} Unit)
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[11px] font-bold text-slate-600">Pelanggan / Destinasi Pengiriman</label>
                <select name="customer_id" id="modal_customer_id" required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                    @foreach($customers as $c)
                    <option value="{{ $c->id }}">{{ $c->name }} - {{ $c->company_name ?? $c->city }}</option>
                    @endforeach
                </select>
            </div>

            <!-- MOB-04 SOLVED: grid-cols-1 sm:grid-cols-2 -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Tanggal Pengiriman</label>
                    <input type="date" name="shipment_date" value="{{ date('Y-m-d') }}" required class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
                <div>
                    <!-- DST-08 SOLVED: Dynamic expedition datalist -->
                    <label class="text-[11px] font-bold text-slate-600">Nama Ekspedisi / Kargo</label>
                    <input type="text" name="expedition_name" list="expedition_options" placeholder="Pilih atau ketik nama kargo..." required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                    <datalist id="expedition_options">
                        <option value="Armada Truk Sentra Marmer Tulungagung">
                        <option value="Ekspedisi Bali Mandiri Express">
                        <option value="Baraka Sarana Tama (BST Cargo)">
                        <option value="Dakota Kargo Jawa-Bali">
                        <option value="J&T Cargo / JTR Trucking">
                        <option value="Indah Logistik Cargo">
                        <option value="Armada Pick-up Sendiri">
                    </datalist>
                </div>
            </div>

            <!-- MOB-04 SOLVED: grid-cols-1 sm:grid-cols-3 -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                <div>
                    <label class="text-[11px] font-bold text-slate-600">No. Polisi Truk</label>
                    <input type="text" name="vehicle_plate" placeholder="AG 8899 AB" class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Nama Sopir</label>
                    <input type="text" name="driver_name" placeholder="Pak Slamet" class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
                <div>
                    <!-- DST-09 SOLVED: Input tracking number in create modal -->
                    <label class="text-[11px] font-bold text-slate-600">No. Resi Kargo</label>
                    <input type="text" name="tracking_number" placeholder="Resi jika ada" class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
            </div>

            <div class="p-3 bg-amber-50 rounded-xl border border-amber-200">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="packing_verified" value="1" checked required class="rounded text-purple-600">
                    <span class="text-xs font-bold text-amber-900">Verifikasi Packing Peti Kayu Solid Standar Ekspor</span>
                </label>
                <p class="text-[10px] text-amber-700 mt-1">Pastikan seluruh wastafel telah dilapisi Bubble Wrap tebal, sterofoam, dan dipaku kuat dalam krat kayu.</p>
            </div>

            <div>
                <label class="text-[11px] font-bold text-slate-600">Catatan Surat Jalan</label>
                <textarea name="notes" rows="2" placeholder="Catatan pengiriman atau instruksi bongkar muat..." class="w-full text-xs mt-1 border rounded-lg p-2"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <button type="button" onclick="document.getElementById('modal-add-shipment').classList.add('hidden')" class="text-xs px-4 py-2 border rounded-lg text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="text-xs px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-sm flex items-center gap-1">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i> Terbitkan Surat Jalan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAccShipmentModal(spkId, spkNumber, customerId, customerName, productName, qty) {
    document.getElementById('modal_work_order_id').value = spkId;
    if (customerId) {
        document.getElementById('modal_customer_id').value = customerId;
    }
    
    // DST-11 SOLVED: Populate SPK summary confirmation in modal
    const summaryCard = document.getElementById('modal_spk_summary_card');
    if (summaryCard) {
        document.getElementById('modal_summary_spk_num').innerText = spkNumber;
        document.getElementById('modal_summary_qty').innerText = qty + ' Unit Siap Kirim';
        document.getElementById('modal_summary_product').innerText = productName;
        document.getElementById('modal_summary_customer').innerText = 'Destinasi: ' + customerName;
        summaryCard.classList.remove('hidden');
    }
    
    document.getElementById('modal-add-shipment').classList.remove('hidden');
}
</script>
@endsection
