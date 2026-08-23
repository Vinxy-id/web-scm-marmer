@extends('layouts.public')

@section('title', 'Checkout Pemesanan - ' . $product->name)

@section('content')
<!-- Breadcrumb -->
<div class="bg-slate-900 text-white py-6 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
            <span>/</span>
            <a href="{{ route('catalog') }}" class="hover:text-white transition">Katalog</a>
            <span>/</span>
            <a href="{{ route('catalog.show', $product->id) }}" class="hover:text-white transition">{{ $product->name }}</a>
            <span>/</span>
            <span class="text-blue-400 font-semibold">Checkout Pemesanan Online</span>
        </div>
    </div>
</div>

<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Formulir Pemesanan & Checkout E-Commerce
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pemesanan langsung ke pengrajin IKM Marmer & Onyx Tulungagung dengan proteksi packing peti kayu solid dan pelacakan pesanan digital.
            </p>
        </div>

        @if($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-xs">
            <div class="flex items-center gap-2 font-bold mb-1">
                <i data-lucide="alert-circle" class="w-4 h-4"></i> Mohon lengkapi data pemesanan berikut:
            </div>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left: Shipping & Payment Details (8 cols) -->
                <div class="lg:col-span-7 space-y-6">

                    <!-- Section 1: Receiver Details -->
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                        <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-xs">1</div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Data Penerima & Alamat Pengiriman</h3>
                                <p class="text-[11px] text-slate-400">Pastikan nomor WhatsApp aktif untuk konfirmasi foto real pic corak batu.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Penerima *</label>
                                <input type="text" 
                                       name="receiver_name" 
                                       value="{{ old('receiver_name') }}" 
                                       placeholder="Contoh: Bpk. Hendra Gunawan"
                                       required
                                       class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 p-3 bg-slate-50/50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp / HP Aktif *</label>
                                <input type="text" 
                                       name="receiver_phone" 
                                       value="{{ old('receiver_phone') }}" 
                                       placeholder="081234567890"
                                       required
                                       class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 p-3 bg-slate-50/50">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kota / Kabupaten Tujuan *</label>
                            <input type="text" 
                                   name="shipping_city" 
                                   value="{{ old('shipping_city') }}" 
                                   placeholder="Contoh: Surabaya, Jakarta Selatan, Denpasar, Yogyakarta"
                                   required
                                   class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 p-3 bg-slate-50/50">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap Pengiriman Kargo *</label>
                            <textarea name="shipping_address" 
                                      rows="3" 
                                      placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, dan patokan lokasi"
                                      required
                                      class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 p-3 bg-slate-50/50">{{ old('shipping_address') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Kustom Motif / Serat Batu (Opsional)</label>
                            <input type="text" 
                                   name="custom_notes" 
                                   value="{{ old('custom_notes') }}" 
                                   placeholder="Contoh: Minta corak serat marmer dominan abu-abu / onyx transparan tembus"
                                   class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 p-3 bg-slate-50/50">
                        </div>
                    </div>

                    <!-- Section 2: Payment Scheme (DP 50% vs Lunas) -->
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                        <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-xs">2</div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Pilihan Skema Pembayaran</h3>
                                <p class="text-[11px] text-slate-400">Fleksibilitas uang muka untuk produk kerajinan batu alam.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Option 1: DP 50% -->
                            <label class="relative flex flex-col p-4 bg-slate-50 hover:bg-blue-50/40 rounded-2xl border-2 border-slate-200 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 cursor-pointer transition">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-extrabold text-slate-900">DP 50% (Uang Muka)</span>
                                    <input type="radio" name="payment_scheme" value="dp_50" checked class="text-blue-600 focus:ring-blue-500 h-4 w-4" onchange="updateCalculations()">
                                </div>
                                <p class="text-[11px] text-slate-500 mt-2 leading-tight">
                                    Bayar 50% untuk mulai pengerjaan di bengkel. Pelunasan sisa saat barang lulus QC & siap kirim.
                                </p>
                                <span class="mt-3 text-[10px] font-bold text-blue-700 bg-blue-100/60 px-2 py-0.5 rounded-md inline-block w-max">
                                    Populer untuk Custom/PO
                                </span>
                            </label>

                            <!-- Option 2: Full 100% -->
                            <label class="relative flex flex-col p-4 bg-slate-50 hover:bg-blue-50/40 rounded-2xl border-2 border-slate-200 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 cursor-pointer transition">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-extrabold text-slate-900">Lunas 100% (Full Payment)</span>
                                    <input type="radio" name="payment_scheme" value="full_100" class="text-blue-600 focus:ring-blue-500 h-4 w-4" onchange="updateCalculations()">
                                </div>
                                <p class="text-[11px] text-slate-500 mt-2 leading-tight">
                                    Pembayaran penuh sekaligus. Prioritas antrean pembuatan peti kayu & proses kargo kilat.
                                </p>
                                <span class="mt-3 text-[10px] font-bold text-emerald-700 bg-emerald-100/60 px-2 py-0.5 rounded-md inline-block w-max">
                                    Prioritas Kargo Kilat
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Section 3: Payment Methods -->
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                        <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-xs">3</div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Pilih Metode Pembayaran</h3>
                                <p class="text-[11px] text-slate-400">Pembayaran langsung ke rekening resmi IKM Tulungagung.</p>
                            </div>
                        </div>

                        <!-- CHK-04 SOLVED: Pre-Order Lead Time Info -->
                        @if(($product->ready_stock ?? 0) <= 0)
                        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-xs text-amber-900 space-y-1">
                            <div class="flex items-center gap-2 font-bold text-amber-800">
                                <i data-lucide="clock" class="w-4 h-4 text-amber-600"></i>
                                <span>Status Pemesanan: Pre-Order Pengrajin</span>
                            </div>
                            <p class="text-[11px] text-amber-700 leading-relaxed">
                                Unit siap kirim saat ini sedang kosong. Pesanan Anda akan dikerjakan langsung oleh pengrajin sentra Campurdarat dengan estimasi pengerjaan <b>3–7 hari kerja</b> setelah pembayaran diverifikasi.
                            </p>
                        </div>
                        @endif

                        <!-- Payment Method Options (CHK-02 & CHK-03 SOLVED: RENDER $banks FROM CONTROLLER) -->
                        <div class="space-y-3">
                            <!-- QRIS -->
                            <label class="flex items-center justify-between p-3.5 bg-slate-50 hover:bg-slate-100/80 rounded-2xl border border-slate-200 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/30 cursor-pointer transition">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="qris" checked class="text-blue-600 focus:ring-blue-500 h-4 w-4">
                                    <div>
                                        <p class="text-xs font-bold text-slate-900">QRIS Instan (Semua Bank & E-Wallet)</p>
                                        <p class="text-[10px] text-slate-500">BCA, Mandiri, BRI, BNI, GoPay, OVO, ShopeePay, DANA</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold bg-slate-200 text-slate-700 px-2 py-0.5 rounded">Auto QR</span>
                            </label>

                            <!-- Dynamic Bank Options -->
                            @foreach($banks as $bKey => $bank)
                            <label class="flex items-center justify-between p-3.5 bg-slate-50 hover:bg-slate-100/80 rounded-2xl border border-slate-200 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/30 cursor-pointer transition">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="{{ $bKey }}" class="text-blue-600 focus:ring-blue-500 h-4 w-4">
                                    <div>
                                        <p class="text-xs font-bold text-slate-900">Transfer {{ $bank['name'] }}</p>
                                        <p class="text-[10px] text-slate-500">Rek: <b class="font-mono text-slate-800">{{ $bank['number'] }}</b> a/n {{ $bank['holder'] }}</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-blue-700 uppercase">{{ str_replace('bank_', '', $bKey) }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                </div>

                <!-- Right: Order Summary Sticky Card (4 cols) -->
                <div class="lg:col-span-5 space-y-6">
                    
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm sticky top-6 space-y-6">
                        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center justify-between">
                            <span>Ringkasan Pesanan</span>
                            <span class="text-[11px] font-mono text-slate-400">{{ $product->product_code }}</span>
                        </h3>

                        <!-- Product Mini Card -->
                        <div class="flex gap-4 items-center">
                            <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center flex-shrink-0 border border-slate-200 overflow-hidden">
                                <img src="{{ asset($product->image_path ?: 'images/products/wastafel-marmer-putih-b1.webp') }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-blue-700 uppercase bg-blue-50 px-2 py-0.5 rounded">
                                    {{ $product->category->name ?? 'Kerajinan' }}
                                </span>
                                <h4 class="text-xs font-bold text-slate-900 leading-tight">{{ $product->name }}</h4>
                                <p class="text-[11px] text-slate-500">Pengrajin: {{ $artisan['name'] }}</p>
                                <p class="text-xs font-black text-slate-900">Rp {{ number_format($product->selling_price, 0, ',', '.') }} / unit</p>
                            </div>
                        </div>

                        <!-- Quantity Selector (CHK-05 SOLVED: EDITABLE & CLAMPED) -->
                        <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700">Jumlah Pesanan:</span>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="changeQty(-1)" class="w-8 h-8 rounded-xl bg-white border border-slate-300 font-bold text-slate-700 hover:bg-slate-100 flex items-center justify-center">-</button>
                                <input type="number" id="qty-input" name="quantity" value="1" min="1" max="50" onchange="onQtyChange(this.value)" class="w-14 text-center text-xs font-bold rounded-xl border-slate-300 p-1.5 focus:ring-2 focus:ring-blue-500 outline-none">
                                <button type="button" onclick="changeQty(1)" class="w-8 h-8 rounded-xl bg-white border border-slate-300 font-bold text-slate-700 hover:bg-slate-100 flex items-center justify-center">+</button>
                            </div>
                        </div>

                        <!-- Cost Breakdown -->
                        <div class="space-y-2.5 text-xs text-slate-600 border-t border-slate-100 pt-4">
                            <div class="flex justify-between">
                                <span>Subtotal Produk</span>
                                <span class="font-bold text-slate-900" id="txt-subtotal">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Packing Peti Kayu Solid</span>
                                <span class="font-bold text-emerald-600">GRATIS (Standar Ekspedisi)</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Estimasi Ongkos Kargo</span>
                                <span class="text-slate-500">Dikonfirmasi via WhatsApp</span>
                            </div>
                            <div class="flex justify-between border-t border-slate-100 pt-3 text-sm">
                                <span class="font-bold text-slate-900" id="lbl-tagihan">Total Tagihan (DP 50%):</span>
                                <span class="font-black text-blue-900 text-lg" id="txt-total">Rp {{ number_format($product->selling_price * 0.5, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Protections / Guarantees -->
                        <div class="bg-blue-50/60 p-3.5 rounded-2xl border border-blue-100 space-y-1.5 text-[11px] text-blue-900">
                            <div class="flex items-center gap-1.5 font-bold">
                                <i data-lucide="shield-check" class="w-4 h-4 text-blue-700"></i>
                                <span>Jaminan Transaksi Aman E-SCM</span>
                            </div>
                            <p class="text-blue-800/80 leading-tight">
                                Setiap pesanan langsung terbit nomor resi SPK digital dan otomatis masuk ke antrean pengerjaan bengkel.
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-blue-700 hover:bg-blue-600 text-white font-extrabold text-xs sm:text-sm py-4 px-6 rounded-2xl transition shadow-lg shadow-blue-700/20 flex items-center justify-center gap-2">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                            <span>Konfirmasi & Buat Pesanan</span>
                        </button>

                        <div class="text-center">
                            <a href="{{ route('catalog.show', $product->id) }}" class="text-[11px] font-bold text-slate-400 hover:text-slate-600 transition">
                                &larr; Kembali ke Detail Produk
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </form>

    </div>
</div>

<script>
    const basePrice = {{ (float) $product->selling_price }};
    let currentQty = 1;

    function changeQty(delta) {
        currentQty = Math.max(1, Math.min(50, currentQty + delta));
        document.getElementById('qty-input').value = currentQty;
        updateCalculations();
    }

    function onQtyChange(val) {
        let parsed = parseInt(val, 10);
        if (isNaN(parsed) || parsed < 1) parsed = 1;
        if (parsed > 50) parsed = 50;
        currentQty = parsed;
        document.getElementById('qty-input').value = currentQty;
        updateCalculations();
    }

    function updateCalculations() {
        const scheme = document.querySelector('input[name="payment_scheme"]:checked')?.value || 'dp_50';
        const subtotal = basePrice * currentQty;
        
        document.getElementById('txt-subtotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');

        if (scheme === 'dp_50') {
            const dp = subtotal * 0.5;
            document.getElementById('lbl-tagihan').innerText = 'Total Tagihan (DP 50%):';
            document.getElementById('txt-total').innerText = 'Rp ' + dp.toLocaleString('id-ID');
        } else {
            document.getElementById('lbl-tagihan').innerText = 'Total Tagihan (Lunas 100%):';
            document.getElementById('txt-total').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        }
    }
</script>
@endsection
