@extends('layouts.app')

@section('title', 'Manajemen Pesanan Masuk (E-Commerce) - E-SCM Marmer & Onyx')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Summary -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="p-2 rounded-xl bg-amber-50 text-amber-700">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                </span>
                <h1 class="text-xl font-black text-slate-900">Manajemen Pesanan Masuk & Verifikasi SPK</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Sistem 2-Pintu Validasi: Verifikasi pembayaran uang muka (DP) sebelum menerbitkan Surat Perintah Kerja (SPK) ke lantai bengkel.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('production.kanban') }}" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-sm">
                <i data-lucide="kanban-square" class="w-4 h-4 text-indigo-400"></i>
                <span>Buka Papan Kanban Bengkel</span>
            </a>
        </div>
    </div>

    <!-- 4 Stats Cards Summary -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
        <!-- Total Orders -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Pesanan</p>
            <p class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_orders'] }}</p>
            <p class="text-[10px] text-slate-500 mt-0.5">Semua transaksi web</p>
        </div>

        <!-- Pending Payment -->
        <div class="bg-white p-4 rounded-2xl border border-amber-200 shadow-sm bg-amber-50/20">
            <div class="flex items-center justify-between">
                <p class="text-[10px] font-bold text-amber-700 uppercase tracking-wider">Menunggu Bayar</p>
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
            </div>
            <p class="text-2xl font-black text-amber-900 mt-1">{{ $stats['pending_payment'] }}</p>
            <p class="text-[10px] text-amber-700 mt-0.5">Belum masuk ke bengkel</p>
        </div>

        <!-- In Production -->
        <div class="bg-white p-4 rounded-2xl border border-indigo-200 shadow-sm bg-indigo-50/20">
            <p class="text-[10px] font-bold text-indigo-700 uppercase tracking-wider">SPK Diterbitkan</p>
            <p class="text-2xl font-black text-indigo-900 mt-1">{{ $stats['in_production'] }}</p>
            <p class="text-[10px] text-indigo-700 mt-0.5">Dalam pengerjaan bengkel</p>
        </div>

        <!-- Completed -->
        <div class="bg-white p-4 rounded-2xl border border-emerald-200 shadow-sm bg-emerald-50/20">
            <p class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider">Selesai / Terkirim</p>
            <p class="text-2xl font-black text-emerald-900 mt-1">{{ $stats['completed'] }}</p>
            <p class="text-[10px] text-emerald-700 mt-0.5">Barang diterima pembeli</p>
        </div>

        <!-- Cancelled / Expired -->
        <div class="bg-white p-4 rounded-2xl border border-rose-200 shadow-sm bg-rose-50/20 col-span-2 lg:col-span-1">
            <p class="text-[10px] font-bold text-rose-700 uppercase tracking-wider">Batal / Kadaluarsa</p>
            <p class="text-2xl font-black text-rose-900 mt-1">{{ $stats['cancelled'] }}</p>
            <p class="text-[10px] text-rose-700 mt-0.5">Spam / Tidak dibayar</p>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3">
        <!-- Status Filter Tabs -->
        <div class="flex flex-wrap items-center gap-1.5 text-xs font-bold">
            <a href="{{ route('orders.index', ['status' => 'all', 'search' => $search]) }}" 
               class="px-3 py-2 rounded-xl transition {{ $statusFilter === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua ({{ $stats['total_orders'] }})
            </a>
            <a href="{{ route('orders.index', ['status' => 'pending', 'search' => $search]) }}" 
               class="px-3 py-2 rounded-xl transition flex items-center gap-1 {{ $statusFilter === 'pending' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                <span>Menunggu Verifikasi</span>
                @if($stats['pending_payment'] > 0)
                <span class="bg-white text-amber-900 text-[10px] px-1.5 py-0.2 rounded-full font-black">{{ $stats['pending_payment'] }}</span>
                @endif
            </a>
            <a href="{{ route('orders.index', ['status' => 'in_production', 'search' => $search]) }}" 
               class="px-3 py-2 rounded-xl transition {{ $statusFilter === 'in_production' ? 'bg-indigo-600 text-white' : 'bg-indigo-50 text-indigo-800 hover:bg-indigo-100' }}">
                SPK Bengkel ({{ $stats['in_production'] }})
            </a>
            <a href="{{ route('orders.index', ['status' => 'completed', 'search' => $search]) }}" 
               class="px-3 py-2 rounded-xl transition {{ $statusFilter === 'completed' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                Selesai ({{ $stats['completed'] }})
            </a>
            <a href="{{ route('orders.index', ['status' => 'cancelled', 'search' => $search]) }}" 
               class="px-3 py-2 rounded-xl transition {{ $statusFilter === 'cancelled' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-800 hover:bg-rose-100' }}">
                Batal / Kadaluarsa ({{ $stats['cancelled'] }})
            </a>
        </div>

        <!-- Search Form -->
        <form action="{{ route('orders.index') }}" method="GET" class="flex gap-2 w-full md:w-auto">
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            <input type="text" 
                   name="search" 
                   value="{{ $search }}" 
                   placeholder="Cari No Order, Pembeli, Kota..." 
                   class="text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-3 py-2 bg-slate-50 w-full md:w-64">
            <button type="submit" class="bg-blue-700 hover:bg-blue-600 text-white text-xs font-bold px-3 py-2 rounded-xl transition flex items-center gap-1 shadow-sm">
                <i data-lucide="search" class="w-3.5 h-3.5"></i>
            </button>
            @if(!empty($search))
            <a href="{{ route('orders.index', ['status' => $statusFilter]) }}" title="Reset Pencarian" class="p-2 bg-slate-100 text-slate-500 hover:bg-slate-200 rounded-xl transition flex items-center justify-center">
                <i data-lucide="x" class="w-3.5 h-3.5"></i>
            </a>
            @endif
        </form>
    </div>

    <!-- Orders Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                        <th class="py-3.5 px-4 whitespace-nowrap">No. Order & Waktu</th>
                        <th class="py-3.5 px-4 whitespace-nowrap min-w-[160px]">Pembeli & Tujuan</th>
                        <th class="py-3.5 px-4 whitespace-nowrap min-w-[180px]">Produk Kerajinan</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Skema & Nominal</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Status & SPK</th>
                        <th class="py-3.5 px-4 whitespace-nowrap text-center">Tindakan Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($orders as $item)
                    <tr class="hover:bg-slate-50/80 transition">
                        
                        <!-- Order Number & Date -->
                        <td class="py-3.5 px-4 align-top whitespace-nowrap">
                            <div class="space-y-1">
                                <a href="{{ route('checkout.invoice', $item->order_number) }}" target="_blank" class="font-mono font-black text-blue-700 hover:underline inline-flex whitespace-nowrap items-center gap-1">
                                    <span>#{{ $item->order_number }}</span>
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                </a>
                                <p class="text-[11px] text-slate-400 whitespace-nowrap">{{ $item->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                                
                                @if($item->order_status === 'pending_payment')
                                    @if($item->isExpired())
                                    <span class="inline-flex whitespace-nowrap items-center gap-1 text-[10px] font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded">
                                        <i data-lucide="clock" class="w-3 h-3"></i> Kadaluarsa
                                    </span>
                                    @else
                                    <span class="inline-flex whitespace-nowrap items-center gap-1 text-[10px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded">
                                        <i data-lucide="clock" class="w-3 h-3"></i> Exp: {{ $item->expires_at ? $item->expires_at->diffForHumans() : '24 Jam' }}
                                    </span>
                                    @endif
                                @endif
                            </div>
                        </td>

                        <!-- Customer Details -->
                        <td class="py-3.5 px-4 align-top">
                            <div class="space-y-0.5">
                                <p class="font-bold text-slate-900">{{ $item->receiver_name }}</p>
                                <p class="text-[11px] text-slate-500">{{ $item->shipping_city }}</p>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->receiver_phone) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-emerald-700 hover:underline font-semibold">
                                    <i data-lucide="message-circle" class="w-3 h-3"></i> {{ $item->receiver_phone }}
                                </a>
                                @if($item->custom_notes)
                                <p class="text-[10px] text-slate-500 italic bg-slate-50 p-1.5 rounded border border-slate-100 mt-1">
                                    "{{ Str::limit($item->custom_notes, 50) }}"
                                </p>
                                @endif
                            </div>
                        </td>

                        <!-- Product Details -->
                        <td class="py-3.5 px-4 align-top">
                            <div class="flex items-center gap-2.5">
                                <div class="w-10 h-10 rounded-lg bg-slate-100 p-1 flex items-center justify-center border border-slate-200 flex-shrink-0">
                                    <img src="{{ asset($item->product->image_path ?: 'images/products/wastafel-marmer-putih.svg') }}" alt="{{ $item->product->name }}" class="w-full h-full object-contain">
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 leading-tight">{{ $item->product->name }}</p>
                                    <p class="text-[11px] text-slate-500">{{ $item->quantity }} Unit &bull; {{ $item->product->category->name ?? 'Kerajinan' }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Scheme & Amount -->
                        <td class="py-3.5 px-4 align-top whitespace-nowrap">
                            <div class="space-y-1">
                                <p class="font-extrabold text-slate-900">
                                    Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                                </p>
                                <div class="flex items-center gap-1">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded inline-block whitespace-nowrap {{ $item->payment_scheme === 'dp_50' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                        {{ $item->payment_scheme === 'dp_50' ? 'DP 50%' : 'Lunas 100%' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 uppercase font-mono">{{ $item->payment_method }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Status & SPK -->
                        <td class="py-3.5 px-4 align-top whitespace-nowrap">
                            <div class="space-y-1.5">
                                <span class="inline-block whitespace-nowrap text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $item->status_badge_class }}">
                                    {{ $item->order_status_label }}
                                </span>

                                <div>
                                    @if($item->work_order_id && $item->workOrder)
                                    <a href="{{ route('production.kanban') }}" class="inline-flex whitespace-nowrap items-center gap-1 text-[11px] font-mono font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200 hover:bg-indigo-100 transition">
                                        <i data-lucide="kanban-square" class="w-3 h-3"></i>
                                        <span>{{ $item->workOrder->spk_number }}</span>
                                    </a>
                                    @elseif($item->isCancelled())
                                    <p class="text-[10px] text-rose-600">Alasan: {{ $item->cancellation_reason ?: 'Kadaluarsa' }}</p>
                                    @else
                                    <span class="inline-block whitespace-nowrap text-[10px] text-amber-700 font-semibold bg-amber-50/80 px-2 py-0.5 rounded border border-amber-200">
                                        SPK Belum Terbit (Menunggu DP)
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Admin Actions -->
                        <td class="py-3.5 px-4 align-top text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                
                                @if($item->canBeVerified() && !$item->isExpired())
                                <!-- Verify Payment & Generate SPK Button -->
                                <button type="button" 
                                        onclick="openVerifyModal('{{ $item->id }}', '{{ $item->order_number }}', '{{ $item->receiver_name }}', '{{ $item->product->name }}', '{{ $item->quantity }}', '{{ $item->payment_scheme }}', '{{ number_format($item->payment_scheme === 'dp_50' ? $item->total_amount * 0.5 : $item->total_amount, 0, ',', '.') }}')" 
                                        class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-3 py-1.5 rounded-xl transition shadow-sm flex items-center gap-1">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                    <span>Verifikasi & Terbitkan SPK</span>
                                </button>

                                <!-- Cancel Order Button -->
                                <button type="button" 
                                        onclick="openCancelModal('{{ $item->id }}', '{{ $item->order_number }}')" 
                                        class="p-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-xl border border-rose-200 transition" 
                                        title="Batalkan Pesanan">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                                @elseif($item->isCancelled())
                                <!-- Delete Spam Button -->
                                <form action="{{ route('orders.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus permanen rekaman pesanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-slate-100 text-slate-500 hover:bg-rose-100 hover:text-rose-700 rounded-xl transition" title="Hapus Permanen">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('checkout.invoice', $item->order_number) }}" target="_blank" class="p-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl transition" title="Lihat Invoice">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                </a>
                                @endif

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-2 text-slate-400">
                                <i data-lucide="inbox" class="w-6 h-6"></i>
                            </div>
                            <p class="font-bold text-slate-700">Tidak ada data pesanan</p>
                            <p class="text-xs text-slate-400 mt-0.5">Pesanan e-commerce dari pengunjung web akan muncul di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($orders->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Modal 1: Konfirmasi Verifikasi Pembayaran & Terbitkan SPK -->
<div id="verify-modal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl border border-slate-200 max-w-md w-full p-6 space-y-5 shadow-2xl animate-in fade-in zoom-in-95">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Verifikasi Pembayaran & Terbitkan SPK</h3>
                <p class="text-xs text-slate-500">Pintu 2 Validasi Rantai Pasok IKM</p>
            </div>
        </div>

        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-2 text-xs">
            <div class="flex justify-between">
                <span class="text-slate-500">Nomor Pesanan:</span>
                <b id="v-order-num" class="font-mono text-slate-900">-</b>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Nama Pembeli:</span>
                <b id="v-customer-name" class="text-slate-900">-</b>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Produk & Qty:</span>
                <span id="v-product-info" class="font-bold text-slate-900">-</span>
            </div>
            <div class="flex justify-between border-t border-slate-200 pt-2">
                <span class="text-slate-500">Nominal Transfer Masuk:</span>
                <b id="v-amount-info" class="text-emerald-700 font-extrabold">Rp -</b>
            </div>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Dengan mengonfirmasi verifikasi ini, sistem akan:
            <br>&bull; Mengubah status pembayaran menjadi <b>DP Terbayar / Lunas</b>.
            <br>&bull; Secara resmi <b>menerbitkan nomor SPK baru</b> dan memasukkannya ke antrean pengerjaan mesin bubut di papan Kanban bengkel.
        </p>

        <form id="verify-form" action="" method="POST" class="flex gap-2 pt-2 border-t border-slate-100">
            @csrf
            <button type="button" onclick="closeVerifyModal()" class="w-1/2 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                Batal
            </button>
            <button type="submit" class="w-1/2 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-xs font-bold text-white transition shadow-md flex items-center justify-center gap-1.5">
                <i data-lucide="check" class="w-4 h-4"></i> Terbitkan SPK
            </button>
        </form>
    </div>
</div>

<!-- Modal 2: Pembatalan Pesanan / Spam -->
<div id="cancel-modal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl border border-slate-200 max-w-md w-full p-6 space-y-4 shadow-2xl animate-in fade-in zoom-in-95">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-700 flex items-center justify-center flex-shrink-0">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Batalkan Pesanan Masuk</h3>
                <p class="text-xs text-slate-500">Pilih alasan pembatalan order</p>
            </div>
        </div>

        <form id="cancel-form" action="" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Alasan Pembatalan:</label>
                <select name="cancellation_reason" required class="w-full text-xs rounded-xl border-slate-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 p-2.5 bg-slate-50">
                    <option value="Melewati Batas Waktu Pembayaran (1x24 Jam)">Melewati Batas Waktu Pembayaran (1x24 Jam)</option>
                    <option value="Permintaan Pembatalan oleh Pembeli">Permintaan Pembatalan oleh Pembeli</option>
                    <option value="Data Fiktif / Spam Checkout">Data Fiktif / Spam Checkout</option>
                    <option value="Stok Bahan Baku / Batuan Habis">Stok Bahan Baku / Batuan Habis</option>
                </select>
            </div>

            <p class="text-[11px] text-slate-400">
                Pesanan yang dibatalkan tidak akan mengotori lantai bengkel dan stok batu tidak akan terpotong.
            </p>

            <div class="flex gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeCancelModal()" class="w-1/2 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                    Kembali
                </button>
                <button type="submit" class="w-1/2 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-xs font-bold text-white transition shadow-md flex items-center justify-center gap-1">
                    <i data-lucide="x-circle" class="w-4 h-4"></i> Batalkan Order
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openVerifyModal(orderId, orderNum, customerName, productName, qty, scheme, amount) {
        document.getElementById('v-order-num').innerText = '#' + orderNum;
        document.getElementById('v-customer-name').innerText = customerName;
        document.getElementById('v-product-info').innerText = productName + ' (' + qty + ' Unit)';
        document.getElementById('v-amount-info').innerText = 'Rp ' + amount + ' (' + (scheme === 'dp_50' ? 'Uang Muka 50%' : 'Pelunasan 100%') + ')';
        document.getElementById('verify-form').action = `{{ url('/orders') }}/${orderId}/verify-spk`;
        document.getElementById('verify-modal').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeVerifyModal() {
        document.getElementById('verify-modal').classList.add('hidden');
    }

    function openCancelModal(orderId, orderNum) {
        document.getElementById('cancel-form').action = `{{ url('/orders') }}/${orderId}/cancel`;
        document.getElementById('cancel-modal').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeCancelModal() {
        document.getElementById('cancel-modal').classList.add('hidden');
    }
</script>
@endsection
