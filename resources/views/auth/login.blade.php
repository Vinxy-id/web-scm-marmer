<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-SCM Marmer Tulungagung</title>
    <!-- Favicon & Touch Icon -->
    <link rel="icon" type="image/webp" href="{{ asset('images/favicon.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icon-192.webp') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-slate-950 border border-slate-800 rounded-2xl shadow-2xl p-8 space-y-6">
        <!-- Logo & Header -->
        <div class="text-center space-y-3">
            <div class="inline-flex items-center justify-center p-3 rounded-2xl bg-white/5 border border-white/10 shadow-xl mx-auto">
                <img src="{{ asset('images/logo-icon.webp') }}" 
                     alt="Logo E-SCM Marmer Tulungagung" 
                     width="48" 
                     height="48" 
                     class="w-12 h-12 object-contain">
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
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@instansi.com" required autofocus class="w-full bg-slate-900 border border-slate-700 text-white rounded-lg pl-9 pr-3 py-2 text-xs focus:outline-none focus:border-blue-500 placeholder:text-slate-600">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Kata Sandi</label>
                <div class="relative">
                    <i data-lucide="lock" class="w-4 h-4 text-slate-500 absolute left-3 top-3"></i>
                    <input type="password" name="password" placeholder="••••••••" required class="w-full bg-slate-900 border border-slate-700 text-white rounded-lg pl-9 pr-3 py-2 text-xs focus:outline-none focus:border-blue-500 placeholder:text-slate-600">
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
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
