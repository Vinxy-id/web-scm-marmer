@extends('layouts.public')

@section('title', 'Invoice Pembayaran - #' . $order->order_number)

@section('styles')
<style>
    /* =========================================================
       PRINT-SPECIFIC STYLESHEET (A4 FORMAL BUSINESS INVOICE)
       ========================================================= */
    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm 12mm 10mm 12mm;
        }

        html, body {
            background: #ffffff !important;
            color: #0f172a !important;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            font-size: 9.5pt !important;
            line-height: 1.35 !important;
            margin: 0 !important;
            padding: 0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Hide all website chrome, headers, footers, announcements, navigation, buttons */
        header, footer, nav, .no-print, [role="banner"], #mobile-menu, .announcement-banner {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Hide the screen interactive view */
        .screen-invoice-view {
            display: none !important;
        }

        /* Show the dedicated printable invoice document */
        .print-invoice-view {
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .print-table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        .print-table th, .print-table td {
            border: 1px solid #94a3b8 !important;
            padding: 5.5px 8px !important;
        }

        .print-table th {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            font-size: 8.5pt !important;
        }

        .print-avoid-break {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
    }

    /* Screen-only rules */
    @media screen {
        .print-invoice-view {
            display: none !important;
        }
    }
</style>
@endsection

@section('content')
<!-- Breadcrumb (Screen only) -->
<div class="no-print bg-slate-900 text-white py-6 border-b border-slate-800">
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
    $isMidtrans = ($order->payment_method === 'midtrans');
    $uniqueCode = $isMidtrans ? 0 : ($order->unique_code ?? 0);
    $billAmount = ($order->payment_scheme === 'dp_50') 
        ? (($order->total_amount * 0.5) + $uniqueCode) 
        : ($order->total_amount + $uniqueCode);
    
    $selectedBank = $banks[$order->payment_method] ?? $banks['qris'];

    $isPaid = in_array($order->payment_status, ['paid_dp', 'paid_full']) || 
              in_array($order->order_status, ['verified', 'in_production', 'qc_phase', 'packing', 'shipped', 'delivered']);

    $isPutraAbadi = in_array($order->product->material_type ?? '', ['batu_kali']) || 
                   str_contains(strtolower($order->product->name ?? ''), 'kali') || 
                   str_contains(strtolower($order->product->name ?? ''), 'stepping') || 
                   str_contains(strtolower($order->product->name ?? ''), 'lampu');

    $artisan = $isPutraAbadi ? [
        'name' => 'UD Putra Abadi',
        'owner' => 'Efri Saputra',
        'phone' => '6281335022012',
    ] : [
        'name' => 'UD Cahaya Onix',
        'owner' => 'M. Ilham Nur Amali',
        'phone' => '6281340231737',
    ];
@endphp

<!-- =========================================================================
     1. SCREEN VIEW (INTERACTIVE DIGITAL INVOICE FOR BROWSER / MOBILE)
     ========================================================================= -->
<div class="screen-invoice-view py-10 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="no-print p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs flex items-center gap-2 shadow-sm">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('info'))
        <div class="no-print p-4 bg-blue-50 border border-blue-200 rounded-2xl text-blue-800 text-xs flex items-center gap-2 shadow-sm">
            <i data-lucide="info" class="w-4 h-4 text-blue-600 flex-shrink-0"></i>
            <span>{{ session('info') }}</span>
        </div>
        @endif

        @if($order->isCancelled())
        <div class="no-print p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-xs flex items-center gap-2 shadow-sm">
            <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600 flex-shrink-0"></i>
            <div>
                <p class="font-bold">Pesanan Ini Telah Dibatalkan / Kadaluarsa</p>
                <p class="text-[11px] text-rose-700 mt-0.5">Alasan: {{ $order->cancellation_reason ?: 'Melewati batas waktu pembayaran 1x24 jam.' }}</p>
            </div>
        </div>
        @endif

        <!-- Invoice Main Card -->
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
                        @if(!$isMidtrans && $uniqueCode > 0)
                        <p class="text-[10px] text-slate-400">Termasuk kode verifikasi unik: +Rp {{ $uniqueCode }}</p>
                        @endif
                    </div>

                </div>
            </div>

            <!-- Invoice Body -->
            <div class="p-6 sm:p-8 space-y-8">
                
                @if(!$order->isCancelled())

                @if(!$isPaid)
                <!-- Anti-Double-Payment Warning Alert -->
                <div class="no-print p-4 sm:p-5 bg-amber-500/10 border-2 border-amber-400/80 rounded-2xl text-amber-950 text-xs space-y-1.5 shadow-sm">
                    <div class="flex items-center gap-2 font-black text-amber-900 text-sm">
                        <i data-lucide="alert-octagon" class="w-5 h-5 text-amber-600 flex-shrink-0"></i>
                        <span>PERINGATAN PENTING: JANGAN MELAKUKAN PEMBAYARAN GANDA</span>
                    </div>
                    <p class="text-xs text-amber-900/90 leading-relaxed font-medium pl-7">
                        Jika Anda <b>sudah menyelesaikan transfer / pembayaran</b> di aplikasi m-Banking, ATM, QRIS, atau E-Wallet, <b>MOHON JANGAN MEMBAYAR ULANG ATAU MEMBUKA POPUP LAGI</b> agar uang Anda tidak terbayar ganda (*overpayment*). Cukup klik tombol <b>"Sudah Bayar? Cek Status"</b> di bawah untuk memvalidasi pembayaran Anda ke sistem.
                    </p>
                </div>
                @endif

                <!-- Midtrans Automatic Payment Box -->
                @if($order->payment_method === 'midtrans')

                    @if($isPaid)
                    <!-- State 1: Midtrans Paid & Verified -->
                    <div class="bg-emerald-50/90 p-5 sm:p-6 rounded-2xl border border-emerald-300 space-y-4 shadow-sm">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold shadow-sm">
                                    <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm sm:text-base font-extrabold text-emerald-950">Pembayaran Berhasil Terverifikasi!</h3>
                                    <p class="text-xs text-emerald-700">Transaksi Anda telah tercatat dan tervalidasi secara otomatis oleh sistem Midtrans.</p>
                                </div>
                            </div>
                            <span class="text-[11px] font-black tracking-wider uppercase bg-emerald-200/80 text-emerald-900 border border-emerald-300 px-3 py-1 rounded-lg">
                                {{ $order->payment_scheme === 'dp_50' ? 'DP TERVERIFIKASI' : 'LUNAS (100%)' }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-1 text-xs">
                            <div class="p-3 bg-white rounded-xl border border-emerald-100">
                                <span class="text-slate-400 block text-[11px]">Skema Tagihan</span>
                                <b class="text-slate-800">{{ $order->payment_scheme_label }}</b>
                            </div>
                            <div class="p-3 bg-white rounded-xl border border-emerald-100">
                                <span class="text-slate-400 block text-[11px]">Metode Pembayaran</span>
                                <b class="text-slate-800">{{ $order->formatted_payment_type }}</b>
                            </div>
                            <div class="p-3 bg-white rounded-xl border border-emerald-100">
                                <span class="text-slate-400 block text-[11px]">ID Referensi</span>
                                <b class="font-mono text-slate-800 text-[11px] truncate block">{{ $order->midtrans_transaction_id ?: ($order->midtrans_response['transaction_id'] ?? '-') }}</b>
                            </div>
                            <div class="p-3 bg-white rounded-xl border border-emerald-100">
                                <span class="text-slate-400 block text-[11px]">Status SPK Bengkel</span>
                                <b class="text-indigo-700 font-bold">{{ $order->workOrder->spk_number ?? 'Siap di Kanban' }}</b>
                            </div>
                        </div>

                        <div class="p-3.5 bg-emerald-100/60 rounded-xl border border-emerald-200/80 text-xs text-emerald-900 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <i data-lucide="info" class="w-4 h-4 text-emerald-700 flex-shrink-0"></i>
                                <span>Pesanan Anda telah diteruskan ke jadwal pengerjaan workshop pengrajin marmer.</span>
                            </div>
                            <a href="{{ route('order.tracking', ['order_number' => $order->order_number]) }}" class="text-xs font-bold text-emerald-800 hover:text-emerald-950 underline flex items-center gap-1">
                                <i data-lucide="truck" class="w-3.5 h-3.5"></i> Lacak Progres Pengerjaan &rarr;
                            </a>
                        </div>
                    </div>

                    @elseif($order->midtrans_status === 'pending')
                    <!-- State 2: Midtrans Transaction Pending Settlement -->
                    <div class="bg-amber-50/90 p-5 sm:p-6 rounded-2xl border-2 border-amber-300 space-y-4 shadow-sm">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-amber-600 text-white flex items-center justify-center shadow-sm">
                                    <i data-lucide="clock" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-extrabold text-slate-900">Menunggu Penyelesaian Pembayaran</h3>
                                    <p class="text-[11px] text-slate-600">Saluran pembayaran telah dipilih (<b>{{ $order->formatted_payment_type }}</b>). Silakan selesaikan pembayaran pada aplikasi perbankan atau e-wallet Anda.</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-black tracking-wider uppercase bg-amber-200 text-amber-900 border border-amber-300 px-2.5 py-1 rounded-lg">
                                Menunggu Pembayaran
                            </span>
                        </div>

                        <div class="p-5 sm:p-6 bg-white rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-6">
                            <div class="text-center sm:text-left space-y-1">
                                <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block">Tagihan Pembayaran:</span>
                                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Rp {{ number_format($billAmount, 0, ',', '.') }}</span>
                                <div class="flex items-center gap-2 justify-center sm:justify-start pt-0.5">
                                    <span class="text-[11px] font-semibold text-slate-600">Skema: <b>{{ $order->payment_scheme_label }}</b></span>
                                </div>
                            </div>

                            @if(!empty($order->snap_token))
                            <div class="flex flex-col items-center sm:items-end gap-2.5 w-full sm:w-auto">
                                <button type="button" id="pay-button" style="background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%) !important; color: #ffffff !important; box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.45); border: none; padding: 14px 36px; font-size: 14px; font-weight: 800; letter-spacing: 0.5px; border-radius: 16px; min-width: 220px;" class="w-full sm:w-auto text-white transition duration-300 hover:brightness-110 text-center cursor-pointer active:scale-98 flex items-center justify-center gap-2">
                                    <i data-lucide="external-link" class="w-4 h-4"></i> Buka Layar Pembayaran
                                </button>
                                
                                <div class="flex items-center gap-3 flex-wrap justify-center sm:justify-end">
                                    <a href="{{ route('checkout.check-status', $order->order_number) }}" class="text-[11px] text-emerald-700 hover:text-emerald-900 font-bold hover:underline flex items-center gap-1 transition bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200 shadow-xs">
                                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                                        <span>Sudah Bayar? Cek Status</span>
                                    </a>
                                    <a href="{{ route('checkout.regenerate-snap', $order->order_number) }}" onclick="return confirm('Apakah Anda ingin membatalkan saluran saat ini dan memilih metode pembayaran lain?')" class="text-[11px] text-slate-600 hover:text-slate-900 font-semibold hover:underline flex items-center gap-1 transition">
                                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                        <span>Ganti Saluran Bayar</span>
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    @else
                    <!-- State 3: Fresh Midtrans Payment (Initial) -->
                    <div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-slate-50 p-5 sm:p-6 rounded-2xl border-2 border-blue-200 space-y-4 shadow-sm">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm">
                                    <i data-lucide="zap" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-extrabold text-slate-900">Pembayaran Online Instan</h3>
                                    <p class="text-[11px] text-slate-500">QRIS Dinamis (GoPay/ShopeePay/DANA), Virtual Account Bank (BCA, Mandiri, BRI, BNI), atau Kartu Kredit</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-black tracking-wider uppercase bg-emerald-100 text-emerald-800 border border-emerald-200 px-2.5 py-1 rounded-lg">
                                Verifikasi Otomatis
                            </span>
                        </div>

                        <div class="p-5 sm:p-6 bg-white rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-6">
                            <div class="text-center sm:text-left space-y-1">
                                <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block">Tagihan Pembayaran:</span>
                                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Rp {{ number_format($billAmount, 0, ',', '.') }}</span>
                                <div class="flex items-center gap-2 justify-center sm:justify-start pt-0.5">
                                    <span class="text-[11px] font-semibold text-slate-600">Skema: <b>{{ $order->payment_scheme_label }}</b></span>
                                    <span class="inline-block w-1 h-1 rounded-full bg-slate-300"></span>
                                    <span class="text-[11px] text-emerald-600 font-bold">Bebas Kode Unik</span>
                                </div>
                            </div>

                            @if(!empty($order->snap_token))
                            <div class="flex flex-col items-center sm:items-end gap-2.5 w-full sm:w-auto">
                                <button type="button" id="pay-button" style="background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 50%, #3b82f6 100%) !important; color: #ffffff !important; box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.45); border: none; padding: 14px 44px; font-size: 15px; font-weight: 800; letter-spacing: 0.5px; border-radius: 16px; min-width: 220px;" class="w-full sm:w-auto text-white transition duration-300 hover:brightness-110 text-center cursor-pointer active:scale-98 flex items-center justify-center gap-2">
                                    <i data-lucide="credit-card" class="w-4 h-4"></i> Bayar Sekarang
                                </button>
                                
                                <div class="flex items-center gap-3 flex-wrap justify-center sm:justify-end">
                                    <a href="{{ route('checkout.check-status', $order->order_number) }}" class="text-[11px] text-emerald-700 hover:text-emerald-900 font-bold hover:underline flex items-center gap-1 transition bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200 shadow-xs">
                                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                                        <span>Sudah Bayar? Cek Status</span>
                                    </a>
                                    <a href="{{ route('checkout.regenerate-snap', $order->order_number) }}" class="text-[11px] text-blue-600 hover:text-blue-800 font-semibold hover:underline flex items-center gap-1 transition">
                                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                        <span>Ganti Metode</span>
                                    </a>
                                </div>
                            </div>
                            @else
                            <div class="text-xs text-amber-700 bg-amber-50 p-3 rounded-xl border border-amber-200 flex items-center gap-2">
                                <i data-lucide="loader-2" class="w-4 h-4 animate-spin text-amber-600"></i>
                                <span>Sedang menyiapkan link pembayaran... Silakan muat ulang halaman.</span>
                            </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 text-[11px] text-slate-500">
                            <i data-lucide="shield-check" class="w-4 h-4 text-blue-600 flex-shrink-0"></i>
                            <span>Setelah pembayaran selesai dilakukan, sistem akan memverifikasi secara otomatis dan langsung menerbitkan SPK pengerjaan bengkel.</span>
                        </div>
                    </div>
                    @endif

                @else
                <!-- Manual Payment (BCA/BRI/Mandiri/QRIS) -->
                @if($isPaid)
                <div class="bg-emerald-50/90 p-5 sm:p-6 rounded-2xl border border-emerald-300 space-y-3 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold">
                            <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-emerald-950">Pembayaran Manual Telah Dikonfirmasi Admin!</h3>
                            <p class="text-xs text-emerald-700">Bukti pembayaran telah divalidasi dan pesanan Anda sedang dikerjakan di workshop.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 text-xs">
                        <div class="p-3 bg-white rounded-xl border border-emerald-100">
                            <span class="text-slate-400 block text-[11px]">Metode Pembayaran</span>
                            <b class="text-slate-800">{{ $order->payment_method_label }}</b>
                        </div>
                        <div class="p-3 bg-white rounded-xl border border-emerald-100">
                            <span class="text-slate-400 block text-[11px]">Nominal Diterima</span>
                            <b class="font-mono text-emerald-700 font-bold">Rp {{ number_format($order->paid_amount ?: $billAmount, 0, ',', '.') }}</b>
                        </div>
                        <div class="p-3 bg-white rounded-xl border border-emerald-100">
                            <span class="text-slate-400 block text-[11px]">Nomor SPK Produksi</span>
                            <b class="text-indigo-700 font-bold">{{ $order->workOrder->spk_number ?? 'SPK Aktif' }}</b>
                        </div>
                    </div>
                </div>
                @else
                <!-- Manual Payment Instructions Box -->
                <div class="bg-blue-50/60 p-5 sm:p-6 rounded-2xl border border-blue-100 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="credit-card" class="w-5 h-5 text-blue-700"></i>
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Instruksi Pembayaran Manual IKM:</h3>
                        </div>
                        <span class="text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded">
                            {{ $order->payment_method_label }}
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
                            <p class="text-slate-500 text-[11px]">NMID: <b class="font-mono text-slate-800">ID102435890123</b> | Merchant: <b>{{ $artisan['name'] }}</b></p>
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
                        * Mohon transfer dengan nominal tepat hingga 3 digit terakhir. Setelah transfer selesai, kirim bukti ke WhatsApp pengrajin agar SPK pengerjaan di bengkel segera diterbitkan.
                    </p>
                </div>
                @endif
                @endif
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
                                            <div class="w-14 h-14 bg-slate-100 rounded-xl flex-shrink-0 flex items-center justify-center overflow-hidden border border-slate-200">
                                                <img src="{{ asset($order->product->image_path ?: 'images/products/wastafel-marmer-putih.svg') }}" alt="{{ $order->product->name }}" class="w-full h-full object-cover">
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
                                    <td colspan="3" class="p-3 text-right text-[11px]">Packing Peti Kayu Solid (Standar Kirim Aman):</td>
                                    <td class="p-3 text-right font-bold text-emerald-600">GRATIS</td>
                                </tr>
                                @if(!$isMidtrans && $uniqueCode > 0)
                                <tr>
                                    <td colspan="3" class="p-3 text-right text-[11px]">Kode Unik Verifikasi:</td>
                                    <td class="p-3 text-right font-mono text-slate-600">+ Rp {{ $uniqueCode }}</td>
                                </tr>
                                @endif
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
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-1.5 text-xs">
                        <div class="flex items-center justify-between gap-2">
                            <h4 class="font-bold text-slate-900">Tujuan Pengiriman:</h4>
                            @if($order->google_maps_url)
                            <a href="{{ $order->google_maps_url }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-700 hover:text-blue-900 bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-200 shadow-xs hover:underline">
                                <i data-lucide="map-pin" class="w-3 h-3 text-red-500"></i>
                                <span>Buka Google Maps</span>
                            </a>
                            @endif
                        </div>
                        <p class="text-slate-800 font-semibold">{{ $order->receiver_name }} ({{ $order->receiver_phone }})</p>
                        <p class="text-slate-600 leading-relaxed">{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
                        @if($order->latitude && $order->longitude)
                        <div class="pt-1 text-[11px] text-slate-500 flex items-center gap-1.5 font-mono">
                            <span class="bg-slate-200/80 px-2 py-0.5 rounded text-slate-700">GPS: {{ number_format($order->latitude, 6) }}, {{ number_format($order->longitude, 6) }}</span>
                        </div>
                        @endif
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
                        @if($order->workOrder && $order->workOrder->shipment)
                        <p class="text-slate-600">Ekspedisi: <b>{{ $order->workOrder->shipment->expedition_name }}</b> (Resi/SJ: <b class="font-mono text-purple-700">{{ $order->workOrder->shipment->tracking_number ?: $order->workOrder->shipment->shipment_code }}</b>)</p>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Invoice Footer Action Buttons (Hidden on Print) -->
            <div class="no-print bg-slate-50 p-6 sm:p-8 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 flex-wrap justify-center sm:justify-start w-full sm:w-auto">
                    <button onclick="window.print()" class="px-4 py-2.5 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="printer" class="w-4 h-4"></i> Cetak / Simpan PDF
                    </button>
                    <a href="{{ route('order.tracking', ['order_number' => $order->order_number]) }}" class="px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs rounded-xl border border-blue-200 transition flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="truck" class="w-4 h-4"></i> Lacak Progres
                    </a>
                    @php
                        $saveWaText = "Catatan Pesanan E-SCM Marmer Tulungagung:\nNo. Invoice: #{$order->order_number}\nProduk: " . ($order->product->name ?? 'Kerajinan Marmer') . "\nTotal Tagihan: Rp " . number_format($billAmount, 0, ',', '.') . "\nLink Invoice: " . route('checkout.invoice', $order->order_number) . "\nLink Lacak: " . route('order.tracking', ['order_number' => $order->order_number]);
                    @endphp
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($saveWaText) }}" target="_blank" class="px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs rounded-xl border border-emerald-200 transition flex items-center gap-1.5 shadow-sm" title="Simpan catatan link invoice ini ke WhatsApp Anda">
                        <i data-lucide="share-2" class="w-4 h-4 text-emerald-600"></i> Simpan Catatan ke WA
                    </a>
                </div>

                @if(!$order->isCancelled())
                    @if($isPaid)
                    <a href="{{ $waConfirmUrl }}" target="_blank" class="w-full sm:w-auto px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl transition flex items-center justify-center gap-2 shadow-md">
                        <i data-lucide="message-circle" class="w-4 h-4"></i> Hubungi Pengrajin via WhatsApp
                    </a>
                    @else
                    <a href="{{ $waConfirmUrl }}" target="_blank" class="w-full sm:w-auto px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl transition flex items-center justify-center gap-2 shadow-md">
                        <i data-lucide="message-circle" class="w-4 h-4"></i> Konfirmasi Pembayaran via WhatsApp
                    </a>
                    @endif
                @endif
            </div>

        </div>

    </div>
</div>


<!-- =========================================================================
     2. DEDICATED PRINT / PDF INVOICE DOCUMENT (STANDARD A4 OFFICIAL FORMAT)
     ========================================================================= -->
<div class="print-invoice-view">
    
    <!-- Kop Surat Resmi Klaster IKM -->
    <div style="border-bottom: 3px double #0f172a; padding-bottom: 10px; margin-bottom: 14px;">
        <table style="width: 100%; border-collapse: collapse; border: none;">
            <tr>
                <td style="width: 65px; vertical-align: middle; border: none; padding-right: 12px;">
                    <div style="width: 58px; height: 58px; border: 2px solid #1e3a8a; border-radius: 12px; display: flex; align-items: center; justify-content: center; background-color: #f8fafc; text-align: center; line-height: 1;">
                        <span style="font-size: 9pt; font-weight: 900; color: #1e3a8a; display: block; padding-top: 14px;">E-SCM<br><span style="font-size: 7.5pt; color: #475569;">MARMER</span></span>
                    </div>
                </td>
                <td style="vertical-align: middle; border: none; text-align: left;">
                    <div style="font-size: 13pt; font-weight: 900; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.2;">
                        KLASTER IKM KERAJINAN MARMER & ONYX TULUNGAGUNG
                    </div>
                    <div style="font-size: 11pt; font-weight: 800; color: #1e3a8a; margin-top: 2px; line-height: 1.2;">
                        {{ strtoupper($artisan['name']) }} &bull; SENTRA PRODUKSI BATU ALAM CAMPURDARAT
                    </div>
                    <div style="font-size: 8pt; color: #475569; margin-top: 3px; line-height: 1.3;">
                        Sentra Industri Pengrajin Marmer, Jl. Raya Popoh - Campurdarat, Kec. Campurdarat, Kab. Tulungagung, Jawa Timur 66272
                    </div>
                    <div style="font-size: 8pt; color: #475569; line-height: 1.3;">
                        WhatsApp: +62 813-4023-1737 / +62 813-3502-2012 &bull; Email: kontak@scm-marmer-tulungagung.id &bull; Web: www.scm-marmer-tulungagung.id
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Judul Dokumen & Stamp Status Transaksi -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; border-bottom: 1px solid #cbd5e1; padding-bottom: 10px;">
        <div>
            <h2 style="font-size: 13pt; font-weight: 900; color: #0f172a; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">
                FAKTUR PENJUALAN & BUKTI PEMBAYARAN
            </h2>
            <div style="font-size: 9pt; color: #334155; margin-top: 3px;">
                No. Faktur: <strong style="font-family: monospace; font-size: 10pt; color: #0f172a;">#{{ $order->order_number }}</strong>
                &bull; Tanggal: <strong>{{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</strong>
            </div>
        </div>
        <div style="text-align: right;">
            @if($isPaid)
                @if($order->payment_scheme === 'dp_50')
                <span style="display: inline-block; border: 2px solid #059669; background-color: #ecfdf5; color: #065f46; padding: 4px 10px; font-weight: 900; font-size: 9.5pt; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                    DP 50% TERVERIFIKASI
                </span>
                @else
                <span style="display: inline-block; border: 2px solid #059669; background-color: #ecfdf5; color: #065f46; padding: 4px 10px; font-weight: 900; font-size: 9.5pt; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                    LUNAS (100%)
                </span>
                @endif
            @else
                <span style="display: inline-block; border: 2px solid #d97706; background-color: #fffbeb; color: #92400e; padding: 4px 10px; font-weight: 900; font-size: 9.5pt; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                    MENUNGGU PEMBAYARAN
                </span>
            @endif
        </div>
    </div>

    <!-- 2 Kolom: Data Pembeli & Informasi SCM Produksi -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 14px; border: none;">
        <tr>
            <!-- Kolom Kiri: Data Pemesan -->
            <td style="width: 50%; vertical-align: top; border: 1px solid #cbd5e1; padding: 8px 10px; background-color: #f8fafc; border-radius: 4px;">
                <div style="font-size: 8.5pt; font-weight: 800; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin-bottom: 6px;">
                    PEMESAN / DITUJUKAN KEPADA:
                </div>
                <div style="font-size: 9.5pt; font-weight: 800; color: #0f172a;">{{ $order->receiver_name }}</div>
                <div style="font-size: 8.5pt; color: #334155; margin-top: 2px;">No. Telepon / WA: <strong>{{ $order->receiver_phone }}</strong></div>
                <div style="font-size: 8.5pt; color: #334155; margin-top: 2px; line-height: 1.35;">
                    Alamat Pengiriman: {{ $order->shipping_address }}, <strong>{{ $order->shipping_city }}</strong>
                </div>
                @if($order->latitude && $order->longitude)
                <div style="font-size: 7.5pt; color: #475569; margin-top: 3px; font-family: monospace;">
                    Titik GPS Kargo: {{ number_format($order->latitude, 6) }}, {{ number_format($order->longitude, 6) }}
                </div>
                @endif
            </td>
            
            <td style="width: 2%; border: none;"></td>

            <!-- Kolom Kanan: Data Transaksi & Bengkel SCM -->
            <td style="width: 48%; vertical-align: top; border: 1px solid #cbd5e1; padding: 8px 10px; background-color: #f8fafc; border-radius: 4px;">
                <div style="font-size: 8.5pt; font-weight: 800; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin-bottom: 6px;">
                    INFORMASI TRANSAKSI & SCM:
                </div>
                <table style="width: 100%; border-collapse: collapse; border: none; font-size: 8.5pt;">
                    <tr>
                        <td style="border: none; padding: 1.5px 0; color: #64748b; width: 42%;">Skema Tagihan:</td>
                        <td style="border: none; padding: 1.5px 0; color: #0f172a; font-weight: 700;">{{ $order->payment_scheme_label }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 1.5px 0; color: #64748b;">Metode / Saluran:</td>
                        <td style="border: none; padding: 1.5px 0; color: #0f172a; font-weight: 700;">{{ $order->formatted_payment_type }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 1.5px 0; color: #64748b;">No. SPK Bengkel:</td>
                        <td style="border: none; padding: 1.5px 0; color: #1e3a8a; font-weight: 800; font-family: monospace;">{{ $order->workOrder->spk_number ?? 'SPK Menunggu Verifikasi DP' }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 1.5px 0; color: #64748b;">Ekspedisi Logistik:</td>
                        <td style="border: none; padding: 1.5px 0; color: #0f172a;">{{ $order->workOrder && $order->workOrder->shipment ? $order->workOrder->shipment->expedition_name . ' (' . ($order->workOrder->shipment->tracking_number ?: $order->workOrder->shipment->shipment_code) . ')' : 'Standar Kargo Marmer (Peti Kayu)' }}</td>
                    </tr>
                    @if($order->midtrans_transaction_id || isset($order->midtrans_response['transaction_id']))
                    <tr>
                        <td style="border: none; padding: 1.5px 0; color: #64748b;">ID Transaksi Midtrans:</td>
                        <td style="border: none; padding: 1.5px 0; color: #0f172a; font-family: monospace; font-size: 8pt;">{{ $order->midtrans_transaction_id ?: ($order->midtrans_response['transaction_id'] ?? '-') }}</td>
                    </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <!-- Tabel Rincian Pesanan Produk & Perhitungan -->
    <table class="print-table" style="margin-bottom: 12px;">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 45%; text-align: left;">Deskripsi Produk Kerajinan Marmer / Onyx</th>
                <th style="width: 20%; text-align: left;">Spesifikasi & Dimensi</th>
                <th style="width: 8%; text-align: center;">Qty</th>
                <th style="width: 11%; text-align: right;">Harga Satuan</th>
                <th style="width: 11%; text-align: right;">Subtotal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center; vertical-align: top; font-weight: 700;">1</td>
                <td style="vertical-align: top;">
                    <div style="font-weight: 800; color: #0f172a; font-size: 9.5pt;">{{ $order->product->name }}</div>
                    <div style="font-size: 8pt; color: #475569; margin-top: 1px;">Kode Produk: <span style="font-family: monospace; font-weight: 700;">{{ $order->product->product_code }}</span></div>
                    @if($order->custom_notes)
                    <div style="font-size: 8pt; color: #0369a1; margin-top: 2px;"><em>Catatan Custom: {{ $order->custom_notes }}</em></div>
                    @endif
                </td>
                <td style="vertical-align: top; font-size: 8.5pt;">
                    <div>Bahan: <strong>{{ ucwords(str_replace('_', ' ', $order->product->material_type ?? 'Batu Alam')) }}</strong></div>
                    <div style="color: #475569; font-size: 8pt;">Dimensi: {{ $order->product->dimension_spec ?: 'Standar Pengrajin' }}</div>
                </td>
                <td style="text-align: center; vertical-align: top; font-weight: 700;">{{ $order->quantity }} Unit</td>
                <td style="text-align: right; vertical-align: top; font-family: monospace;">{{ number_format($order->unit_price, 0, ',', '.') }}</td>
                <td style="text-align: right; vertical-align: top; font-weight: 800; font-family: monospace;">{{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right; font-weight: 700; background-color: #f8fafc;">Total Nilai Pesanan:</td>
                <td style="text-align: right; font-weight: 700; font-family: monospace; background-color: #f8fafc;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right; font-size: 8.5pt; color: #475569;">Pengemasan Peti Kayu Solid Bertulang (Standar Kargo Nasional):</td>
                <td style="text-align: right; font-weight: 700; color: #059669; font-size: 8.5pt;">GRATIS</td>
            </tr>
            @if(!$isMidtrans && $uniqueCode > 0)
            <tr>
                <td colspan="5" style="text-align: right; font-size: 8.5pt; color: #475569;">Kode Verifikasi Unik:</td>
                <td style="text-align: right; font-family: monospace; font-size: 8.5pt;">+ Rp {{ $uniqueCode }}</td>
            </tr>
            @endif
            <tr style="border-top: 2px solid #0f172a; background-color: #f1f5f9;">
                <td colspan="5" style="text-align: right; font-weight: 900; font-size: 10pt; color: #0f172a; text-transform: uppercase;">
                    {{ $order->payment_scheme === 'dp_50' ? 'Total Tagihan Uang Muka (DP 50%):' : 'Total Tagihan Lunas:' }}
                </td>
                <td style="text-align: right; font-weight: 900; font-size: 10.5pt; font-family: monospace; color: #1e3a8a;">
                    Rp {{ number_format($billAmount, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right; font-size: 8.5pt; color: #334155;">Jumlah Pembayaran Diterima / Terverifikasi:</td>
                <td style="text-align: right; font-weight: 800; font-family: monospace; color: #059669; font-size: 8.5pt;">
                    Rp {{ number_format($order->paid_amount ?: ($isPaid ? $billAmount : 0), 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right; font-size: 8.5pt; font-weight: 700; color: #475569;">Sisa Tagihan (Pelunasan Sebelum Kirim):</td>
                <td style="text-align: right; font-weight: 800; font-family: monospace; font-size: 8.5pt; color: {{ $order->payment_scheme === 'dp_50' ? '#b45309' : '#059669' }};">
                    {{ $order->payment_scheme === 'dp_50' ? 'Rp ' . number_format($order->total_amount * 0.5, 0, ',', '.') : 'Rp 0 (LUNAS)' }}
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Syarat, Ketentuan, & Garansi Pengrajin IKM Marmer -->
    <div class="print-avoid-break" style="border: 1px solid #cbd5e1; border-radius: 4px; padding: 7px 10px; margin-bottom: 14px; background-color: #fafaf9; font-size: 7.5pt; line-height: 1.4; color: #44403c;">
        <strong style="color: #1e3a8a; text-transform: uppercase; display: block; margin-bottom: 2px;">Catatan & Jaminan Garansi Mutu Pengrajin:</strong>
        <ol style="margin: 0; padding-left: 14px;">
            <li>Setiap wastafel dan kerajinan batu alam dibuat secara <em>handmade</em> oleh pengrajin sentra Campurdarat dari 100% batuan alam asli Tulungagung. Karakter serat, corak, dan fosil adalah ciri keaslian batuan alami otentik.</li>
            <li>Pengiriman menggunakan peti kayu solid bertulang (*free solid wooden crate*) untuk menjamin keselamatan barang selama perjalanan ekspedisi kargo nasional.</li>
            <li>Untuk skema Uang Muka (DP 50%), proses produksi di bengkel langsung dimulai sesuai SPK. Pelunasan sisa tagihan dilakukan saat produk selesai QC sebelum serah terima ekspedisi.</li>
        </ol>
    </div>

    <!-- Kolom Tanda Tangan & QR Code Verifikasi -->
    <div class="print-avoid-break">
        <table style="width: 100%; border-collapse: collapse; border: none; font-size: 8.5pt;">
            <tr>
                <!-- QR Code Verifikasi Digital -->
                <td style="width: 38%; vertical-align: top; border: none; padding-right: 10px;">
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        <tr>
                            <td style="width: 70px; vertical-align: top; border: none; padding-right: 8px;">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&data={{ urlencode(route('checkout.invoice', $order->order_number)) }}" alt="QR Verifikasi" style="width: 66px; height: 66px; border: 1px solid #cbd5e1; padding: 2px; background: white; border-radius: 4px;">
                            </td>
                            <td style="vertical-align: top; border: none; font-size: 7.5pt; color: #64748b; line-height: 1.3;">
                                <strong style="color: #0f172a; display: block; margin-bottom: 2px;">Verifikasi Digital:</strong>
                                Pindai QR Code ini dengan kamera ponsel untuk memverifikasi keaslian dokumen faktur & memantau progres pengerjaan di bengkel secara langsung (*Live Tracking*).
                            </td>
                        </tr>
                    </table>
                </td>

                <!-- Tanda Tangan Pembeli -->
                <td style="width: 30%; text-align: center; vertical-align: top; border: none;">
                    <div style="font-weight: 600; color: #475569;">Penerima / Pembeli:</div>
                    <div style="height: 48px;"></div>
                    <div style="font-weight: 800; color: #0f172a; border-bottom: 1px solid #94a3b8; display: inline-block; padding: 0 15px;">
                        {{ $order->receiver_name }}
                    </div>
                </td>

                <!-- Tanda Tangan Pengrajin / Bagian Keuangan -->
                <td style="width: 32%; text-align: center; vertical-align: top; border: none;">
                    <div style="color: #475569;">Tulungagung, {{ now()->translatedFormat('d F Y') }}</div>
                    <div style="font-weight: 700; color: #0f172a; margin-top: 1px;">Pengrajin / Bagian Keuangan IKM,</div>
                    <div style="height: 32px; display: flex; align-items: center; justify-content: center;">
                        <span style="border: 1.5px solid #059669; color: #059669; font-size: 7pt; font-weight: 900; padding: 1px 6px; border-radius: 3px; text-transform: uppercase; transform: rotate(-5deg); display: inline-block;">
                            TERVALIDASI SISTEM E-SCM
                        </span>
                    </div>
                    <div style="font-weight: 800; color: #0f172a; border-bottom: 1px solid #94a3b8; display: inline-block; padding: 0 15px;">
                        {{ $artisan['owner'] }}
                    </div>
                    <div style="font-size: 7.5pt; color: #64748b; margin-top: 1px;">{{ $artisan['name'] }}</div>
                </td>
            </tr>
        </table>

        <!-- Electronic Document Footer Notice -->
        <div style="margin-top: 12px; padding-top: 6px; border-top: 1px solid #e2e8f0; font-size: 7pt; color: #94a3b8; text-align: center;">
            Dokumen ini diterbitkan secara elektronik melalui Sistem Informasi Rantai Pasok Terpadu (E-SCM) Klaster IKM Kerajinan Marmer Tulungagung dan sah secara digital.
        </div>
    </div>

</div>

<!-- Client-side Scripts -->
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);
        alert('Nomor rekening ' + text + ' berhasil disalin!');
    }

    // Simpan otomatis pesanan ini ke localStorage browser agar tidak hilang jika Chrome ditutup/refresh
    (function() {
        try {
            const orderData = {
                number: "{{ $order->order_number }}",
                product: "{{ addslashes($order->product->name ?? 'Kerajinan Marmer') }}",
                amount: "Rp {{ number_format($billAmount, 0, ',', '.') }}",
                date: "{{ $order->created_at->translatedFormat('d M Y') }}",
                status: "{{ $order->order_status_label }}",
                statusClass: "{{ $order->status_badge_class }}",
                invoiceUrl: "{{ route('checkout.invoice', $order->order_number) }}",
                trackUrl: "{{ route('order.tracking', ['order_number' => $order->order_number]) }}"
            };

            let orders = JSON.parse(localStorage.getItem('scm_recent_orders') || '[]');
            if (!Array.isArray(orders)) orders = [];
            orders = orders.filter(o => o.number !== orderData.number);
            orders.unshift(orderData);
            localStorage.setItem('scm_recent_orders', JSON.stringify(orders.slice(0, 5)));
        } catch (e) {
            console.error('Failed to cache order to localStorage:', e);
        }
    })();
</script>

@if($order->payment_method === 'midtrans' && !empty($order->snap_token) && !$isPaid && !$order->isCancelled())
<script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const payBtn = document.getElementById('pay-button');

        function openSnapPayment() {
            if (typeof snap === 'undefined') {
                alert('Midtrans Snap SDK sedang dimuat. Silakan tunggu beberapa detik dan coba lagi.');
                return;
            }
            snap.pay('{{ $order->snap_token }}', {
                onSuccess: function(result) {
                    const query = new URLSearchParams({
                        status_code: (result && result.status_code) || '200',
                        transaction_status: (result && result.transaction_status) || 'settlement',
                        transaction_id: (result && result.transaction_id) || '',
                        payment_type: (result && result.payment_type) || '',
                        fraud_status: (result && result.fraud_status) || 'accept'
                    }).toString();
                    window.location.href = "{{ route('checkout.check-status', $order->order_number) }}?" + query;
                },
                onPending: function(result) {
                    const query = new URLSearchParams({
                        status_code: (result && result.status_code) || '201',
                        transaction_status: (result && result.transaction_status) || 'pending',
                        transaction_id: (result && result.transaction_id) || '',
                        payment_type: (result && result.payment_type) || ''
                    }).toString();
                    window.location.href = "{{ route('checkout.check-status', $order->order_number) }}?" + query;
                },
                onError: function(result) {
                    alert('Terjadi kendala pada proses pembayaran. Silakan coba kembali.');
                },
                onClose: function() {
                    // Modal ditutup oleh user
                }
            });
        }

        if (payBtn) {
            payBtn.addEventListener('click', function (e) {
                e.preventDefault();
                @if($order->midtrans_status === 'pending')
                if (!confirm("PERHATIAN:\nJika Anda sudah melakukan transfer/pembayaran di m-Banking atau ATM, mohon JANGAN bayar lagi agar tidak terjadi kelebihan bayar.\n\nKlik OK jika Anda belum mentransfer dan ingin membuka sesi pembayaran.")) {
                    return;
                }
                @endif
                openSnapPayment();
            });
        }

        @if(request()->has('pay'))
        // Buka otomatis popup setelah user memilih reset/ganti metode
        setTimeout(function() {
            openSnapPayment();
        }, 400);
        @endif
    });
</script>
@endif

@endsection
