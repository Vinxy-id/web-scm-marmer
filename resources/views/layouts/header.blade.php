<!-- TOPBAR HEADER -->
<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-3 sm:px-6 z-20 flex-shrink-0">
    <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1 mr-2">
        <!-- Hamburger Button Mobile (Always visible on mobile) -->
        <button type="button" 
                onclick="toggleSidebar()" 
                class="p-2 -ml-1 text-slate-700 hover:text-blue-700 hover:bg-blue-50 rounded-xl lg:hidden flex-shrink-0 border border-slate-200 transition shadow-sm" 
                title="Buka Menu Navigasi" 
                aria-label="Buka Menu Navigasi">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
        <div class="min-w-0 flex-1">
            <h1 class="text-xs sm:text-base md:text-lg font-bold text-slate-800 leading-tight truncate">@yield('page-title', 'Dashboard Monitoring Rantai Pasok')</h1>
            <p class="hidden sm:block text-xs text-slate-600 truncate">@yield('page-subtitle', 'Klaster IKM Marmer Kabupaten Tulungagung')</p>
        </div>
    </div>

    <div class="flex items-center gap-1.5 sm:gap-3 flex-shrink-0">
        <!-- Quick Stats Badges (Hidden on mobile) -->
        <div class="hidden md:flex items-center gap-2 text-xs bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-slate-600 font-medium">FastAPI: <b class="text-emerald-600">Port 8001</b></span>
        </div>

        <!-- Role Badge -->
        <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full text-[10px] sm:text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 flex-shrink-0">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
            {{ ucfirst(auth()->user()->role ?? 'Owner') }}
        </span>

        @yield('topbar-actions')
    </div>
</header>
