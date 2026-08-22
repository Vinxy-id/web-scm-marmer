<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-SCM Marmer Tulungagung') - Klaster IKM Terintegrasi</title>
    <!-- Favicon SVG -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232563eb' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z'/><polyline points='3.27 6.96 12 12.01 20.73 6.96'/><line x1='12' y1='22.08' x2='12' y2='12'/></svg>" />
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .kanban-col { min-height: 520px; }
    </style>
    @yield('styles')
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased custom-scrollbar">

    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR -->
        <aside id="sidebar" class="w-64 bg-slate-900 text-slate-200 flex flex-col transition-all duration-300 z-30 flex-shrink-0">
            <!-- Brand Header -->
            <div class="h-16 flex items-center px-4 bg-slate-950 border-b border-slate-800 gap-3">
                <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                    <i data-lucide="layers" class="w-5 h-5"></i>
                </div>
                <div class="overflow-hidden">
                    <h1 class="font-bold text-sm text-white tracking-wide leading-tight">E-SCM MARMER</h1>
                    <p class="text-[11px] text-blue-400 font-medium truncate">Klaster IKM Tulungagung</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto py-3 px-2 space-y-1 custom-scrollbar">
                <div class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">Menu Utama</div>
                
                <a href="{{ route('dashboard') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                </a>

                <a href="{{ route('supply-chain-flow') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('supply-chain-flow') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="git-merge" class="w-4 h-4 text-emerald-400"></i> Alur Rantai Pasok
                </a>

                <div class="px-3 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">Operasional Hulu</div>

                <a href="{{ route('materials.index') }}" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('materials.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="boxes" class="w-4 h-4 text-amber-400"></i> Bahan Baku
                    </div>
                    <span class="inline-flex items-center gap-1 bg-red-500/20 text-red-400 text-[10px] px-1.5 py-0.5 rounded font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> 1 Kritis
                    </span>
                </a>

                <div class="px-3 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">Lantai Produksi</div>

                <a href="{{ route('production.kanban') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('production.kanban') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="kanban-square" class="w-4 h-4 text-indigo-400"></i> Produksi & SPK
                </a>

                <a href="{{ route('production.wip') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('production.wip') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="activity" class="w-4 h-4 text-cyan-400"></i> WIP 7 Mesin Bubut
                </a>

                <a href="{{ route('qc.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('qc.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="shield-check" class="w-4 h-4 text-green-400"></i> QC Dua Tahap
                </a>

                <a href="{{ route('waste.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('waste.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="recycle" class="w-4 h-4 text-teal-400"></i> Hilirisasi Residu
                </a>

                <div class="px-3 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">Hilir & Distribusi</div>

                <a href="{{ route('distribution.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('distribution.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="truck" class="w-4 h-4 text-purple-400"></i> Distribusi & Packing
                </a>

                <div class="px-3 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">Analitik Cerdas</div>

                <a href="{{ route('forecasting.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('forecasting.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="trending-up" class="w-4 h-4 text-yellow-400"></i> AI & Forecasting
                </a>

                <a href="{{ route('reports') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('reports') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 text-blue-400"></i> Laporan & KPI
                </a>
            </div>

            <!-- Footer User Profile -->
            <div class="p-3 border-t border-slate-800 bg-slate-950 flex items-center justify-between">
                <div class="flex items-center gap-2.5 overflow-hidden">
                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs">
                        {{ substr(auth()->user()->name ?? 'Pak Joko', 0, 2) }}
                    </div>
                    <div class="overflow-hidden text-left">
                        <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'Pak Joko Santoso' }}</p>
                        <p class="text-[10px] text-slate-400">{{ ucfirst(auth()->user()->role ?? 'owner') }} (UD Cahaya Onix)</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" title="Keluar Sistem" class="p-1.5 hover:bg-slate-800 rounded text-slate-400 hover:text-red-400 transition">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- TOPBAR -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 z-20 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg lg:hidden">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 leading-tight">@yield('page-title', 'Dashboard Monitoring Rantai Pasok')</h2>
                        <p class="text-xs text-slate-500">@yield('page-subtitle', 'Klaster IKM Marmer Kabupaten Tulungagung')</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Quick Stats Badges -->
                    <div class="hidden md:flex items-center gap-2 text-xs bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-slate-600 font-medium">FastAPI Service: <b class="text-emerald-600">Port 8001</b></span>
                    </div>

                    <!-- Role Badge -->
                    <span class="bg-blue-100 text-blue-800 border border-blue-200 text-xs px-2.5 py-1 rounded-md font-semibold flex items-center gap-1.5">
                        <i data-lucide="user-check" class="w-3.5 h-3.5"></i> Role: {{ ucfirst(auth()->user()->role ?? 'owner') }}
                    </span>

                    @yield('topbar-actions')
                </div>
            </header>

            <!-- MAIN BODY SCROLLABLE -->
            <main class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                
                @if(session('success'))
                <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl flex items-center gap-3 shadow-sm">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>
                    <p class="text-xs font-semibold">{{ session('success') }}</p>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-5 bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl flex items-center gap-3 shadow-sm">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                    <p class="text-xs font-semibold">{{ session('error') }}</p>
                </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        lucide.createIcons();

        function toggleSidebar() {
            const sb = document.getElementById('sidebar');
            sb.classList.toggle('-translate-x-full');
        }
    </script>
    @yield('scripts')
</body>
</html>
