@extends('layouts.app')

@section('title', 'Manajemen Bahan Baku')
@section('page-title', 'Manajemen Bahan Baku & Stok Bongkahan')
@section('page-subtitle', 'Pencatatan Bongkahan Marmer, Onyx, dan Batu Kali dari Penambang')

@section('topbar-actions')
    <button onclick="document.getElementById('modal-add-material').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 shadow-sm transition">
        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Material
    </button>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Filters & Action Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('materials.index') }}" class="flex flex-wrap items-center gap-2 flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode / nama material..." class="text-xs border rounded-lg px-3 py-2 w-48 sm:w-64 focus:outline-blue-500">
            
            <select name="type" class="text-xs border rounded-lg px-2.5 py-2 text-slate-700 bg-white">
                <option value="">Semua Jenis</option>
                <option value="marmer" {{ request('type') == 'marmer' ? 'selected' : '' }}>Marmer</option>
                <option value="onix" {{ request('type') == 'onix' ? 'selected' : '' }}>Onyx</option>
                <option value="batu_kali" {{ request('type') == 'batu_kali' ? 'selected' : '' }}>Batu Kali</option>
                <option value="bahan_penolong" {{ request('type') == 'bahan_penolong' ? 'selected' : '' }}>Bahan Penolong</option>
            </select>

            <select name="status" class="text-xs border rounded-lg px-2.5 py-2 text-slate-700 bg-white">
                <option value="">Semua Status</option>
                <option value="kritis" {{ request('status') == 'kritis' ? 'selected' : '' }}>Kritis</option>
                <option value="rendah" {{ request('status') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                <option value="normal" {{ request('status') == 'normal' ? 'selected' : '' }}>Normal</option>
            </select>

            <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1">
                <i data-lucide="filter" class="w-3.5 h-3.5"></i> Filter
            </button>
        </form>

        <button onclick="document.getElementById('modal-stock-transaction').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 shadow-sm transition">
            <i data-lucide="arrow-down-up" class="w-4 h-4"></i> Catat Mutasi Stok
        </button>
    </div>

    <!-- Material Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b">
                    <tr>
                        <th class="p-3">Kode Material</th>
                        <th class="p-3">Nama Bahan Baku</th>
                        <th class="p-3">Jenis / Grade</th>
                        <th class="p-3">Dimensi Blok</th>
                        <th class="p-3">Stok Terkini</th>
                        <th class="p-3">Stok Min</th>
                        <th class="p-3">Harga Satuan</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($materials as $mat)
                    <tr class="hover:bg-slate-50/80 {{ $mat->stock_status === 'kritis' ? 'bg-red-50/30' : '' }}">
                        <td class="p-3 font-mono font-bold text-blue-700">{{ $mat->material_code }}</td>
                        <td class="p-3 font-semibold text-slate-800">
                            {{ $mat->name }}
                            @if($mat->supplier)
                            <p class="text-[10px] text-slate-400 font-normal">Pemasok: {{ $mat->supplier->name }}</p>
                            @endif
                        </td>
                        <td class="p-3">
                            <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-semibold text-[10px] uppercase">
                                {{ $mat->type }} - {{ str_replace('_', ' ', $mat->grade) }}
                            </span>
                        </td>
                        <td class="p-3 text-slate-500">{{ $mat->dimension_info ?? '-' }}</td>
                        <td class="p-3 font-bold {{ $mat->stock_status === 'kritis' ? 'text-red-600' : 'text-slate-800' }}">
                            {{ number_format($mat->current_stock, 2) }} {{ $mat->unit }}
                        </td>
                        <td class="p-3 text-slate-500">{{ number_format($mat->minimum_stock, 2) }} {{ $mat->unit }}</td>
                        <td class="p-3 text-slate-700">Rp {{ number_format($mat->unit_cost, 0, ',', '.') }}</td>
                        <td class="p-3">
                            @if($mat->stock_status === 'kritis')
                            <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-2 py-0.5 rounded font-semibold text-[10px]">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Kritis
                            </span>
                            @elseif($mat->stock_status === 'rendah')
                            <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 px-2 py-0.5 rounded font-semibold text-[10px]">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Rendah
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded font-semibold text-[10px]">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Normal
                            </span>
                            @endif
                        </td>
                        <td class="p-3 text-right space-x-1">
                            <button class="p-1 text-slate-400 hover:text-blue-600"><i data-lucide="edit-3" class="w-4 h-4"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-4 text-center text-slate-400">Tidak ada data bahan baku yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-t">
            {{ $materials->links() }}
        </div>
    </div>

</div>

<!-- MODAL ADD MATERIAL -->
<div id="modal-add-material" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl border">
        <div class="flex items-center justify-between border-b pb-3">
            <h4 class="text-sm font-bold text-slate-800">Tambah Bahan Baku Baru</h4>
            <button onclick="document.getElementById('modal-add-material').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('materials.store') }}" method="POST" class="space-y-3">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Kode Material</label>
                    <input type="text" name="material_code" placeholder="MAT-MRM-002" required class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Jenis Batuan</label>
                    <select name="type" required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                        <option value="marmer">Marmer</option>
                        <option value="onix">Onyx</option>
                        <option value="batu_kali">Batu Kali</option>
                        <option value="bahan_penolong">Bahan Penolong</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-[11px] font-bold text-slate-600">Nama Bahan Baku</label>
                <input type="text" name="name" placeholder="Bongkahan Marmer Trotol Besole" required class="w-full text-xs mt-1 border rounded-lg p-2">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Grade Batuan</label>
                    <select name="grade" required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                        <option value="grade_a_super">Grade A Super</option>
                        <option value="grade_b_standard">Grade B Standard</option>
                        <option value="grade_c_ekonomis">Grade C Ekonomis</option>
                    </select>
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Pemasok Tambang</label>
                    <select name="supplier_id" class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                        <option value="">Pilih Pemasok (Opsional)</option>
                        @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Stok Awal</label>
                    <input type="number" step="0.01" name="current_stock" value="10" required class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Batas Min (Alert)</label>
                    <input type="number" step="0.01" name="minimum_stock" value="5" required class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Satuan</label>
                    <input type="text" name="unit" value="blok" required class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Dimensi Blok</label>
                    <input type="text" name="dimension_info" placeholder="80 x 60 x 60 cm" class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Harga Satuan (Rp)</label>
                    <input type="number" name="unit_cost" value="450000" required class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <button type="button" onclick="document.getElementById('modal-add-material').classList.add('hidden')" class="text-xs px-4 py-2 border rounded-lg text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="text-xs px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">Simpan Material</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL MUTASI STOK -->
<div id="modal-stock-transaction" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl border">
        <div class="flex items-center justify-between border-b pb-3">
            <h4 class="text-sm font-bold text-slate-800">Catat Mutasi Stok Masuk / Keluar</h4>
            <button onclick="document.getElementById('modal-stock-transaction').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('materials.transaction') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="text-[11px] font-bold text-slate-600">Pilih Material</label>
                <select name="material_id" required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                    @foreach($materials as $m)
                    <option value="{{ $m->id }}">{{ $m->material_code }} - {{ $m->name }} (Sisa: {{ $m->current_stock }} {{ $m->unit }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Tipe Transaksi</label>
                    <select name="type" required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white font-bold">
                        <option value="in" class="text-emerald-600">MASUK (Penerimaan Tambang)</option>
                        <option value="out" class="text-red-600">KELUAR (Lantai Produksi)</option>
                    </select>
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Jumlah (Qty)</label>
                    <input type="number" step="0.01" name="quantity" value="1.00" required class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
            </div>

            <div>
                <label class="text-[11px] font-bold text-slate-600">Catatan Transaksi</label>
                <textarea name="notes" rows="2" placeholder="Contoh: Penerimaan 5 truk dari tambang Besole" class="w-full text-xs mt-1 border rounded-lg p-2"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <button type="button" onclick="document.getElementById('modal-stock-transaction').classList.add('hidden')" class="text-xs px-4 py-2 border rounded-lg text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="text-xs px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg">Simpan Transaksi</button>
            </div>
        </form>
    </div>
</div>
@endsection
