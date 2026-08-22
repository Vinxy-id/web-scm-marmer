<!-- TOPBAR HEADER -->
<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-3 sm:px-6 z-20 flex-shrink-0">
    <div class="flex items-center gap-2 sm:gap-3 min-w-0">
        <button onclick="toggleSidebar()" class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg lg:hidden flex-shrink-0" title="Menu Navigasi">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
        <div class="min-w-0">
            <h2 class="text-sm sm:text-base md:text-lg font-bold text-slate-800 leading-tight truncate">@yield('page-title', 'Dashboard Monitoring Rantai Pasok')</h2>
            <p class="hidden sm:block text-xs text-slate-500 truncate">@yield('page-subtitle', 'Klaster IKM Marmer Kabupaten Tulungagung')</p>
        </div>
    </div>

    <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
        <!-- Quick Stats Badges (Hidden on mobile) -->
        <div class="hidden md:flex items-center gap-2 text-xs bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-slate-600 font-medium">FastAPI: <b class="text-emerald-600">Port 8001</b></span>
        </div>

        <!-- Role Badge -->
        <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full text-[11px] sm:text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
            {{ ucfirst(auth()->user()->role ?? 'Owner') }}
        </span>

        @yield('topbar-actions')
    </div>
</header>
