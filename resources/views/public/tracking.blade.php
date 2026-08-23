@extends('layouts.public')

@section('title', 'Lacak Pesanan Real-Time - E-SCM Marmer & Onyx Tulungagung')

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
                       placeholder="Masukkan Nomor Order (Contoh: ORD-...) atau Nomor SPK (SPK-...)" 
                       required
                       class="w-full text-xs rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-3 bg-slate-50">
                <button type="submit" class="bg-blue-700 hover:bg-blue-600 text-white text-xs font-bold px-6 py-3 rounded-2xl transition shadow-md flex items-center gap-2 flex-shrink-0">
                    <i data-lucide="search" class="w-4 h-4"></i> Lacak
                </button>
            </form>
        </div>

        @if(!empty($searchNumber) && !$order)
        <div class="bg-white p-8 rounded-3xl border border-rose-200 text-center space-y-3 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 mx-auto flex items-center justify-center">
                <i data-lucide="alert-circle" class="w-6 h-6"></i>
            </div>
            <h3 class="text-sm font-bold text-slate-900">Pesanan Tidak Ditemukan</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto">
                Nomor pesanan atau SPK <b class="font-mono text-slate-800">"{{ $searchNumber }}"</b> tidak ditemukan di dalam sistem. Pastikan nomor yang dimasukkan sudah sesuai dengan invoice Anda.
            </p>
        </div>
        @elseif($order)
        <!-- Tracking Result Card -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden space-y-6 p-6 sm:p-8">
            
            <!-- Header Summary -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-6">
                <div>
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full uppercase border {{ $order->status_badge_class }}">
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

            <!-- Product & Timeline Details -->
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
                            <p class="text-[11px] text-blue-700 font-semibold">Skema: {{ $order->payment_scheme === 'dp_50' ? 'Uang Muka DP 50%' : 'Lunas 100%' }}</p>
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

            <!-- Action Buttons -->
            <div class="pt-4 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100">
                <a href="{{ route('checkout.invoice', $order->order_number) }}" class="text-xs font-bold text-blue-700 hover:underline flex items-center gap-1">
                    <i data-lucide="file-text" class="w-4 h-4"></i> Lihat Invoice Tagihan
                </a>
                <a href="https://wa.me/6281234567890?text={{ urlencode('Halo Admin E-SCM, saya ingin menanyakan status pesanan ' . $order->order_number) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl transition flex items-center gap-1.5 shadow-sm">
                    <i data-lucide="message-circle" class="w-4 h-4"></i> Tanya CS Pengrajin
                </a>
            </div>

        </div>
        @endif

    </div>
</div>
@endsection
