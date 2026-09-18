@extends('layouts.public')

@section('title', 'Lacak Pesanan Real-Time | E-SCM Marmer Tulungagung')
@section('meta-description', 'Pantau status pengerjaan pesanan dan pengiriman surat jalan kerajinan marmer secara transparan dan real-time.')

@section('content')
<!-- Breadcrumb -->
<div class="bg-slate-900 text-white py-6 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
            <span>/</span>
            <a href="{{ route('catalog') }}" class="hover:text-white transition">Katalog</a>
            <span>/</span>
            <span class="text-blue-400 font-semibold">Lacak Progres Pesanan Live</span>
        </div>
    </div>
</div>

<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Search Header Card -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm text-center space-y-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 mx-auto flex items-center justify-center">
                <i data-lucide="search" class="w-6 h-6"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Lacak Status Pesanan & SPK Produksi</h1>
                <p class="text-xs text-slate-500 max-w-lg mx-auto mt-1">
                    Pantau tahapan pengerjaan kerajinan marmer & onyx Anda secara transparan dari lantai bengkel hingga pengiriman ekspedisi.
                </p>
            </div>

            <form action="{{ route('order.tracking') }}" method="GET" class="max-w-xl mx-auto flex gap-2">
                <input type="text" 
                       name="order_number" 
                       value="{{ $searchNumber }}" 
                       placeholder="Masukkan Nomor Order (ORD-...), SPK, atau Nomor HP/WA Anda" 
                       required
                       class="w-full text-xs rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-3 bg-slate-50">
                <button type="submit" class="bg-blue-700 hover:bg-blue-600 text-white text-xs font-bold px-6 py-3 rounded-2xl transition shadow-md flex items-center gap-2 flex-shrink-0">
                    <i data-lucide="search" class="w-4 h-4"></i> Lacak
                </button>
            </form>
            <p class="text-[11px] text-slate-400">
                💡 <b class="text-slate-600">Lupa nomor pesanan?</b> Cukup masukkan nomor HP / WhatsApp yang Anda gunakan saat checkout.
            </p>
        </div>

        <!-- Recent Orders on This Device (LocalStorage Fallback) -->
        <div id="device-history-section" class="hidden bg-white p-6 sm:p-8 rounded-3xl border border-blue-100 shadow-sm space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-2 border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i data-lucide="smartphone" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Riwayat Pesanan di Browser Ini</h3>
                        <p class="text-[11px] text-slate-500">Daftar pesanan Anda yang tersimpan di perangkat ini (dapat dibuka kapan saja tanpa login).</p>
                    </div>
                </div>
                <button type="button" onclick="clearRecentOrders()" class="text-[11px] text-slate-400 hover:text-rose-600 transition flex items-center gap-1">
                    <i data-lucide="trash-2" class="w-3 h-3"></i> Bersihkan Riwayat
                </button>
            </div>

            <div id="recent-orders-list" class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                <!-- Rendered dynamically by JavaScript -->
            </div>
        </div>

        @if(!empty($phoneOrders) && $phoneOrders->count() > 1 && !$order)
        <!-- Multiple Orders Found by Phone Number -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-blue-200 shadow-sm space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="phone-call" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-sm sm:text-base font-extrabold text-slate-900">Ditemukan {{ $phoneOrders->count() }} Pesanan untuk Nomor "{{ $searchNumber }}"</h2>
                    <p class="text-xs text-slate-500">Pilih salah satu pesanan di bawah ini untuk melihat detail pelacakan langsung.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                @foreach($phoneOrders as $pOrder)
                <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/70 hover:bg-white hover:border-blue-300 hover:shadow-md transition space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-mono text-xs font-bold text-slate-900">#{{ $pOrder->order_number }}</span>
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border {{ $pOrder->status_badge_class }}">
                            {{ $pOrder->order_status_label }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-800 line-clamp-1">{{ $pOrder->product->name ?? 'Kerajinan Marmer' }}</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Penerima: <b class="text-slate-700">{{ $pOrder->receiver_name }}</b> ({{ $pOrder->shipping_city }})</p>
                        <p class="text-xs font-black text-blue-900 mt-1">Rp {{ number_format($pOrder->total_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center gap-2 pt-2 border-t border-slate-200/60">
                        <a href="{{ route('order.tracking', ['order_number' => $pOrder->order_number]) }}" class="flex-1 text-center py-2 px-3 bg-blue-700 hover:bg-blue-600 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs">
                            <i data-lucide="search" class="w-3.5 h-3.5"></i> Lacak Progres
                        </a>
                        <a href="{{ route('checkout.invoice', $pOrder->order_number) }}" class="py-2 px-3 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1">
                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Tagihan
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @elseif(!empty($searchNumber) && !$order && !$workOrder && (empty($phoneOrders) || $phoneOrders->isEmpty()))
        <div class="bg-white p-8 rounded-3xl border border-rose-200 text-center space-y-3 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 mx-auto flex items-center justify-center">
                <i data-lucide="alert-circle" class="w-6 h-6"></i>
            </div>
            <h3 class="text-sm font-bold text-slate-900">Pesanan Tidak Ditemukan</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto">
                Kata kunci pencarian <b class="font-mono text-slate-800">"{{ $searchNumber }}"</b> tidak ditemukan. Pastikan Anda memasukkan nomor pesanan yang benar (contoh: <span class="font-mono">ORD-20260917-BXGV</span>) atau nomor HP/WhatsApp yang aktif saat proses pemesanan.
            </p>
        </div>
        @elseif($order)
        <!-- Tracking Result Card (Order Based) -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden space-y-6 p-6 sm:p-8">
            
            <!-- Header Summary -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-6">
                <div>
                    <span class="inline-block whitespace-nowrap text-[10px] font-bold px-2.5 py-1 rounded-full uppercase border {{ $order->status_badge_class }}">
                        {{ $order->order_status_label }}
                    </span>
                    <h2 class="text-xl font-black text-slate-900 mt-2">Pesanan #{{ $order->order_number }}</h2>
                    <p class="text-xs text-slate-400">Penerima: <b class="text-slate-700">{{ $order->receiver_name }}</b> ({{ $order->shipping_city }})</p>
                </div>
                <div class="text-left sm:text-right">
                    <p class="text-xs text-slate-400">Nomor SPK Produksi:</p>
                    <p class="font-mono text-sm font-bold {{ $order->work_order_id ? 'text-indigo-700' : 'text-amber-600' }}">
                        {{ $order->workOrder->spk_number ?? 'Menunggu Verifikasi DP' }}
                    </p>
                </div>
            </div>

            @if($order->isCancelled())
            <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-xs flex items-center gap-3">
                <i data-lucide="x-circle" class="w-5 h-5 text-rose-600 flex-shrink-0"></i>
                <div>
                    <p class="font-bold">Status Pesanan: {{ $order->order_status_label }}</p>
                    <p class="text-[11px] text-rose-700 mt-0.5">Alasan: {{ $order->cancellation_reason ?: 'Melewati batas waktu pembayaran 1x24 jam.' }}</p>
                </div>
            </div>
            @else
            <!-- 5-Step Progress Bar -->
            @php
                $statusWeights = [
                    'pending_payment' => 1,
                    'verified' => 1,
                    'in_production' => 2,
                    'qc_phase' => 3,
                    'packing' => 4,
                    'shipped' => 5,
                    'delivered' => 5,
                ];
                $currentStep = $statusWeights[$order->order_status] ?? 1;
            @endphp

            <div class="py-4">
                <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 text-center">
                    
                    <!-- Step 1: Order Placed -->
                    <div class="p-3 rounded-2xl border {{ $currentStep >= 1 ? 'bg-blue-50 border-blue-200 text-blue-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                        <div class="w-7 h-7 mx-auto rounded-full {{ $currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold text-xs mb-1.5">
                            1
                        </div>
                        <p class="text-xs font-bold">Pesanan Masuk</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">{{ $order->work_order_id ? 'DP Terverifikasi' : 'Verifikasi Invoice' }}</p>
                    </div>

                    <!-- Step 2: In Production -->
                    <div class="p-3 rounded-2xl border {{ $currentStep >= 2 ? 'bg-blue-50 border-blue-200 text-blue-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                        <div class="w-7 h-7 mx-auto rounded-full {{ $currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold text-xs mb-1.5">
                            2
                        </div>
                        <p class="text-xs font-bold">Papan Produksi</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Bubut & Pahat</p>
                    </div>

                    <!-- Step 3: QC Phase -->
                    <div class="p-3 rounded-2xl border {{ $currentStep >= 3 ? 'bg-blue-50 border-blue-200 text-blue-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                        <div class="w-7 h-7 mx-auto rounded-full {{ $currentStep >= 3 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold text-xs mb-1.5">
                            3
                        </div>
                        <p class="text-xs font-bold">Inspeksi QC</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Uji Kilap & Retak</p>
                    </div>

                    <!-- Step 4: Packing -->
                    <div class="p-3 rounded-2xl border {{ $currentStep >= 4 ? 'bg-blue-50 border-blue-200 text-blue-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                        <div class="w-7 h-7 mx-auto rounded-full {{ $currentStep >= 4 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold text-xs mb-1.5">
                            4
                        </div>
                        <p class="text-xs font-bold">Packing Peti</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Pallet Kayu Solid</p>
                    </div>

                    <!-- Step 5: Shipped -->
                    <div class="p-3 rounded-2xl border {{ $currentStep >= 5 ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                        <div class="w-7 h-7 mx-auto rounded-full {{ $currentStep >= 5 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold text-xs mb-1.5">
                            5
                        </div>
                        <p class="text-xs font-bold">Kargo Logistik</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Dalam Perjalanan</p>
                    </div>

                </div>
            </div>
            @endif

            <!-- Product & Shipping Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-100 text-xs">
                
                <!-- Product Information -->
                <div class="space-y-3">
                    <h4 class="font-bold text-slate-900">Barang yang Dipesan:</h4>
                    <div class="flex gap-3 bg-slate-50 p-3.5 rounded-2xl border border-slate-100 items-center">
                        <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center border border-slate-200 flex-shrink-0 overflow-hidden">
                            <img src="{{ asset($order->product->image_path ?: 'images/products/wastafel-marmer-putih.svg') }}" alt="{{ $order->product->name }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">{{ $order->product->name }}</p>
                            <p class="text-[11px] text-slate-500">Jumlah: {{ $order->quantity }} unit ({{ $order->product->dimension_spec ?: 'Standar' }})</p>
                            <p class="text-[11px] text-blue-700 font-semibold">Skema: {{ $order->payment_scheme_label }} &bull; {{ $order->formatted_payment_type }}</p>
                        </div>
                    </div>
                </div>

                <!-- Shipping Destination -->
                <div class="space-y-3">
                    <h4 class="font-bold text-slate-900">Alamat Tujuan Pengiriman:</h4>
                    <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100 space-y-1">
                        <p class="font-bold text-slate-800">{{ $order->receiver_name }} ({{ $order->receiver_phone }})</p>
                        <p class="text-slate-600 leading-relaxed">{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
                    </div>
                </div>

            </div>

            <!-- DST-01 SOLVED: Live Shipment & Logistics Tracking Info Card -->
            @if($order->workOrder && $order->workOrder->shipment)
            @php $sh = $order->workOrder->shipment; @endphp
            <div class="p-4 bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200/80 rounded-2xl space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-purple-200/50 pb-2">
                    <div class="flex items-center gap-2">
                        <i data-lucide="truck" class="w-4 h-4 text-purple-700"></i>
                        <h4 class="font-bold text-slate-900 text-xs">Informasi Logistik & Surat Jalan Pengiriman</h4>
                    </div>
                    <span class="text-[10px] font-mono font-bold bg-purple-100 text-purple-800 px-2 py-0.5 rounded border border-purple-200">
                        {{ $sh->shipment_code }}
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-[11px]">
                    <div>
                        <span class="text-slate-500 block text-[10px]">Ekspedisi Kargo</span>
                        <span class="font-bold text-slate-800">{{ $sh->expedition_name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-[10px]">No. Resi / Surat Jalan</span>
                        <span class="font-bold text-slate-800 font-mono">{{ $sh->tracking_number ?: $sh->shipment_code }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-[10px]">Armada / Sopir</span>
                        <span class="font-bold text-slate-800">{{ $sh->vehicle_plate ?: 'Armada Logistik' }} {{ $sh->driver_name ? '(' . $sh->driver_name . ')' : '' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-[10px]">Status Packing Kayu</span>
                        <span class="font-bold text-emerald-700 flex items-center gap-1">
                            <i data-lucide="shield-check" class="w-3 h-3 text-emerald-600"></i> Terverifikasi Solid Kayu
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="pt-4 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100">
                <a href="{{ route('checkout.invoice', $order->order_number) }}" class="text-xs font-bold text-blue-700 hover:underline flex items-center gap-1">
                    <i data-lucide="file-text" class="w-4 h-4"></i> Lihat Invoice Tagihan
                </a>
                <a href="https://wa.me/6281340231737?text={{ urlencode('Halo Admin E-SCM, saya ingin menanyakan status pesanan ' . $order->order_number) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl transition flex items-center gap-1.5 shadow-sm">
                    <i data-lucide="message-circle" class="w-4 h-4"></i> Tanya CS Pengrajin
                </a>
            </div>

        </div>
        @elseif($workOrder)
        <!-- Standalone Work Order / SPK Tracking Result Card -->
        @php
            $woBadge = match($workOrder->status) {
                'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'qc_phase' => 'bg-purple-100 text-purple-800 border-purple-200',
                'in_progress' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                default => 'bg-blue-100 text-blue-800 border-blue-200',
            };
            $woLabel = match($workOrder->status) {
                'completed' => 'Selesai Produksi (Siap Kirim)',
                'qc_phase' => 'Tahap Pengujian Mutu (QC)',
                'in_progress' => 'Sedang Dikerjakan di Bengkel',
                default => 'Dijadwalkan di Papan Produksi',
            };
            $woStepWeight = match($workOrder->status) {
                'scheduled', 'draft' => 1,
                'in_progress' => 2,
                'qc_phase' => 3,
                'completed' => ($workOrder->shipment && in_array($workOrder->shipment->delivery_status, ['in_transit', 'delivered'])) ? 5 : 4,
            };
        @endphp

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden space-y-6 p-6 sm:p-8">
            
            <!-- Header Summary -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-6">
                <div>
                    <span class="inline-block whitespace-nowrap text-[10px] font-bold px-2.5 py-1 rounded-full uppercase border {{ $woBadge }}">
                        {{ $woLabel }}
                    </span>
                    <h2 class="text-xl font-black text-slate-900 mt-2">Surat Perintah Kerja #{{ $workOrder->spk_number }}</h2>
                    <p class="text-xs text-slate-400">Pemesan / Mitra: <b class="text-slate-700">{{ $workOrder->customer->company_name ?? ($workOrder->customer->name ?? 'Stok Gudang Sentra') }}</b> ({{ $workOrder->customer->city ?? 'Tulungagung' }})</p>
                </div>
                <div class="text-left sm:text-right">
                    <p class="text-xs text-slate-400">Target & Realisasi:</p>
                    <p class="font-mono text-sm font-bold text-indigo-700">
                        {{ $workOrder->completed_quantity }} / {{ $workOrder->target_quantity }} Unit
                    </p>
                </div>
            </div>

            <!-- 5-Step Progress Bar -->
            <div class="py-4">
                <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 text-center">
                    
                    <!-- Step 1: SPK Created -->
                    <div class="p-3 rounded-2xl border {{ $woStepWeight >= 1 ? 'bg-blue-50 border-blue-200 text-blue-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                        <div class="w-7 h-7 mx-auto rounded-full {{ $woStepWeight >= 1 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold text-xs mb-1.5">
                            1
                        </div>
                        <p class="text-xs font-bold">Terbit SPK</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Jadwal Produksi</p>
                    </div>

                    <!-- Step 2: In Production -->
                    <div class="p-3 rounded-2xl border {{ $woStepWeight >= 2 ? 'bg-blue-50 border-blue-200 text-blue-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                        <div class="w-7 h-7 mx-auto rounded-full {{ $woStepWeight >= 2 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold text-xs mb-1.5">
                            2
                        </div>
                        <p class="text-xs font-bold">Papan Produksi</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Slep & Bubut</p>
                    </div>

                    <!-- Step 3: QC Phase -->
                    <div class="p-3 rounded-2xl border {{ $woStepWeight >= 3 ? 'bg-blue-50 border-blue-200 text-blue-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                        <div class="w-7 h-7 mx-auto rounded-full {{ $woStepWeight >= 3 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold text-xs mb-1.5">
                            3
                        </div>
                        <p class="text-xs font-bold">Inspeksi QC</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">2-Tahap Mutu</p>
                    </div>

                    <!-- Step 4: Packing -->
                    <div class="p-3 rounded-2xl border {{ $woStepWeight >= 4 ? 'bg-blue-50 border-blue-200 text-blue-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                        <div class="w-7 h-7 mx-auto rounded-full {{ $woStepWeight >= 4 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold text-xs mb-1.5">
                            4
                        </div>
                        <p class="text-xs font-bold">Packing Peti</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Krat Kayu Solid</p>
                    </div>

                    <!-- Step 5: Shipped -->
                    <div class="p-3 rounded-2xl border {{ $woStepWeight >= 5 ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                        <div class="w-7 h-7 mx-auto rounded-full {{ $woStepWeight >= 5 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold text-xs mb-1.5">
                            5
                        </div>
                        <p class="text-xs font-bold">Kargo Logistik</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">{{ $workOrder->shipment ? 'Surat Jalan Terbit' : 'Antrean Siap Kirim' }}</p>
                    </div>

                </div>
            </div>

            <!-- Product & Customer Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-100 text-xs">
                
                <!-- Product Information -->
                <div class="space-y-3">
                    <h4 class="font-bold text-slate-900">Produk yang Diproduksi:</h4>
                    <div class="flex gap-3 bg-slate-50 p-3.5 rounded-2xl border border-slate-100 items-center">
                        <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center border border-slate-200 flex-shrink-0 overflow-hidden">
                            <img src="{{ asset($workOrder->product->image_path ?: 'images/products/wastafel-marmer-putih.svg') }}" alt="{{ $workOrder->product->name ?? 'Produk' }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">{{ $workOrder->product->name ?? 'Kerajinan Marmer' }}</p>
                            <p class="text-[11px] text-slate-500">Target: {{ $workOrder->target_quantity }} Unit ({{ $workOrder->product->dimension_spec ?: 'Standar' }})</p>
                            <p class="text-[11px] text-indigo-700 font-semibold">Tenggat: {{ $workOrder->due_date ? $workOrder->due_date->format('d M Y') : '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Customer Destination -->
                <div class="space-y-3">
                    <h4 class="font-bold text-slate-900">Pelanggan / Destinasi Mitra:</h4>
                    <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100 space-y-1">
                        <p class="font-bold text-slate-800">{{ $workOrder->customer->name ?? 'Stok Gudang Sentra' }} ({{ $workOrder->customer->phone ?? '-' }})</p>
                        <p class="text-slate-600 leading-relaxed">{{ $workOrder->customer->address ?? 'Kawasan Industri Marmer' }}, {{ $workOrder->customer->city ?? 'Tulungagung' }}</p>
                    </div>
                </div>

            </div>

            <!-- Live Shipment Info if available -->
            @if($workOrder->shipment)
            @php $sh = $workOrder->shipment; @endphp
            <div class="p-4 bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200/80 rounded-2xl space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-purple-200/50 pb-2">
                    <div class="flex items-center gap-2">
                        <i data-lucide="truck" class="w-4 h-4 text-purple-700"></i>
                        <h4 class="font-bold text-slate-900 text-xs">Informasi Logistik & Surat Jalan Pengiriman</h4>
                    </div>
                    <span class="text-[10px] font-mono font-bold bg-purple-100 text-purple-800 px-2 py-0.5 rounded border border-purple-200">
                        {{ $sh->shipment_code }}
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-[11px]">
                    <div>
                        <span class="text-slate-500 block text-[10px]">Ekspedisi Kargo</span>
                        <span class="font-bold text-slate-800">{{ $sh->expedition_name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-[10px]">No. Resi / Surat Jalan</span>
                        <span class="font-bold text-slate-800 font-mono">{{ $sh->tracking_number ?: $sh->shipment_code }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-[10px]">Armada / Sopir</span>
                        <span class="font-bold text-slate-800">{{ $sh->vehicle_plate ?: 'Armada Logistik' }} {{ $sh->driver_name ? '(' . $sh->driver_name . ')' : '' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-[10px]">Status Packing Kayu</span>
                        <span class="font-bold text-emerald-700 flex items-center gap-1">
                            <i data-lucide="shield-check" class="w-3 h-3 text-emerald-600"></i> Terverifikasi Solid Kayu
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="pt-4 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100">
                <span class="text-xs text-slate-500 font-medium">Batch SPK Resmi Sentra IKM Marmer Tulungagung</span>
                <a href="https://wa.me/6281340231737?text={{ urlencode('Halo Admin E-SCM, saya ingin menanyakan status SPK ' . $workOrder->spk_number) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl transition flex items-center gap-1.5 shadow-sm">
                    <i data-lucide="message-circle" class="w-4 h-4"></i> Tanya CS Pengrajin
                </a>
            </div>

        </div>
        @endif

    </div>
</div>

<script>
    function renderRecentOrders() {
        try {
            const container = document.getElementById('device-history-section');
            const listEl = document.getElementById('recent-orders-list');
            if (!container || !listEl) return;

            const orders = JSON.parse(localStorage.getItem('scm_recent_orders') || '[]');
            if (!Array.isArray(orders) || orders.length === 0) {
                container.classList.add('hidden');
                return;
            }

            container.classList.remove('hidden');
            listEl.innerHTML = orders.map(o => `
                <div class="p-4 bg-slate-50/90 rounded-2xl border border-slate-200 flex flex-col justify-between gap-2.5 hover:bg-white hover:border-blue-300 hover:shadow-xs transition">
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-mono text-xs font-bold text-slate-900">#${escapeHtml(o.number)}</span>
                        <span class="text-[10px] font-semibold text-slate-500">${escapeHtml(o.date)}</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-800 line-clamp-1">${escapeHtml(o.product)}</p>
                        <p class="text-xs font-black text-blue-900 mt-0.5">${escapeHtml(o.amount)}</p>
                    </div>
                    <div class="flex items-center gap-2 pt-2 border-t border-slate-200/80">
                        <a href="${escapeHtml(o.trackUrl)}" class="flex-1 text-center py-2 px-3 bg-blue-700 hover:bg-blue-600 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs">
                            <i data-lucide="search" class="w-3.5 h-3.5"></i> Lacak Live
                        </a>
                        <a href="${escapeHtml(o.invoiceUrl)}" class="py-2 px-3 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1">
                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Tagihan
                        </a>
                    </div>
                </div>
            `).join('');

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        } catch (e) {
            console.error('Error rendering recent orders:', e);
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>"']/g, function (m) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[m];
        });
    }

    function clearRecentOrders() {
        if (confirm('Hapus daftar riwayat pesanan dari browser ini?')) {
            localStorage.removeItem('scm_recent_orders');
            const container = document.getElementById('device-history-section');
            if (container) container.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', renderRecentOrders);
</script>
@endsection
