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

        /* Hide spinner arrows / steppers on number inputs */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased custom-scrollbar">

    <div class="flex h-screen overflow-hidden relative">
        
        <!-- MODULAR SIDEBAR -->
        @include('layouts.sidebar')

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- MODULAR TOPBAR HEADER -->
            @include('layouts.header')

            <!-- MAIN SCROLLABLE BODY -->
            <main class="flex-1 overflow-y-auto p-3.5 sm:p-6 bg-slate-50 custom-scrollbar">
                
                <!-- Flash Alerts -->
                @if(session('success'))
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between text-xs font-semibold shadow-sm">
                    <div class="flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center justify-between text-xs font-semibold shadow-sm">
                    <div class="flex items-center gap-2">
                        <i data-lucide="alert-triangle" class="w-4 h-4 text-red-600 flex-shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-xs shadow-sm">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-red-600 flex-shrink-0"></i>
                        <span>Terjadi Kesalahan Validasi:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px] text-red-700">
                        @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Yield Content -->
                @yield('content')
            </main>
        </div>

    </div>

    <!-- Scripts -->
    <script>
        // Init Lucide Vector Icons
        lucide.createIcons();

        // Responsive Sidebar Toggle with Backdrop
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
            }
            if (backdrop) {
                backdrop.classList.toggle('hidden');
            }
        }

        // Global Real-time Input Validation & Warning System
        function validateIntegerInput(input) {
            let originalVal = input.value;
            if (originalVal.includes('.') || originalVal.includes(',') || originalVal.includes('-') || isNaN(originalVal)) {
                input.value = originalVal.replace(/[^0-9]/g, '');
                showInputWarning(input, '⚠️ Angka harus bulat (tanpa koma/titik)!');
            } else {
                hideInputWarning(input);
            }
        }

        function validateCodeInput(input) {
            let originalVal = input.value;
            if (/[^a-zA-Z0-9\-\_]/.test(originalVal)) {
                input.value = originalVal.replace(/[^a-zA-Z0-9\-\_]/g, '');
                showInputWarning(input, '⚠️ Karakter tidak diperbolehkan (hanya huruf, angka, - dan _)!');
            } else {
                hideInputWarning(input);
            }
        }

        function showInputWarning(input, msg) {
            let warningId = 'warning-' + (input.id || input.name || Math.random().toString(36).substring(7));
            let warningEl = document.getElementById(warningId);
            if (!warningEl) {
                warningEl = document.createElement('p');
                warningEl.id = warningId;
                warningEl.className = 'text-[10px] text-red-600 font-bold mt-1 flex items-center gap-1 animate-pulse';
                input.parentNode.appendChild(warningEl);
            }
            warningEl.textContent = msg;
            input.classList.add('border-red-500', 'ring-1', 'ring-red-500');
            setTimeout(() => {
                if (warningEl) warningEl.remove();
                input.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
            }, 3000);
        }

        function hideInputWarning(input) {
            let warningId = 'warning-' + (input.id || input.name);
            let warningEl = document.getElementById(warningId);
            if (warningEl) warningEl.remove();
            input.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
        }
    </script>
    @yield('scripts')
</body>
</html>
