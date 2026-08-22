<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-SCM Marmer Tulungagung</title>
    <!-- Favicon SVG -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232563eb' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z'/><polyline points='3.27 6.96 12 12.01 20.73 6.96'/><line x1='12' y1='22.08' x2='12' y2='12'/></svg>" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-slate-950 border border-slate-800 rounded-2xl shadow-2xl p-8 space-y-6">
        <!-- Logo & Header -->
        <div class="text-center space-y-2">
            <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white mx-auto shadow-lg shadow-blue-500/30">
                <i data-lucide="layers" class="w-6 h-6"></i>
            </div>
            <h1 class="text-xl font-bold text-white tracking-wide">E-SCM MARMER TULUNGAGUNG</h1>
            <p class="text-xs text-slate-400">Sistem Rantai Pasok Terintegrasi Klaster IKM</p>
        </div>

        @if ($errors->any())
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-3 rounded-lg text-xs space-y-1">
            @foreach ($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
        @endif

        @if (session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-3 rounded-lg text-xs">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Email Pengguna</label>
                <div class="relative">
                    <i data-lucide="mail" class="w-4 h-4 text-slate-500 absolute left-3 top-3"></i>
                    <input type="email" name="email" value="{{ old('email', 'owner@cahayaonix.com') }}" required autofocus class="w-full bg-slate-900 border border-slate-700 text-white rounded-lg pl-9 pr-3 py-2 text-xs focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Kata Sandi</label>
                <div class="relative">
                    <i data-lucide="lock" class="w-4 h-4 text-slate-500 absolute left-3 top-3"></i>
                    <input type="password" name="password" value="owner123" required class="w-full bg-slate-900 border border-slate-700 text-white rounded-lg pl-9 pr-3 py-2 text-xs focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-400">
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded bg-slate-800 border-slate-700 text-blue-600 focus:ring-0">
                    <span>Ingat Sesi Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2.5 rounded-lg text-xs transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
                <i data-lucide="log-in" class="w-4 h-4"></i> Masuk ke Dashboard
            </button>
        </form>

        <!-- Quick Demo Role Switcher Hint -->
        <div class="pt-4 border-t border-slate-800/80 text-[11px] text-slate-500 text-center space-y-1">
            <p class="font-semibold text-slate-400">Akun Pengujian Demo (Password: <i>role</i>123):</p>
            <p><code>owner@cahayaonix.com</code> (Owner) | <code>gudang@cahayaonix.com</code></p>
            <p><code>produksi@cahayaonix.com</code> | <code>distribusi@cahayaonix.com</code></p>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
