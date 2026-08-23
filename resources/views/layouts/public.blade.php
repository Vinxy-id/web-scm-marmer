<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Katalog Kerajinan Marmer & Onyx Tulungagung') - E-SCM IKM</title>
    <meta name="description" content="Etalase produk kerajinan marmer, onyx tembus cahaya, wastafel batu kali, dan stepping stone dari sentra IKM Campurdarat Tulungagung (UD Cahaya Onix & UD Putra Abadi).">

    <!-- Favicon SVG -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231e3a8a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z'/><polyline points='3.27 6.96 12 12.01 20.73 6.96'/><line x1='12' y1='22.08' x2='12' y2='12'/></svg>" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        marble: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            500: '#64748b',
                            800: '#1e293b',
                            900: '#0f172a',
                        },
                        brand: {
                            navy: '#1e3a8a',
                            accent: '#3b82f6',
                            gold: '#d97706',
                            emerald: '#10b981',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
    @yield('styles')
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased custom-scrollbar flex flex-col min-h-screen">

    <!-- 1. TOP ANNOUNCEMENT BAR (MOB-07 SOLVED: Single-line clean mobile bar) -->
    <div class="bg-slate-900 text-slate-300 text-xs py-1.5 sm:py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 text-[11px] sm:text-xs truncate">
                <span class="inline-flex items-center gap-1 bg-emerald-500/20 text-emerald-400 font-semibold px-1.5 py-0.5 rounded-full text-[10px] flex-shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> E-SCM
                </span>
                <span class="truncate">Sentra Pengrajin Marmer & Onyx Campurdarat, Tulungagung</span>
            </div>
            <div class="hidden sm:flex items-center gap-4 text-[11px] flex-shrink-0">
                <a href="https://wa.me/6281234567890?text=Halo%20Pengrajin%20Marmer%20Tulungagung,%20saya%20ingin%20konsultasi%20pemesanan%20produk." target="_blank" class="hover:text-emerald-400 transition flex items-center gap-1">
                    <i data-lucide="phone-call" class="w-3 h-3 text-emerald-400"></i> Hotline: 0812-3456-7890
                </a>
                <span class="text-slate-600">|</span>
                <span class="text-slate-400">Senin - Sabtu (08:00 - 17:00)</span>
            </div>
        </div>
    </div>

    <!-- 2. MAIN PUBLIC NAVBAR -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40 shadow-sm transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-blue-900 flex items-center justify-center text-white shadow-md shadow-blue-900/20 group-hover:scale-105 transition">
                        <i data-lucide="layers" class="w-5 h-5 text-blue-300"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-extrabold text-lg text-slate-900 tracking-tight leading-none">E-SCM MARMER</span>
                            <span class="bg-blue-100 text-blue-800 font-bold text-[10px] px-1.5 py-0.5 rounded">IKM</span>
                        </div>
                        <p class="text-[11px] text-slate-500 font-medium">Klaster Marmer & Onyx Tulungagung</p>
                    </div>
                </a>

                <!-- Desktop Navigation Links (MOB-11 SOLVED: Added Kontak link) -->
                <nav class="hidden md:flex items-center gap-7">
                    <a href="{{ route('home') }}" class="text-sm font-semibold transition {{ request()->routeIs('home') ? 'text-blue-900 font-bold border-b-2 border-blue-900 pb-1' : 'text-slate-600 hover:text-blue-900' }}">
                        Beranda
                    </a>
                    <a href="{{ route('catalog') }}" class="text-sm font-semibold transition {{ request()->routeIs('catalog*') || request()->routeIs('checkout*') ? 'text-blue-900 font-bold border-b-2 border-blue-900 pb-1' : 'text-slate-600 hover:text-blue-900' }}">
                        Katalog Produk
                    </a>
                    <a href="{{ route('order.tracking') }}" class="text-sm font-semibold transition {{ request()->routeIs('order.tracking') ? 'text-blue-900 font-bold border-b-2 border-blue-900 pb-1' : 'text-slate-600 hover:text-blue-900' }}">
                        Lacak Pesanan
                    </a>
                    <a href="{{ route('home') }}#profil-ikm" class="text-sm font-semibold text-slate-600 hover:text-blue-900 transition">
                        Profil IKM
                    </a>
                    <a href="{{ route('home') }}#alur-rantai-pasok" class="text-sm font-semibold text-slate-600 hover:text-blue-900 transition">
                        Alur SCM
                    </a>
                    <a href="{{ route('home') }}#kontak" class="text-sm font-semibold text-slate-600 hover:text-blue-900 transition">
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
                        <i data-lucide="lock" class="w-4 h-4 text-blue-400"></i>
                        <span>Portal E-SCM</span>
                    </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button onclick="toggleMobileMenu()" class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg md:hidden">
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
           class="bg-emerald-600 hover:bg-emerald-500 text-white p-3.5 rounded-full shadow-xl shadow-emerald-600/30 flex items-center gap-2 hover:scale-105 transition group">
            <i data-lucide="message-circle" class="w-6 h-6"></i>
            <span class="max-w-0 overflow-hidden whitespace-nowrap group-hover:max-w-xs transition-all duration-300 font-bold text-xs">
                Tanya Pengrajin
            </span>
        </a>
    </div>
    @endif

    <!-- 5. FOOTER -->
    <footer id="kontak" class="bg-slate-950 text-slate-400 border-t border-slate-800 pt-14 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                
                <!-- Col 1: Brand & Purpose -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-700 flex items-center justify-center text-white font-bold">
                            <i data-lucide="layers" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white leading-none">E-SCM MARMER</h3>
                            <p class="text-xs text-blue-400">Klaster IKM Tulungagung</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Platform e-Supply Chain Management terintegrasi untuk akselerasi hilirisasi industri kerajinan marmer, onyx, dan batu kali di Kabupaten Tulungagung.
                    </p>
                    <div class="flex items-center gap-2 text-xs text-emerald-400">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                        <span>QC 2-Tahap & Garansi Pengiriman</span>
                    </div>
                </div>

                <!-- Col 2: IKM Mitra -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">IKM Mitra Binaan</h4>
                    <ul class="space-y-3 text-xs">
                        <li class="p-2.5 bg-slate-900/80 rounded-lg border border-slate-800">
                            <p class="font-bold text-white">UD Cahaya Onix</p>
                            <p class="text-[11px] text-slate-400">Spesialis Wastafel Marmer & Onyx Tembus Cahaya</p>
                            <p class="text-[10px] text-blue-400 mt-1">Pimpinan: M. Ilham Nur Amali (Campurdarat)</p>
                        </li>
                        <li class="p-2.5 bg-slate-900/80 rounded-lg border border-slate-800">
                            <p class="font-bold text-white">UD Putra Abadi</p>
                            <p class="text-[11px] text-slate-400">Spesialis Batu Kali, Stepping Stone & Cladding</p>
                            <p class="text-[10px] text-emerald-400 mt-1">Pimpinan: Efri Saputra (Campurdarat)</p>
                        </li>
                    </ul>
                </div>

                <!-- Col 3: Quick Links -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Navigasi Cepat</h4>
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
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Kontak & Workshop</h4>
                    <div class="space-y-2.5 text-xs text-slate-400">
                        <div class="flex items-start gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-slate-500 mt-0.5 flex-shrink-0"></i>
                            <span>Kawasan Industri Kerajinan Marmer Campurdarat, Kabupaten Tulungagung, Jawa Timur 66272</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="phone" class="w-4 h-4 text-slate-500 flex-shrink-0"></i>
                            <span>+62 812-3456-7890 / +62 812-9876-5432</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="mail" class="w-4 h-4 text-slate-500 flex-shrink-0"></i>
                            <span>kontak@scm-marmer-tulungagung.id</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="pt-8 border-t border-slate-900 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} E-SCM Marmer Tulungagung. Didukung oleh Klaster IKM Kerajinan Marmer Tulungagung.</p>
                <div class="flex items-center gap-4 text-[11px]">
                    <span>Standar Mutu ISO/IEC 25010</span>
                    <span>&bull;</span>
                    <span>100% Batuan Alami Tulungagung</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Init Lucide Icons
        lucide.createIcons();

        // Responsive Menu Toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
    @yield('scripts')
</body>
</html>
