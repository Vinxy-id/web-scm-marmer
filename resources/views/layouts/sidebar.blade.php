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
