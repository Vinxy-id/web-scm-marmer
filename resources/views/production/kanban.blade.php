@extends('layouts.app')

@section('title', 'Kanban Produksi & SPK')
@section('page-title', 'Kanban Tracking Surat Perintah Kerja (SPK) Produksi')
@section('page-subtitle', 'Pantau Progres Batch Wastafel dari Pembelahan hingga Siap Masuk Gudang')

@section('topbar-actions')
    <button onclick="document.getElementById('modal-add-spk').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-2 rounded-lg flex items-center gap-1.5 shadow-sm transition">
        <i data-lucide="plus" class="w-4 h-4"></i> Buat SPK Baru
    </button>
@endsection

@section('content')
<div class="space-y-6">

    <!-- 5 KANBAN COLUMNS -->
    <div class="flex overflow-x-auto gap-4 pb-4 custom-scrollbar snap-x snap-mandatory">
        
        <!-- Col 1: Antrian SPK -->
        <div class="min-w-[270px] sm:min-w-[290px] flex-1 flex-shrink-0 bg-slate-100 p-3 rounded-xl border border-slate-200 kanban-col space-y-3 snap-start">
            <div class="flex items-center justify-between text-xs font-bold text-slate-700 pb-2 border-b border-slate-200">
                <div class="flex items-center gap-1.5">
                    <i data-lucide="clipboard-list" class="w-3.5 h-3.5 text-slate-500"></i>
                    <span>1. Antrian SPK</span>
                </div>
                <span class="bg-slate-200 text-slate-700 px-2 py-0.5 rounded-full text-[10px]">{{ $colAntrian->count() }}</span>
            </div>

            @foreach($colAntrian as $spk)
            <div class="bg-white p-3.5 rounded-lg border border-slate-200 shadow-sm space-y-2 hover:border-blue-400 transition">
                <div class="flex justify-between items-start">
                    <span class="text-[10px] font-mono font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">{{ $spk->spk_number }}</span>
                    <span class="text-[9px] {{ $spk->priority === 'urgent' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700' }} font-semibold px-1.5 py-0.5 rounded uppercase">
                        {{ $spk->priority }}
                    </span>
                </div>
                <h5 class="text-xs font-bold text-slate-800">{{ $spk->product->name ?? 'Wastafel' }}</h5>
                <p class="text-[11px] text-slate-500">Pesanan: {{ $spk->customer->name ?? 'Stok Gudang' }}</p>
                <div class="flex justify-between items-center text-[10px] text-slate-600 pt-1 border-t">
                    <span>Target: <b>{{ $spk->target_quantity }} Unit</b></span>
                    <span>Due: {{ $spk->due_date->format('d M') }}</span>
                </div>
                <form action="{{ route('production.work-order.update-status', $spk->id) }}" method="POST" class="pt-1">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="in_progress">
                    <button type="submit" class="w-full text-[10px] bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold py-1 rounded flex items-center justify-center gap-1">
                        Mulai Potong Slep <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </button>
                </form>
            </div>
            @endforeach
        </div>

        <!-- Col 2: Persiapan Bahan (Mesin Slep) -->
        <div class="min-w-[270px] sm:min-w-[290px] flex-1 flex-shrink-0 bg-slate-100 p-3 rounded-xl border border-slate-200 kanban-col space-y-3 snap-start">
            <div class="flex items-center justify-between text-xs font-bold text-slate-700 pb-2 border-b border-slate-200">
                <div class="flex items-center gap-1.5">
                    <i data-lucide="scissors" class="w-3.5 h-3.5 text-slate-500"></i>
                    <span>2. Potong Slep</span>
                </div>
                <span class="bg-slate-200 text-slate-700 px-2 py-0.5 rounded-full text-[10px]">{{ $colSlep->count() }}</span>
            </div>

            @foreach($colSlep as $spk)
            <div class="bg-white p-3.5 rounded-lg border border-slate-200 shadow-sm space-y-2 hover:border-blue-400 transition">
                <div class="flex justify-between items-start">
                    <span class="text-[10px] font-mono font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">{{ $spk->spk_number }}</span>
                    <span class="text-[9px] bg-blue-100 text-blue-700 font-semibold px-1.5 py-0.5 rounded">Slep</span>
                </div>
                <h5 class="text-xs font-bold text-slate-800">{{ $spk->product->name ?? 'Wastafel' }}</h5>
                <p class="text-[11px] text-slate-500">Operator: Pak Slamet (Slep)</p>
                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-blue-600 h-full w-2/5"></div>
                </div>
                <div class="flex justify-between items-center text-[10px] text-slate-600 pt-1 border-t">
                    <span>Target: <b>{{ $spk->target_quantity }} Unit</b></span>
                    <span>Due: {{ $spk->due_date->format('d M') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Col 3: Pembubutan & Bentuk -->
        <div class="min-w-[270px] sm:min-w-[290px] flex-1 flex-shrink-0 bg-slate-100 p-3 rounded-xl border border-slate-200 kanban-col space-y-3 snap-start">
            <div class="flex items-center justify-between text-xs font-bold text-slate-700 pb-2 border-b border-slate-200">
                <div class="flex items-center gap-1.5">
                    <i data-lucide="disc" class="w-3.5 h-3.5 text-slate-500"></i>
                    <span>3. Mesin Bubut</span>
                </div>
                <span class="bg-slate-200 text-slate-700 px-2 py-0.5 rounded-full text-[10px]">{{ $colBubut->count() }}</span>
            </div>

            @foreach($colBubut as $spk)
            <div class="bg-white p-3.5 rounded-lg border border-slate-200 shadow-sm space-y-2 hover:border-blue-400 transition">
                <div class="flex justify-between items-start">
                    <span class="text-[10px] font-mono font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">{{ $spk->spk_number }}</span>
                    <span class="text-[9px] bg-amber-100 text-amber-700 font-semibold px-1.5 py-0.5 rounded">Bubut 1-4</span>
                </div>
                <h5 class="text-xs font-bold text-slate-800">{{ $spk->product->name ?? 'Wastafel' }}</h5>
                <p class="text-[11px] text-slate-500">4 Operator (Mesin 1-4)</p>
                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-amber-500 h-full w-3/4"></div>
                </div>
                <div class="flex justify-between items-center text-[10px] text-slate-600 pt-1 border-t">
                    <span>Target: <b>{{ $spk->target_quantity }} Unit</b></span>
                    <span>Due: {{ $spk->due_date->format('d M') }}</span>
                </div>
                <form action="{{ route('production.work-order.update-status', $spk->id) }}" method="POST" class="pt-1">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="qc_phase">
                    <button type="submit" class="w-full text-[10px] bg-amber-50 hover:bg-amber-100 text-amber-800 font-semibold py-1 rounded flex items-center justify-center gap-1">
                        Kirim ke Inspeksi QC <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </button>
                </form>
            </div>
            @endforeach
        </div>

        <!-- Col 4: QC & Finishing Poles -->
        <div class="min-w-[270px] sm:min-w-[290px] flex-1 flex-shrink-0 bg-slate-100 p-3 rounded-xl border border-slate-200 kanban-col space-y-3 snap-start">
            <div class="flex items-center justify-between text-xs font-bold text-slate-700 pb-2 border-b border-slate-200">
                <div class="flex items-center gap-1.5">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-slate-500"></i>
                    <span>4. QC & Poles</span>
                </div>
                <span class="bg-slate-200 text-slate-700 px-2 py-0.5 rounded-full text-[10px]">{{ $colQc->count() }}</span>
            </div>

            @foreach($colQc as $spk)
            <div class="bg-white p-3.5 rounded-lg border border-slate-200 shadow-sm space-y-2 hover:border-blue-400 transition">
                <div class="flex justify-between items-start">
                    <span class="text-[10px] font-mono font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">{{ $spk->spk_number }}</span>
                    <span class="text-[9px] bg-emerald-100 text-emerald-700 font-semibold px-1.5 py-0.5 rounded">QC 2 Poles</span>
                </div>
                <h5 class="text-xs font-bold text-slate-800">{{ $spk->product->name ?? 'Wastafel' }}</h5>
                <p class="text-[11px] text-slate-500">Inspeksi Lubang Afur</p>
                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-emerald-500 h-full w-11/12"></div>
                </div>
                <div class="flex justify-between items-center text-[10px] text-slate-600 pt-1 border-t">
                    <span>Lolos QC: <b>{{ $spk->completed_quantity }}/{{ $spk->target_quantity }}</b></span>
                    <span>Tambal: {{ $spk->scrap_quantity }}</span>
                </div>
                <a href="{{ route('qc.index') }}" class="block text-center text-[10px] bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-semibold py-1 rounded">
                    Buka Form QC
                </a>
            </div>
            @endforeach
        </div>

        <!-- Col 5: Selesai / Siap Kirim -->
        <div class="min-w-[270px] sm:min-w-[290px] flex-1 flex-shrink-0 bg-slate-100 p-3 rounded-xl border border-slate-200 kanban-col space-y-3 snap-start">
            <div class="flex items-center justify-between text-xs font-bold text-slate-700 pb-2 border-b border-slate-200">
                <div class="flex items-center gap-1.5">
                    <i data-lucide="package-check" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>5. Siap Kirim</span>
                </div>
                <span class="bg-slate-200 text-slate-700 px-2 py-0.5 rounded-full text-[10px]">{{ $colCompleted->count() }}</span>
            </div>

            @foreach($colCompleted as $spk)
            <div class="bg-white p-3.5 rounded-lg border border-emerald-300 shadow-sm space-y-2">
                <span class="text-[10px] font-mono font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">{{ $spk->spk_number }}</span>
                <h5 class="text-xs font-bold text-slate-800">{{ $spk->product->name ?? 'Wastafel' }}</h5>
                <p class="text-[11px] text-slate-500">{{ $spk->customer->company_name ?? 'Stok Gudang' }}</p>
                <div class="text-[10px] text-emerald-700 font-semibold pt-1 border-t flex items-center gap-1">
                    <i data-lucide="check" class="w-3 h-3 text-emerald-600"></i> {{ $spk->completed_quantity }} Unit (Packing Krat Kayu)
                </div>
            </div>
            @endforeach
        </div>

    </div>

</div>

<!-- MODAL ADD SPK -->
<div id="modal-add-spk" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl border">
        <div class="flex items-center justify-between border-b pb-3">
            <h4 class="text-sm font-bold text-slate-800">Terbitkan Surat Perintah Kerja (SPK) Baru</h4>
            <button onclick="document.getElementById('modal-add-spk').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('production.work-order.store') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="text-[11px] font-bold text-slate-600">Pilih Produk Wastafel</label>
                <select name="product_id" required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                    @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->product_code }} - {{ $p->name }} (HPP: Rp {{ number_format($p->standard_cogs, 0) }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Pelanggan / Buyer (Opsional)</label>
                    <select name="customer_id" class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                        <option value="">-- Untuk Stok Gudang --</option>
                        @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->company_name ?? $c->city }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Prioritas Produksi</label>
                    <select name="priority" required class="w-full text-xs mt-1 border rounded-lg p-2 bg-white">
                        <option value="normal">Normal</option>
                        <option value="high">Tinggi (High)</option>
                        <option value="urgent">Mendesak (Urgent)</option>
                        <option value="low">Rendah (Low)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Target Qty (Unit)</label>
                    <input type="number" name="target_quantity" value="14" min="1" required class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Tgl Mulai</label>
                    <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-slate-600">Tenggat Selesai</label>
                    <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+4 days')) }}" required class="w-full text-xs mt-1 border rounded-lg p-2">
                </div>
            </div>

            <div>
                <label class="text-[11px] font-bold text-slate-600">Catatan Khusus SPK</label>
                <textarea name="notes" rows="2" placeholder="Contoh: Poles Hi-Glossy halus untuk export Bali" class="w-full text-xs mt-1 border rounded-lg p-2"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <button type="button" onclick="document.getElementById('modal-add-spk').classList.add('hidden')" class="text-xs px-4 py-2 border rounded-lg text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="text-xs px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">Terbitkan SPK</button>
            </div>
        </form>
    </div>
</div>
@endsection
