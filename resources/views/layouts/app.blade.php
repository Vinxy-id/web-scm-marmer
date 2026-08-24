<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-SCM Marmer Tulungagung') - Klaster IKM Terintegrasi</title>
    <meta name="description" content="@yield('meta-description', 'Dashboard monitoring rantai pasok terintegrasi klaster IKM marmer dan onix Tulungagung - E-SCM.')">
    <meta name="robots" content="@yield('meta-robots', 'index, follow')">
    
    <!-- Preload & Link Compiled Minified CSS -->
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Favicon & Touch Icon -->
    <link rel="icon" type="image/webp" href="{{ asset('images/favicon.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icon-192.webp') }}">
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

    <!-- Deferred Lucide & Chart.js CDNs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <script src="https://unpkg.com/lucide@latest" defer></script>

    <!-- Scripts -->
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
