<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pengrajin Marmer & Onyx Tulungagung | Wastafel Batu Alam')</title>
    <meta name="description" content="@yield('meta-description', 'Pusat pengrajin wastafel marmer, onyx tembus cahaya & batu kali Campurdarat Tulungagung. Melayani pesanan custom, harga tangan pertama & peti kayu aman.')">
    <meta name="keywords" content="pengrajin marmer tulungagung, jual wastafel marmer, wastafel onyx tembus cahaya, wastafel batu kali, stepping stone taman, harga marmer tulungagung, UD Cahaya Onix, UD Putra Abadi, marmer campurdarat, kerajinan batu alam">
    <meta name="author" content="Klaster IKM Marmer & Onyx Tulungagung">
    <meta name="robots" content="index, follow">
    <meta name="google-site-verification" content="google32f15ef73219ac0b">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook / WhatsApp SEO -->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="Onyx Tulungagung">
    <meta property="og:title" content="@yield('title', 'Pengrajin Marmer & Onyx Tulungagung | Wastafel Batu Alam')">
    <meta property="og:description" content="@yield('meta-description', 'Pusat pengrajin wastafel marmer, onyx tembus cahaya & batu kali Campurdarat Tulungagung. Melayani pesanan custom, harga tangan pertama & peti kayu aman.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/products/wastafel-onyx-tembus-cahaya.webp') }}">

    <!-- Twitter Card SEO -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Pengrajin Marmer & Onyx Tulungagung | Wastafel Batu Alam')">
    <meta name="twitter:description" content="@yield('meta-description', 'Pusat pengrajin wastafel marmer, onyx tembus cahaya & batu kali Campurdarat Tulungagung. Melayani pesanan custom, harga tangan pertama & peti kayu aman.')">
    <meta name="twitter:image" content="{{ asset('images/products/wastafel-onyx-tembus-cahaya.webp') }}">

    <!-- Favicon & Touch Icons (Googlebot-Favicon Compliant) -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/webp" href="{{ asset('images/favicon.webp') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/icon-192.webp') }}">

    <!-- Schema.org JSON-LD Structured Data (WebSite & Local Business for Google Search Rich Snippets) -->
    <script type="application/ld+json">
    [
      {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "Onyx Tulungagung",
        "alternateName": ["E-SCM Marmer Tulungagung", "OnyxTulungagung.id"],
        "url": "{{ url('/') }}",
        "potentialAction": {
          "@type": "SearchAction",
          "target": "{{ url('/katalog') }}?q={search_term_string}",
          "query-input": "required name=search_term_string"
        }
      },
      {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "E-SCM Klaster IKM Kerajinan Marmer Tulungagung",
        "image": "{{ asset('images/products/wastafel-onyx-tembus-cahaya.webp') }}",
        "description": "Sistem Informasi Rantai Pasok Terintegrasi dan Etalase Digital Kerajinan Marmer, Onyx, dan Batu Kali Campurdarat Tulungagung.",
        "address": {
          "@type": "PostalAddress",
          "addressLocality": "Campurdarat",
          "addressRegion": "Jawa Timur",
          "postalCode": "66272",
          "addressCountry": "ID"
        },
        "geo": {
          "@type": "GeoCoordinates",
          "latitude": -8.1639,
          "longitude": 111.8542
        },
        "url": "{{ url('/') }}",
        "telephone": "+6281234567890",
        "priceRange": "Rp 150.000 - Rp 2.500.000"
      }
    ]
    </script>

    <!-- Preload & Link Compiled Minified CSS -->
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    @yield('styles')
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex flex-col min-h-screen">

    <!-- 1. TOP ANNOUNCEMENT BANNER -->
    <div class="bg-slate-950 text-slate-300 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-900 text-blue-200 border border-blue-700">
                    RESMI
                </span>
                <span class="truncate">Sistem Rantai Pasok Digital Terpadu Klaster Marmer & Onyx Tulungagung</span>
            </div>
            <div class="hidden md:flex items-center gap-4 text-[11px]">
                <a href="https://wa.me/6281234567890?text=Halo%20Pengrajin%20Marmer%20Tulungagung,%20saya%20ingin%20konsultasi%20pemesanan%20produk." target="_blank" class="hover:text-emerald-400 transition flex items-center gap-1">
                    <i data-lucide="message-circle" class="w-3.5 h-3.5 text-emerald-400"></i>
                    <span>Pusat Informasi & Bantuan</span>
                </a>
                <span class="text-slate-600">|</span>
                <span class="text-slate-300">Senin - Sabtu (08:00 - 17:00)</span>
            </div>
        </div>
    </div>

    <!-- 2. MAIN PUBLIC NAVBAR -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40 shadow-sm transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center p-1.5 shadow-xs group-hover:scale-105 group-hover:bg-blue-100 transition">
                        <img src="{{ asset('images/logo-icon.webp') }}" 
                             alt="Logo E-SCM Marmer Tulungagung" 
                             width="32" 
                             height="32" 
                             class="w-8 h-8 object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-extrabold text-lg text-slate-900 tracking-tight leading-none">E-SCM MARMER</span>
                            <span class="bg-blue-100 text-blue-800 font-bold text-[10px] px-1.5 py-0.5 rounded">IKM</span>
                        </div>
                        <p class="text-[11px] text-slate-500 font-medium">Klaster Marmer & Onyx Tulungagung</p>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-1.5">
                    <a href="{{ route('home') }}" 
                       class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-900 font-bold border border-blue-200/80 shadow-xs' : 'text-slate-600 hover:text-blue-900 hover:bg-slate-100' }}">
                        Beranda
                    </a>
                    <a href="{{ route('catalog') }}" 
                       class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('catalog*') || request()->routeIs('checkout*') ? 'bg-blue-50 text-blue-900 font-bold border border-blue-200/80 shadow-xs' : 'text-slate-600 hover:text-blue-900 hover:bg-slate-100' }}">
                        Katalog Produk
                    </a>
                    <a href="{{ route('order.tracking') }}" 
                       class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('order.tracking') ? 'bg-blue-50 text-blue-900 font-bold border border-blue-200/80 shadow-xs' : 'text-slate-600 hover:text-blue-900 hover:bg-slate-100' }}">
                        Lacak Pesanan
                    </a>
                    <a href="{{ route('home') }}#profil-ikm" 
                       class="px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:text-blue-900 hover:bg-slate-100 transition-all duration-200">
                        Profil IKM
                    </a>
                    <a href="{{ route('home') }}#alur-rantai-pasok" 
                       class="px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:text-blue-900 hover:bg-slate-100 transition-all duration-200">
                        Alur SCM
                    </a>
                    <a href="{{ route('home') }}#kontak" 
                       class="px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:text-blue-900 hover:bg-slate-100 transition-all duration-200">
                        Kontak
                    </a>
                </nav>

                <!-- Right Action Buttons -->
                <div class="flex items-center gap-3">
                    @auth
                    <a href="{{ route('dashboard') }}" class="bg-blue-900 hover:bg-blue-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow-sm">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 text-blue-300"></i>
                        <span>Masuk Dashboard</span>
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="bg-slate-900 hover:bg-blue-900 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow-sm">
                        <i data-lucide="log-in" class="w-4 h-4 text-blue-400"></i>
                        <span>Portal E-SCM</span>
                    </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button onclick="toggleMobileMenu()" class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg md:hidden" aria-label="Buka Menu Navigasi">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Dropdown Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-200 bg-white px-4 py-4 space-y-2 shadow-lg">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-900' : 'text-slate-700 hover:bg-slate-50' }}">
                Beranda
            </a>
            <a href="{{ route('catalog') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('catalog*') ? 'bg-blue-50 text-blue-900' : 'text-slate-700 hover:bg-slate-50' }}">
                Katalog Produk
            </a>
            <a href="{{ route('order.tracking') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('order.tracking') ? 'bg-blue-50 text-blue-900' : 'text-slate-700 hover:bg-slate-50' }}">
                Lacak Pesanan Live
            </a>
            <a href="{{ route('home') }}#profil-ikm" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Profil IKM Mitra
            </a>
            <a href="{{ route('home') }}#alur-rantai-pasok" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Alur Rantai Pasok
            </a>
            <a href="{{ route('home') }}#kontak" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Kontak & Lokasi
            </a>
            @auth
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-bold bg-blue-900 text-white text-center">
                Buka Dashboard SCM
            </a>
            @else
            <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-sm font-bold bg-slate-900 text-white text-center">
                Masuk Portal E-SCM (Staf/Owner)
            </a>
            @endauth
        </div>
    </header>

    <!-- 3. MAIN YIELD CONTENT -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- 4. FLOATING WHATSAPP BUTTON (MOB-10 SOLVED: Hidden on Checkout page to prevent overlap) -->
    @if(!request()->routeIs('checkout*'))
    <div class="fixed bottom-6 right-6 z-50">
        <a href="https://wa.me/6281234567890?text=Halo%20Pengrajin%20Marmer%20Tulungagung,%20saya%20ingin%20tanya%20produk%20dan%20pemesanan%20katalog." 
           target="_blank" 
           title="Konsultasi Pesanan via WhatsApp" 
           aria-label="Konsultasi Pesanan via WhatsApp"
           class="bg-emerald-600 hover:bg-emerald-500 text-white p-3.5 rounded-full shadow-xl shadow-emerald-600/30 flex items-center gap-2 hover:scale-105 transition group">
            <i data-lucide="message-circle" class="w-6 h-6"></i>
            <span class="max-w-0 overflow-hidden whitespace-nowrap group-hover:max-w-xs transition-all duration-300 font-bold text-xs">
                Tanya Pengrajin
            </span>
        </a>
    </div>
    @endif

    <!-- 5. FOOTER -->
    <footer id="kontak" class="bg-slate-950 text-slate-300 border-t border-slate-800 pt-14 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                
                <!-- Col 1: Brand & Purpose -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center p-1.5 shadow-sm">
                            <img src="{{ asset('images/logo-icon.webp') }}" 
                                 alt="Logo E-SCM Marmer Tulungagung" 
                                 width="28" 
                                 height="28" 
                                 class="w-7 h-7 object-contain">
                        </div>
                        <div>
                            <p class="text-base font-bold text-white leading-none">E-SCM MARMER</p>
                            <p class="text-xs text-blue-400">Klaster IKM Tulungagung</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Platform e-Supply Chain Management terintegrasi untuk akselerasi hilirisasi industri kerajinan marmer, onyx, dan batu kali di Kabupaten Tulungagung.
                    </p>
                    <div class="flex items-center gap-2 text-xs text-emerald-400">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                        <span>QC 2-Tahap & Garansi Pengiriman</span>
                    </div>
                </div>

                <!-- Col 2: IKM Mitra -->
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">IKM Mitra Binaan</h3>
                    <ul class="space-y-3 text-xs">
                        <li class="p-2.5 bg-slate-900/80 rounded-lg border border-slate-800">
                            <p class="font-bold text-white">UD Cahaya Onix</p>
                            <p class="text-[11px] text-slate-300">Spesialis Wastafel Marmer & Onyx Tembus Cahaya</p>
                            <p class="text-[10px] text-blue-300 mt-1">Pimpinan: M. Ilham Nur Amali (Campurdarat)</p>
                        </li>
                        <li class="p-2.5 bg-slate-900/80 rounded-lg border border-slate-800">
                            <p class="font-bold text-white">UD Putra Abadi</p>
                            <p class="text-[11px] text-slate-300">Spesialis Batu Kali, Stepping Stone & Cladding</p>
                            <p class="text-[10px] text-emerald-300 mt-1">Pimpinan: Efri Saputra (Campurdarat)</p>
                        </li>
                    </ul>
                </div>

                <!-- Col 3: Quick Links -->
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Navigasi Cepat</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Beranda Utama</a></li>
                        <li><a href="{{ route('catalog') }}" class="hover:text-white transition">Katalog Wastafel & Kerajinan</a></li>
                        <li><a href="{{ route('catalog', ['material' => 'onix']) }}" class="hover:text-white transition">Koleksi Onyx Tembus Cahaya</a></li>
                        <li><a href="{{ route('catalog', ['material' => 'batu_kali']) }}" class="hover:text-white transition">Koleksi Batu Kali & Taman</a></li>
                        <li><a href="{{ route('login') }}" class="text-blue-400 hover:underline">Login Operator / Portal SCM</a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact & Workshop -->
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Kontak & Workshop</h3>
                    <div class="space-y-2.5 text-xs text-slate-300">
                        <div class="flex items-start gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0"></i>
                            <span>Kawasan Industri Kerajinan Marmer Campurdarat, Kabupaten Tulungagung, Jawa Timur 66272</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="phone" class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
                            <span>+62 812-3456-7890 / +62 812-9876-5432</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="mail" class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
                            <span>kontak@scm-marmer-tulungagung.id</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="pt-8 border-t border-slate-900 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-300">
                <p>&copy; {{ date('Y') }} E-SCM Marmer Tulungagung. Didukung oleh Klaster IKM Kerajinan Marmer Tulungagung.</p>
                <div class="flex items-center gap-4 text-[11px]">
                    <span>Standar Mutu ISO/IEC 25010</span>
                    <span>&bull;</span>
                    <span>100% Batuan Alami Tulungagung</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Deferred Lucide Icons Script -->
    <script src="https://unpkg.com/lucide@latest" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            } else {
                window.addEventListener('load', () => {
                    if (window.lucide) lucide.createIcons();
                });
            }
        });

        // Responsive Menu Toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
    @yield('scripts')
</body>
</html>
