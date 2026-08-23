@extends('layouts.public')

@section('title', 'Invoice Pembayaran - ' . $order->order_number)

@section('content')
<!-- Breadcrumb -->
<div class="bg-slate-900 text-white py-6 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
            <span>/</span>
            <a href="{{ route('catalog') }}" class="hover:text-white transition">Katalog</a>
            <span>/</span>
            <span class="text-blue-400 font-semibold">Invoice & Tagihan Digital</span>
        </div>
    </div>
</div>

@php
    $billAmount = ($order->payment_scheme === 'dp_50') 
        ? (($order->total_amount * 0.5) + $order->unique_code) 
        : ($order->total_amount + $order->unique_code);
    
    $selectedBank = $banks[$order->payment_method] ?? $banks['qris'];

    $isPutraAbadi = in_array($order->product->material_type ?? '', ['batu_kali']) || 
                   str_contains(strtolower($order->product->name ?? ''), 'kali') || 
                   str_contains(strtolower($order->product->name ?? ''), 'stepping') || 
                   str_contains(strtolower($order->product->name ?? ''), 'lampu');

    $artisan = $isPutraAbadi ? [
        'name' => 'UD Putra Abadi',
        'owner' => 'Efri Saputra',
        'phone' => '6281298765432',
    ] : [
        'name' => 'UD Cahaya Onix',
        'owner' => 'M. Ilham Nur Amali',
        'phone' => '6281234567890',
    ];
@endphp

<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Flash Message if any -->
        @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs flex items-center gap-2 shadow-sm">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if($order->isCancelled())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-xs flex items-center gap-2 shadow-sm">
            <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600 flex-shrink-0"></i>
            <div>
                <p class="font-bold">Pesanan Ini Telah Dibatalkan / Kadaluarsa</p>
                <p class="text-[11px] text-rose-700 mt-0.5">Alasan: {{ $order->cancellation_reason ?: 'Melewati batas waktu pembayaran 1x24 jam.' }}</p>
            </div>
        </div>
        @endif

        <!-- Invoice Card -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden" id="printable-invoice">
            
            <!-- Invoice Header -->
            <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="bg-blue-500/30 text-blue-200 text-[11px] font-bold px-2.5 py-0.5 rounded-full border border-blue-400/30">
                                {{ $order->payment_scheme === 'dp_50' ? 'TAGIHAN UANG MUKA (DP 50%)' : 'TAGIHAN LUNAS (100%)' }}
                            </span>
                            <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full border {{ $order->status_badge_class }}">
                                {{ $order->order_status_label }}
                            </span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black mt-2">Invoice #{{ $order->order_number }}</h1>
                        <p class="text-xs text-slate-300 mt-0.5">Tanggal Pesanan: {{ $order->created_at->translatedFormat('d F Y - H:i') }} WIB</p>
                        
                        @if($order->order_status === 'pending_payment' && !$order->isExpired() && $order->expires_at)
                        <p class="text-[11px] text-amber-300 font-semibold mt-1 flex items-center gap-1">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i> Batas Waktu Bayar: {{ $order->expires_at->translatedFormat('d M Y, H:i') }} WIB ({{ $order->expires_at->diffForHumans() }})
                        </p>
                        @endif
                    </div>

                    <div class="text-left sm:text-right">
                        <p class="text-xs text-slate-300 font-semibold">Total Tagihan:</p>
                        <p class="text-2xl sm:text-3xl font-black text-amber-400 mt-0.5">
                            Rp {{ number_format($billAmount, 0, ',', '.') }}
                        </p>
                        <p class="text-[10px] text-slate-400">Termasuk kode verifikasi unik: +Rp {{ $order->unique_code }}</p>
                    </div>
                </div>
            </div>

            <!-- Invoice Body -->
            <div class="p-6 sm:p-8 space-y-8">
                
                @if(!$order->isCancelled())
                <!-- Payment Instructions Box -->
                <div class="bg-blue-50/60 p-5 sm:p-6 rounded-2xl border border-blue-100 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="credit-card" class="w-5 h-5 text-blue-700"></i>
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Instruksi Pembayaran Resmi IKM:</h3>
                        </div>
                        <span class="text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded">
                            {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}
                        </span>
                    </div>

                    @if($order->payment_method === 'qris')
                    <!-- QRIS Box -->
                    <div class="flex flex-col sm:flex-row items-center gap-6 bg-white p-4 rounded-xl border border-blue-200">
                        <div class="w-36 h-36 bg-slate-100 rounded-xl p-2 flex items-center justify-center border border-slate-200 flex-shrink-0">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode('00020101021126580014ID.LINKAJA.WWW01189360000201102435890123520458125303360540' . $billAmount . '5802ID5919UD CAHAYA ONIX MARMER6011TULUNGAGUNG6304') }}" alt="QRIS IKM Marmer" class="w-full h-full object-contain">
                        </div>
                        <div class="space-y-1.5 text-xs">
                            <p class="font-bold text-slate-900">Scan QRIS melalui Aplikasi Mobile Banking atau E-Wallet:</p>
                            <p class="text-slate-600 text-[11px]">BCA Mobile, Livin Mandiri, BRImo, BNI Mobile, GoPay, OVO, ShopeePay, DANA, LinkAja.</p>
                            <p class="text-slate-500 text-[11px]">NMID: <b class="font-mono text-slate-800">ID102435890123</b> | Merchant: <b>UD CAHAYA ONIX / PUTRA ABADI</b></p>
                        </div>
                    </div>
                    @else
                    <!-- Bank Account Box -->
                    <div class="bg-white p-4 rounded-xl border border-blue-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-[11px] text-slate-500 font-semibold">{{ $selectedBank['name'] }}</p>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-lg font-black text-slate-900">{{ $selectedBank['number'] }}</span>
                                <button onclick="copyToClipboard('{{ $selectedBank['number'] }}')" class="p-1 text-blue-600 hover:text-blue-800 text-[10px] font-bold underline">Salin</button>
                            </div>
                            <p class="text-xs text-slate-600">Atas Nama: <b>{{ $selectedBank['holder'] }}</b></p>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-[11px] text-slate-400">Nominal Transfer Tepat:</p>
                            <span class="text-base font-black text-blue-900">Rp {{ number_format($billAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endif

                    <p class="text-[11px] text-slate-500 leading-tight">
                        * Mohon transfer dengan nominal tepat hingga 3 digit terakhir. Setelah transfer selesai, kirim bukti ke WhatsApp admin agar SPK pengerjaan di bengkel segera diterbitkan.
                    </p>
                </div>
                @endif

                <!-- Order Detail Table -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Rincian Barang yang Dipesan:</h3>
                    
                    <div class="border border-slate-200 rounded-2xl overflow-hidden text-xs">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                                <tr>
                                    <th class="p-3.5">Produk Kerajinan</th>
                                    <th class="p-3.5 text-center">Qty</th>
                                    <th class="p-3.5 text-right">Harga Satuan</th>
                                    <th class="p-3.5 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="p-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-slate-100 rounded-lg p-1 flex-shrink-0 flex items-center justify-center">
                                                <img src="{{ asset($order->product->image_path ?: 'images/products/wastafel-marmer-putih.svg') }}" alt="{{ $order->product->name }}" class="w-full h-full object-contain">
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900">{{ $order->product->name }}</p>
                                                <p class="text-[11px] text-slate-500">Kode: {{ $order->product->product_code }} | Dimensi: {{ $order->product->dimension_spec ?: 'Standar' }}</p>
                                                @if($order->custom_notes)
                                                <p class="text-[10px] text-blue-700 mt-0.5">Catatan: {{ $order->custom_notes }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-3.5 text-center font-bold">{{ $order->quantity }} unit</td>
                                    <td class="p-3.5 text-right font-semibold">Rp {{ number_format($order->unit_price, 0, ',', '.') }}</td>
                                    <td class="p-3.5 text-right font-bold text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-slate-50/70 border-t border-slate-200 font-semibold text-slate-700">
                                <tr>
                                    <td colspan="3" class="p-3 text-right text-[11px]">Total Nilai Barang:</td>
                                    <td class="p-3 text-right font-bold text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="p-3 text-right text-[11px]">Packing Peti Kayu Solid (Standar Aman):</td>
                                    <td class="p-3 text-right font-bold text-emerald-600">GRATIS</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="p-3 text-right text-[11px]">Kode Unik Verifikasi:</td>
                                    <td class="p-3 text-right font-mono text-slate-600">+ Rp {{ $order->unique_code }}</td>
                                </tr>
                                <tr class="border-t border-slate-200 text-sm bg-blue-50/40">
                                    <td colspan="3" class="p-3.5 text-right font-extrabold text-blue-950">
                                        {{ $order->payment_scheme === 'dp_50' ? 'Total Tagihan Uang Muka (DP 50%):' : 'Total Tagihan Lunas:' }}
                                    </td>
                                    <td class="p-3.5 text-right font-black text-blue-900 text-base">
                                        Rp {{ number_format($billAmount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Customer & Shipping Information Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-1 text-xs">
                        <h4 class="font-bold text-slate-900 mb-2">Tujuan Pengiriman:</h4>
                        <p class="text-slate-800 font-semibold">{{ $order->receiver_name }} ({{ $order->receiver_phone }})</p>
                        <p class="text-slate-600 leading-relaxed">{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-1 text-xs">
                        <h4 class="font-bold text-slate-900 mb-2">Integrasi Rantai Pasok (SCM):</h4>
                        <p class="text-slate-600">
                            Nomor SPK Produksi: 
                            @if($order->work_order_id && $order->workOrder)
                            <b class="font-mono text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">{{ $order->workOrder->spk_number }}</b>
                            @else
                            <b class="font-mono text-amber-700 bg-amber-50 px-2 py-0.5 rounded">Menunggu Verifikasi Pembayaran</b>
                            @endif
                        </p>
                        <p class="text-slate-600">Pengrajin IKM: <b>{{ $artisan['name'] }}</b></p>
                        <p class="text-slate-600">Status Alur: <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded">{{ $order->order_status_label }}</span></p>
                    </div>
                </div>

            </div>

            <!-- Invoice Footer Action Buttons -->
            <div class="bg-slate-50 p-6 sm:p-8 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <button onclick="window.print()" class="px-4 py-2.5 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="printer" class="w-4 h-4"></i> Cetak Invoice
                    </button>
                    <a href="{{ route('order.tracking', ['order_number' => $order->order_number]) }}" class="px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs rounded-xl border border-blue-200 transition flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="truck" class="w-4 h-4"></i> Lacak Progres Pesanan
                    </a>
                </div>

                @if(!$order->isCancelled())
                <a href="{{ $waConfirmUrl }}" target="_blank" class="w-full sm:w-auto px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl transition flex items-center justify-center gap-2 shadow-md">
                    <i data-lucide="message-circle" class="w-4 h-4"></i> Konfirmasi Pembayaran via WhatsApp
                </a>
                @endif
            </div>

        </div>

    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);
        alert('Nomor rekening ' + text + ' berhasil disalin!');
    }
</script>
@endsection
