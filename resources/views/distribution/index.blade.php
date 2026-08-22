@extends('layouts.app')

@section('title', 'Distribusi & Packing Kayu')
@section('page-title', 'Distribusi & Checklist Packing Kayu')
@section('page-subtitle', 'Manajemen Surat Jalan, Verifikasi Pengepakan Krat Kayu, dan Ekspedisi Kargo')

@section('topbar-actions')
    <button onclick="document.getElementById('modal-add-shipment').classList.remove('hidden')" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 shadow-sm transition">
        <i data-lucide="plus" class="w-4 h-4"></i> Terbitkan Surat Jalan
    </button>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Shipments Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b">
                    <tr>
                        <th class="p-3">No. Surat Jalan</th>
                        <th class="p-3">Pelanggan / Destinasi</th>
                        <th class="p-3">Tgl Kirim</th>
                        <th class="p-3">Ekspedisi & Driver</th>
                        <th class="p-3">Checklist Packing Kayu</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($shipments as $sh)
                    <tr class="hover:bg-slate-50/80">
                        <td class="p-3 font-mono font-bold text-purple-700">{{ $sh->shipment_number }}</td>
                        <td class="p-3 font-semibold text-slate-800">
                            {{ $sh->customer->company_name ?? $sh->customer->name }}
                            <p class="text-[10px] text-slate-400 font-normal">{{ $sh->customer->city }}</p>
                        </td>
                        <td class="p-3">{{ $sh->shipment_date->format('d M Y') }}</td>
                        <td class="p-3 text-slate-600">
                            {{ $sh->expedition_name ?? 'Kargo Truk Sendiri' }}
                            <p class="text-[10px] text-slate-400">Driver: {{ $sh->driver_name ?? '-' }} ({{ $sh->vehicle_number ?? '-' }})</p>
                        </td>
                        <td class="p-3">
                            @if($sh->wooden_packing_checked)
                            <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded font-semibold text-[10px]">
                                <i data-lucide="check" class="w-3 h-3 text-emerald-600"></i> Terverifikasi Solid
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 bg-red-100 text-red-800 px-2 py-0.5 rounded font-semibold text-[10px]">
                                Belum Terverifikasi
                            </span>
                            @endif
                        </td>
                        <td class="p-3">
                            <span class="bg-purple-100 text-purple-800 px-2 py-0.5 rounded font-semibold text-[10px] uppercase">
                                {{ str_replace('_', ' ', $sh->status) }}
                            </span>
                        </td>
                        <td class="p-3 text-right">
                            @if($sh->status === 'prepared')
                            <form action="{{ route('distribution.shipment.update-status', $sh->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="in_transit">
                                <button type="submit" class="text-[10px] bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold px-2 py-1 rounded">
                                    Kirim Kargo
                                </button>
                            </form>
                            @elseif($sh->status === 'in_transit')
                            <form action="{{ route('distribution.shipment.update-status', $sh->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="delivered">
                                <button type="submit" class="text-[10px] bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold px-2 py-1 rounded">
                                    Tandai Terkirim
                                </button>
                            </form>
                            @else
                            <span class="text-[10px] text-emerald-600 font-semibold">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-slate-400">Belum ada data pengiriman surat jalan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL ADD SHIPMENT -->
<div id="modal-add-shipment" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl border">
        <div class="flex items-center justify-between border-b pb-3">
            <h4 class="text-sm font-bold text-slate-800">Terbitkan Surat Jalan Pengiriman Baru</h4>
            <button onclick="document.getElementById('modal-add-shipment').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('distribution.shipment.store') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="text-[11px] font-bold text-slate-600">Pilih Pelanggan / Penerima</label>
                <select name="customer_id" required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                    @foreach($customers as $c)
                    <option value="{{ $c->id }}">{{ $c->name }} - {{ $c->company_name ?? $c->city }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Tanggal Pengiriman</label>
                    <input type="date" name="shipment_date" value="{{ date('Y-m-d') }}" required class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Nama Ekspedisi</label>
                    <input type="text" name="expedition_name" placeholder="Kargo Lintas Jawa-Bali" class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[11px] font-bold text-slate-600">No. Polisi Kendaraan</label>
                    <input type="text" name="vehicle_number" placeholder="AG 8942 US" class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Nama Sopir</label>
                    <input type="text" name="driver_name" placeholder="Pak Agus Widodo" class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
            </div>

            <div class="p-3 bg-amber-50 rounded-lg border border-amber-200">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="wooden_packing_checked" value="1" checked required class="rounded text-blue-600">
                    <span class="text-xs font-bold text-amber-900">Verifikasi Packing Kayu Solid Standar Ekspor</span>
                </label>
                <p class="text-[10px] text-amber-700 mt-1">Pastikan seluruh wastafel telah dilapisi bubble wrap tebal dan dipaku kuat di dalam krat kayu.</p>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <button type="button" onclick="document.getElementById('modal-add-shipment').classList.add('hidden')" class="text-xs px-4 py-2 border rounded-lg text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="text-xs px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg">Terbitkan Surat Jalan</button>
            </div>
        </form>
    </div>
</div>
@endsection
