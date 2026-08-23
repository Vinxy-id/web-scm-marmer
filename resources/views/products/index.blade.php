@extends('layouts.app')

@section('title', 'Katalog & Manajemen Produk')

@section('content')
<div class="space-y-6">

    <!-- 1. HEADER & ACTION BUTTONS -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="p-2 bg-blue-100 text-blue-700 rounded-xl">
                    <i data-lucide="package" class="w-5 h-5"></i>
                </span>
                <h1 class="text-xl font-bold text-slate-900">Katalog & Manajemen Produk</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Kelola master produk, upload foto etalase riil 1:1, pengaturan harga jual, dan monitoring stok siap kirim.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('catalog') }}" target="_blank" class="text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 px-3.5 py-2.5 rounded-xl transition flex items-center gap-1.5">
                <i data-lucide="external-link" class="w-4 h-4 text-slate-500"></i>
                <span>Lihat Etalase Publik</span>
            </a>
            <button type="button" onclick="openAddProductModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition shadow-md shadow-blue-600/20 flex items-center gap-1.5">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Tambah Produk Baru</span>
            </button>
        </div>
    </div>

    <!-- 2. STATS CARDS -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] text-slate-400 font-semibold block">Total Produk</span>
            <p class="text-xl font-black text-slate-900 mt-0.5">{{ number_format($stats['total_products']) }} <span class="text-xs font-normal text-slate-500">SKU</span></p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] text-slate-400 font-semibold block">Stok Siap Kirim</span>
            <p class="text-xl font-black text-emerald-600 mt-0.5">{{ number_format($stats['total_stock']) }} <span class="text-xs font-normal text-slate-500">Unit</span></p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] text-slate-400 font-semibold block">Stok Kritis / Habis</span>
            <p class="text-xl font-black text-amber-600 mt-0.5">{{ number_format($stats['low_stock']) }} <span class="text-xs font-normal text-slate-500">Item</span></p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] text-slate-400 font-semibold block">Koleksi Marmer</span>
            <p class="text-xl font-black text-blue-600 mt-0.5">{{ number_format($stats['marmer_count']) }} <span class="text-xs font-normal text-slate-500">SKU</span></p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] text-slate-400 font-semibold block">Koleksi Batu Kali</span>
            <p class="text-xl font-black text-teal-600 mt-0.5">{{ number_format($stats['batu_kali_count']) }} <span class="text-xs font-normal text-slate-500">SKU</span></p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] text-slate-400 font-semibold block">Koleksi Onix</span>
            <p class="text-xl font-black text-amber-500 mt-0.5">{{ number_format($stats['onix_count']) }} <span class="text-xs font-normal text-slate-500">SKU</span></p>
        </div>
    </div>

    <!-- 3. FILTER & SEARCH BAR -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <!-- Search -->
            <div class="sm:col-span-5 relative">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk, kode PRD, dimensi..." class="w-full pl-9 pr-3 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- Category Filter -->
            <div class="sm:col-span-3">
                <select name="category" class="w-full py-2 px-3 text-xs border border-slate-200 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="all">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Material Filter -->
            <div class="sm:col-span-2">
                <select name="material" class="w-full py-2 px-3 text-xs border border-slate-200 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="all">Semua Bahan</option>
                    <option value="marmer" {{ request('material') == 'marmer' ? 'selected' : '' }}>Marmer</option>
                    <option value="batu_kali" {{ request('material') == 'batu_kali' ? 'selected' : '' }}>Batu Kali</option>
                    <option value="onix" {{ request('material') == 'onix' ? 'selected' : '' }}>Onix</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs py-2 px-3 rounded-xl transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'category', 'material']))
                <a href="{{ route('products.index') }}" class="p-2 text-slate-400 hover:text-slate-700 bg-slate-100 rounded-xl" title="Reset Filter">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 4. PRODUCTS DATA TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs divide-y divide-slate-200">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="p-3.5 text-center w-16">Foto</th>
                        <th class="p-3.5">Kode & Nama Produk</th>
                        <th class="p-3.5">Kategori / Bahan</th>
                        <th class="p-3.5">Spesifikasi Teknis</th>
                        <th class="p-3.5 text-right">HPP (COGS)</th>
                        <th class="p-3.5 text-right">Harga Jual</th>
                        <th class="p-3.5 text-center">Stok Ready</th>
                        <th class="p-3.5 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $p)
                    <tr class="hover:bg-slate-50/80 transition">
                        <!-- Foto 1:1 -->
                        <td class="p-3.5 text-center">
                            <div class="w-12 h-12 aspect-square rounded-xl bg-slate-100 border border-slate-200 overflow-hidden mx-auto flex items-center justify-center">
                                <img src="{{ asset($p->image_path ?: 'images/products/wastafel-marmer-putih.svg') }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                            </div>
                        </td>

                        <!-- Kode & Nama -->
                        <td class="p-3.5">
                            <span class="font-mono font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded text-[11px]">{{ $p->product_code }}</span>
                            <p class="font-bold text-slate-900 text-xs mt-1">{{ $p->name }}</p>
                        </td>

                        <!-- Kategori / Bahan -->
                        <td class="p-3.5">
                            <span class="font-semibold text-slate-800 block">{{ $p->category->name ?? 'Kerajinan' }}</span>
                            <span class="inline-block mt-0.5 text-[10px] font-medium px-2 py-0.5 rounded-full capitalize
                                @if($p->material_type === 'marmer') bg-blue-100 text-blue-800
                                @elseif($p->material_type === 'onix') bg-amber-100 text-amber-800
                                @else bg-emerald-100 text-emerald-800 @endif">
                                {{ str_replace('_', ' ', $p->material_type) }}
                            </span>
                        </td>

                        <!-- Spesifikasi -->
                        <td class="p-3.5 text-slate-600">
                            <p><span class="text-slate-400">Dimensi:</span> <b class="text-slate-800">{{ $p->dimension_spec ?: '-' }}</b></p>
                            <p class="text-[11px] mt-0.5"><span class="text-slate-400">Finishing:</span> {{ $p->finishing_type ?: 'Hi-Glossy' }}</p>
                        </td>

                        <!-- HPP -->
                        <td class="p-3.5 text-right font-mono text-slate-600">
                            Rp {{ number_format($p->standard_cogs, 0, ',', '.') }}
                        </td>

                        <!-- Harga Jual -->
                        <td class="p-3.5 text-right font-mono font-bold text-slate-900">
                            Rp {{ number_format($p->selling_price, 0, ',', '.') }}
                        </td>

                        <!-- Stok Ready -->
                        <td class="p-3.5 text-center">
                            @if($p->ready_stock <= $p->safety_stock)
                            <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 font-bold px-2 py-1 rounded-lg text-[11px]">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span>
                                {{ $p->ready_stock }} Unit
                            </span>
                            <span class="block text-[9px] text-red-500 mt-0.5">Min: {{ $p->safety_stock }}</span>
                            @else
                            <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 font-bold px-2 py-1 rounded-lg text-[11px]">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                {{ $p->ready_stock }} Unit
                            </span>
                            <span class="block text-[9px] text-slate-400 mt-0.5">Min: {{ $p->safety_stock }}</span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="p-3.5 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button type="button" onclick="openEditProductModal({{ json_encode($p) }})" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition" title="Edit Produk">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>
                                <form action="{{ route('products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ $p->name }} ({{ $p->product_code }})?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition" title="Hapus Produk">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-slate-400">
                            <i data-lucide="package-search" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                            <p class="font-semibold">Belum ada produk yang cocok dengan pencarian.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $products->links() }}
        </div>
        @endif
    </div>

</div>

<!-- ============================================================================ -->
<!-- MODAL: TAMBAH PRODUK BARU                                                    -->
<!-- ============================================================================ -->
<div id="modal-add-product" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto custom-scrollbar shadow-2xl border border-slate-200 relative animate-in fade-in zoom-in duration-150">
        
        <div class="flex items-center justify-between p-5 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="p-2 bg-blue-100 text-blue-700 rounded-xl">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                </span>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Tambah Produk Baru</h3>
                    <p class="text-[11px] text-slate-500">Nomor Kode Produk dibuat otomatis oleh sistem secara berurutan.</p>
                </div>
            </div>
            <button onclick="closeAddProductModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            <!-- Auto-generated Code Banner -->
            <div class="bg-blue-50/80 border border-blue-200 rounded-xl p-3 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-4 h-4 text-blue-600"></i>
                    <span class="text-xs text-blue-900 font-semibold">Penomoran Sistem:</span>
                </div>
                <span class="font-mono text-xs font-bold text-blue-700 bg-white px-2 py-0.5 rounded border border-blue-200">
                    Otomatis Berurutan (PRD-XXX)
                </span>
            </div>

            <!-- Nama & Kategori -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Wastafel Marmer Putih B1 Polished" class="w-full text-xs p-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Produk <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full text-xs p-2.5 border rounded-xl bg-white focus:ring-2 focus:ring-blue-500">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Bahan & Dimensi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Bahan Alam <span class="text-red-500">*</span></label>
                    <select name="material_type" required class="w-full text-xs p-2.5 border rounded-xl bg-white focus:ring-2 focus:ring-blue-500">
                        <option value="marmer">Batu Marmer</option>
                        <option value="onix">Batu Onix</option>
                        <option value="batu_kali">Batu Kali Alami</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Spesifikasi Dimensi</label>
                    <input type="text" name="dimension_spec" placeholder="Contoh: D: 40 cm, T: 15 cm" class="w-full text-xs p-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Finishing & Stok -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tipe Finishing</label>
                    <input type="text" name="finishing_type" placeholder="Contoh: Hi-Glossy 95 GU" class="w-full text-xs p-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Stok Awal (Unit) <span class="text-red-500">*</span></label>
                    <input type="number" name="ready_stock" value="0" min="0" required class="w-full text-xs p-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Safety Stock (Min) <span class="text-red-500">*</span></label>
                    <input type="number" name="safety_stock" value="2" min="0" required class="w-full text-xs p-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Harga HPP & Jual -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">HPP Standar (COGS Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="standard_cogs" value="250000" min="0" step="1000" required class="w-full text-xs p-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="selling_price" value="450000" min="0" step="1000" required class="w-full text-xs p-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Upload Foto Produk 1:1 -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Upload Foto Produk Asli (Rasio 1:1 Kotak)</label>
                <input type="file" name="image" accept="image/*" class="w-full text-xs p-2 border rounded-xl file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-[10px] text-slate-400 mt-1">Disarankan format JPG/PNG rasio 1:1 (persegi). File otomatis tersimpan di folder public sistem.</p>
            </div>

            <div class="flex justify-end gap-2.5 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeAddProductModal()" class="text-xs px-4 py-2.5 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 font-semibold">
                    Batal
                </button>
                <button type="submit" class="text-xs px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================================ -->
<!-- MODAL: EDIT PRODUK                                                           -->
<!-- ============================================================================ -->
<div id="modal-edit-product" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto custom-scrollbar shadow-2xl border border-slate-200 relative animate-in fade-in zoom-in duration-150">
        
        <div class="flex items-center justify-between p-5 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="p-2 bg-amber-100 text-amber-700 rounded-xl">
                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                </span>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Edit Data Produk</h3>
                    <p id="edit-product-code-display" class="text-[11px] font-mono text-blue-600 font-bold">PRD-xxx</p>
                </div>
            </div>
            <button onclick="closeEditProductModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="form-edit-product" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" id="edit-name" name="name" required class="w-full text-xs p-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Produk <span class="text-red-500">*</span></label>
                    <select id="edit-category_id" name="category_id" required class="w-full text-xs p-2.5 border rounded-xl bg-white focus:ring-2 focus:ring-blue-500">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Bahan Alam <span class="text-red-500">*</span></label>
                    <select id="edit-material_type" name="material_type" required class="w-full text-xs p-2.5 border rounded-xl bg-white focus:ring-2 focus:ring-blue-500">
                        <option value="marmer">Batu Marmer</option>
                        <option value="onix">Batu Onix</option>
                        <option value="batu_kali">Batu Kali Alami</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Spesifikasi Dimensi</label>
                    <input type="text" id="edit-dimension_spec" name="dimension_spec" class="w-full text-xs p-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tipe Finishing</label>
                    <input type="text" id="edit-finishing_type" name="finishing_type" class="w-full text-xs p-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Stok Ready (Unit) <span class="text-red-500">*</span></label>
                    <input type="number" id="edit-ready_stock" name="ready_stock" min="0" required class="w-full text-xs p-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Safety Stock <span class="text-red-500">*</span></label>
                    <input type="number" id="edit-safety_stock" name="safety_stock" min="0" required class="w-full text-xs p-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">HPP Standar (COGS Rp) <span class="text-red-500">*</span></label>
                    <input type="number" id="edit-standard_cogs" name="standard_cogs" min="0" required class="w-full text-xs p-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" id="edit-selling_price" name="selling_price" min="0" required class="w-full text-xs p-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Ganti Foto Produk (Opsional)</label>
                <input type="file" name="image" accept="image/*" class="w-full text-xs p-2 border rounded-xl file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-[10px] text-slate-400 mt-1">Biarkan kosong jika tidak ingin mengubah foto produk saat ini.</p>
            </div>

            <div class="flex justify-end gap-2.5 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeEditProductModal()" class="text-xs px-4 py-2.5 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 font-semibold">
                    Batal
                </button>
                <button type="submit" class="text-xs px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl shadow-md transition">
                    Perbarui Produk
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openAddProductModal() {
        document.getElementById('modal-add-product').classList.remove('hidden');
    }

    function closeAddProductModal() {
        document.getElementById('modal-add-product').classList.add('hidden');
    }

    function openEditProductModal(product) {
        document.getElementById('form-edit-product').action = `{{ url('/products') }}/${product.id}`;
        document.getElementById('edit-product-code-display').innerText = product.product_code;
        document.getElementById('edit-name').value = product.name;
        document.getElementById('edit-category_id').value = product.category_id;
        document.getElementById('edit-material_type').value = product.material_type;
        document.getElementById('edit-dimension_spec').value = product.dimension_spec || '';
        document.getElementById('edit-finishing_type').value = product.finishing_type || '';
        document.getElementById('edit-ready_stock').value = product.ready_stock;
        document.getElementById('edit-safety_stock').value = product.safety_stock;
        document.getElementById('edit-standard_cogs').value = Math.round(product.standard_cogs);
        document.getElementById('edit-selling_price').value = Math.round(product.selling_price);

        document.getElementById('modal-edit-product').classList.remove('hidden');
    }

    function closeEditProductModal() {
        document.getElementById('modal-edit-product').classList.add('hidden');
    }
</script>
@endsection
