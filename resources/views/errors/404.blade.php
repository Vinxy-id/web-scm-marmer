@extends('layouts.public')

@section('title', '404 - Halaman Tidak Ditemukan | Onyx Tulungagung')
@section('meta-description', 'Maaf, halaman atau produk kerajinan marmer yang Anda cari tidak dapat ditemukan. Silakan telusuri katalog produk kami.')

@section('robots')
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('no_canonical', 'true')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl w-full text-center space-y-8 bg-white p-8 sm:p-12 rounded-3xl border border-slate-200 shadow-sm">
        
        <!-- Error Badge & Icon -->
        <div class="space-y-4">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold uppercase tracking-wider">
                <i data-lucide="compass" class="w-3.5 h-3.5 text-amber-600"></i>
                <span>Status 404 &bull; Tautan Tidak Ditemukan</span>
            </div>
            
            <div class="relative mx-auto w-24 h-24 sm:w-28 sm:h-28 rounded-3xl bg-gradient-to-br from-blue-900 via-indigo-900 to-slate-900 flex items-center justify-center text-white shadow-xl shadow-blue-900/20">
                <span class="text-4xl sm:text-5xl font-black text-amber-400 font-mono">404</span>
            </div>
        </div>

        <!-- Copywriting Contextual to Marble & Handicraft IKM -->
        <div class="space-y-3">
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Halaman atau Produk Tidak Ditemukan
            </h1>
            <p class="text-sm text-slate-600 leading-relaxed max-w-md mx-auto">
                Maaf, tautan yang Anda tuju mungkin telah berpindah, kode produk telah diperbarui, atau alamat URL salah ketik.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition shadow-xs">
                <i data-lucide="home" class="w-4 h-4"></i>
                <span>Kembali ke Beranda</span>
            </a>
            
            <a href="{{ route('catalog') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-blue-700 hover:bg-blue-600 text-white text-xs font-bold transition shadow-md shadow-blue-700/20">
                <i data-lucide="layout-grid" class="w-4 h-4"></i>
                <span>Jelajahi Katalog Produk</span>
            </a>
            
            <a href="https://wa.me/6281340231737?text=Halo%20Pengrajin%20Onyx%20Tulungagung,%20saya%20mencari%20produk%20di%20website%20tetapi%20halaman%20tidak%20ditemukan." target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition shadow-md shadow-emerald-600/20">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
                <span>Tanya via WhatsApp</span>
            </a>
        </div>

        <!-- Quick Help Note -->
        <div class="pt-6 border-t border-slate-100 text-xs text-slate-400">
            Butuh bantuan cepat atau pesanan custom khusus? Hubungi sentra pengrajin di <b>0813-4023-1737</b> (UD Cahaya Onix) atau <b>0813-3502-2012</b> (UD Putra Abadi).
        </div>

    </div>
</div>
@endsection
