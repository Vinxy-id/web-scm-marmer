<!-- BACKDROP OVERLAY FOR MOBILE -->
<div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 lg:hidden hidden transition-opacity duration-300"></div>

<!-- SIDEBAR -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-200 flex flex-col transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 lg:static lg:inset-auto flex-shrink-0 shadow-2xl lg:shadow-none">
    <!-- Brand Header -->
    <div class="h-16 flex items-center justify-between px-4 bg-slate-950 border-b border-slate-800">
        <div class="flex items-center gap-3 overflow-hidden">
            <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 flex-shrink-0">
                <i data-lucide="layers" class="w-5 h-5"></i>
            </div>
            <div class="overflow-hidden">
                <h1 class="font-bold text-sm text-white tracking-wide leading-tight">E-SCM MARMER</h1>
                <p class="text-[11px] text-blue-400 font-medium truncate">Klaster IKM Tulungagung</p>
            </div>
        </div>
        <!-- Close Button (Mobile Only) -->
        <button onclick="toggleSidebar()" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 lg:hidden" title="Tutup Menu">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto py-3 px-2 space-y-1 custom-scrollbar">
        
        <!-- Showcase Front-End Link -->
        <a href="{{ route('home') }}" target="_blank" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-bold transition bg-slate-800 text-blue-300 hover:bg-slate-700 hover:text-white border border-slate-700/60 mb-2">
            <div class="flex items-center gap-2">
                <i data-lucide="external-link" class="w-3.5 h-3.5 text-blue-400"></i>
                <span>Lihat Katalog Publik</span>
            </div>
            <span class="text-[9px] bg-blue-500/20 text-blue-300 px-1.5 py-0.5 rounded font-mono">Front</span>
        </a>

        <div class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">Menu Utama</div>
        
        <a href="{{ route('dashboard') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
        </a>

        <a href="{{ route('orders.index') }}" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('orders.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <div class="flex items-center gap-3">
                <i data-lucide="shopping-bag" class="w-4 h-4 text-amber-400"></i> Pesanan Masuk
            </div>
            @php
                $pendingCount = \App\Models\Order::where('order_status', 'pending_payment')->count();
            @endphp
            @if($pendingCount > 0)
            <span class="inline-flex items-center bg-amber-500 text-slate-950 text-[10px] font-extrabold px-1.5 py-0.5 rounded-full animate-pulse">
                {{ $pendingCount }}
            </span>
            @endif
        </a>

        <a href="{{ route('supply-chain-flow') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('supply-chain-flow') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <i data-lucide="git-merge" class="w-4 h-4 text-emerald-400"></i> Alur Rantai Pasok
        </a>

        <div class="px-3 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">Operasional Hulu</div>

        <a href="{{ route('materials.index') }}" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('materials.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <div class="flex items-center gap-3">
                <i data-lucide="boxes" class="w-4 h-4 text-amber-400"></i> Bahan Baku
            </div>
            <span class="inline-flex items-center gap-1 bg-blue-500/20 text-blue-300 text-[10px] px-1.5 py-0.5 rounded font-semibold">
                Stok
            </span>
        </a>

        <div class="px-3 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">Lantai Produksi</div>

        <a href="{{ route('production.kanban') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('production.kanban') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <i data-lucide="kanban-square" class="w-4 h-4 text-indigo-400"></i> Produksi & SPK
        </a>

        <a href="{{ route('production.wip') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('production.wip') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <i data-lucide="activity" class="w-4 h-4 text-cyan-400"></i> WIP Stasiun Mesin
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
            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
                {{ substr(auth()->user()->name ?? 'UD', 0, 2) }}
            </div>
            <div class="overflow-hidden text-left">
                <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'M. Ilham Nur Amali' }}</p>
                <p class="text-[10px] text-slate-400 truncate">{{ ucfirst(auth()->user()->role ?? 'owner') }} ({{ auth()->user()->ikm_name ?? 'UD Cahaya Onix' }})</p>
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
