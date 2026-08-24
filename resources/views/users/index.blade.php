@extends('layouts.app')

@section('title', 'Manajemen Pengguna & Hak Akses (RBAC)')
@section('page-title', 'Manajemen Pengguna & Hak Akses')
@section('page-subtitle', 'Tata Kelola Akun, Penugasan Peran Operasional (RBAC), dan Kontrol Akses Klaster IKM')

@section('topbar-actions')
    <button type="button" 
            onclick="openAddUserModal()" 
            class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg flex items-center gap-1 shadow-sm transition" 
            title="Tambah Akun Baru">
        <i data-lucide="user-plus" class="w-4 h-4"></i>
        <span class="hidden sm:inline">Tambah Akun Baru</span>
    </button>
@endsection

@section('content')
<div class="space-y-6">

    <!-- 1. HEADER BANNER & ACTION BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="p-2 bg-blue-50 text-blue-700 border border-blue-100 rounded-xl">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </span>
                <h1 class="text-lg sm:text-xl font-bold text-slate-900">Manajemen Pengguna & Hak Akses (RBAC)</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Kelola kredensial akun, alokasi peran operasional (Owner, Gudang, Produksi, Distribusi, Admin), serta kendali akses klaster IKM.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="openAddUserModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition shadow-md shadow-blue-600/20 flex items-center gap-1.5">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                <span>Tambah Pengguna</span>
            </button>
        </div>
    </div>

    <!-- 2. KPI SUMMARY METRICS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] text-slate-400 font-semibold block uppercase tracking-wider">Total Akun</span>
            <p class="text-2xl font-black text-slate-900 mt-1">{{ $totalUsers }} <span class="text-xs font-normal text-slate-500">User</span></p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-emerald-100 shadow-sm">
            <span class="text-[11px] text-emerald-600 font-semibold block uppercase tracking-wider">Akun Aktif</span>
            <p class="text-2xl font-black text-emerald-600 mt-1">{{ $activeUsers }} <span class="text-xs font-normal text-emerald-500">User</span></p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-blue-100 shadow-sm">
            <span class="text-[11px] text-blue-600 font-semibold block uppercase tracking-wider">Owner & Admin</span>
            <p class="text-2xl font-black text-blue-600 mt-1">{{ $adminOwnerCount }} <span class="text-xs font-normal text-blue-500">User</span></p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-purple-100 shadow-sm">
            <span class="text-[11px] text-purple-600 font-semibold block uppercase tracking-wider">Staf Lapangan</span>
            <p class="text-2xl font-black text-purple-600 mt-1">{{ $staffCount }} <span class="text-xs font-normal text-purple-500">User</span></p>
        </div>
    </div>

    <!-- 3. FILTERS & SEARCH BAR -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap items-center gap-2.5">
            <div class="relative flex-1 min-w-[200px]">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-2.5"></i>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari nama, email, atau no. telepon..." 
                       class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg pl-9 pr-3 py-2 text-slate-700 focus:outline-none focus:border-blue-500 focus:bg-white transition">
            </div>

            <select name="role" class="text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:border-blue-500">
                <option value="">Semua Role</option>
                <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                <option value="gudang" {{ request('role') == 'gudang' ? 'selected' : '' }}>Gudang</option>
                <option value="produksi" {{ request('role') == 'produksi' ? 'selected' : '' }}>Produksi</option>
                <option value="distribusi" {{ request('role') == 'distribusi' ? 'selected' : '' }}>Distribusi</option>
            </select>

            <select name="ikm_name" class="text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:border-blue-500">
                <option value="">Semua IKM</option>
                @foreach($ikmOptions as $ikm)
                    <option value="{{ $ikm }}" {{ request('ikm_name') == $ikm ? 'selected' : '' }}>{{ $ikm }}</option>
                @endforeach
            </select>

            <select name="status" class="text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:border-blue-500">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold px-4 py-2 rounded-lg transition flex items-center gap-1.5 shadow-sm">
                <i data-lucide="filter" class="w-3.5 h-3.5"></i> Filter
            </button>

            @if(request()->anyFilled(['search', 'role', 'ikm_name', 'status']))
            <a href="{{ route('users.index') }}" class="text-xs text-slate-500 hover:text-slate-700 font-semibold px-2 py-2 flex items-center gap-1">
                <i data-lucide="rotate-ccw" class="w-3 h-3"></i> Reset
            </a>
            @endif
        </form>
    </div>

    <!-- 4. USERS TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold border-b border-slate-200 text-[11px] tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 whitespace-nowrap">Pengguna</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Hak Akses (Role)</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Unit IKM</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Kontak / HP</th>
                        <th class="py-3.5 px-4 whitespace-nowrap text-center">Status</th>
                        <th class="py-3.5 px-4 whitespace-nowrap text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/80 transition {{ !$user->is_active ? 'bg-slate-50/50 opacity-60' : '' }}">
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs uppercase
                                    {{ $user->role === 'owner' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $user->role === 'gudang' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                    {{ $user->role === 'produksi' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                    {{ $user->role === 'distribusi' ? 'bg-purple-100 text-purple-800' : '' }}
                                ">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 text-sm flex items-center gap-1.5 whitespace-nowrap">
                                        {{ $user->name }}
                                        @if($user->id === auth()->id())
                                            <span class="text-[10px] bg-blue-100 text-blue-800 font-bold px-1.5 py-0.2 rounded inline-block whitespace-nowrap">Anda</span>
                                        @endif
                                    </p>
                                    <p class="text-[11px] text-slate-500 font-mono whitespace-nowrap">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            @php
                                $roleBadges = [
                                    'owner' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'admin' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'gudang' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'produksi' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'distribusi' => 'bg-purple-50 text-purple-700 border-purple-200',
                                ];
                                $badgeClass = $roleBadges[$user->role] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            @endphp
                            <span class="inline-flex whitespace-nowrap items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold border {{ $badgeClass }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <span class="text-xs font-semibold text-slate-800">{{ $user->ikm_name }}</span>
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            @if($user->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" class="inline-flex whitespace-nowrap items-center gap-1 text-slate-700 hover:text-emerald-600 font-mono text-xs transition">
                                    <i data-lucide="phone" class="w-3 h-3 text-slate-400"></i>
                                    <span>{{ $user->phone }}</span>
                                </a>
                            @else
                                <span class="text-slate-400 text-xs italic">-</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            @if($user->is_active)
                                <span class="inline-flex whitespace-nowrap items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex whitespace-nowrap items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <!-- Edit Button -->
                                <button type="button" 
                                        onclick="openEditUserModal({{ json_encode($user) }})"
                                        class="p-1.5 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" 
                                        title="Edit Akun & Reset Password">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>

                                @if($user->id !== auth()->id())
                                <!-- Toggle Status Button -->
                                <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="p-1.5 text-slate-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" 
                                            title="{{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}"
                                            onclick="return confirm('Apakah Anda yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} akun {{ $user->name }}?')">
                                        <i data-lucide="{{ $user->is_active ? 'user-x' : 'user-check' }}" class="w-4 h-4"></i>
                                    </button>
                                </form>

                                <!-- Delete Button -->
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-1.5 text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition" 
                                            title="Hapus Akun"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }}? Jika akun memiliki riwayat SPK/Stok, akun akan otomatis dialihkan ke status Nonaktif.')">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-slate-400">
                            <i data-lucide="users" class="w-8 h-8 mx-auto text-slate-300 mb-2"></i>
                            <p class="font-semibold text-sm text-slate-600">Tidak ada data pengguna yang cocok.</p>
                            <p class="text-xs text-slate-400">Coba ubah kata kunci pencarian atau filter status/role.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>

<!-- MODAL TAMBAH PENGGUNA -->
<div id="modal-add-user" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="p-2 bg-blue-100 text-blue-700 rounded-xl">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                </span>
                <h3 class="font-bold text-slate-900 text-base">Tambah Pengguna Baru</h3>
            </div>
            <button onclick="closeUserModal('modal-add-user')" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Pengguna <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="Contoh: Budi Santoso" class="w-full text-xs border border-slate-300 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Email Login <span class="text-red-500">*</span></label>
                <input type="email" name="email" required placeholder="nama@cahayaonix.com" class="w-full text-xs border border-slate-300 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hak Akses (Role) <span class="text-red-500">*</span></label>
                    <select name="role" required class="w-full text-xs border border-slate-300 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500 bg-white">
                        <option value="gudang">Gudang (Bahan Baku)</option>
                        <option value="produksi">Produksi (SPK & Mesin)</option>
                        <option value="distribusi">Distribusi (Surat Jalan)</option>
                        <option value="owner">Owner (Pemilik IKM)</option>
                        <option value="admin">Administrator (Pusat)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unit IKM Mitra <span class="text-red-500">*</span></label>
                    <select name="ikm_name" required class="w-full text-xs border border-slate-300 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500 bg-white">
                        @foreach($ikmOptions as $ikm)
                            <option value="{{ $ikm }}">{{ $ikm }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Awal <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full text-xs border border-slate-300 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">No. WhatsApp / HP</label>
                    <input type="text" name="phone" placeholder="Contoh: 081234567890" class="w-full text-xs border border-slate-300 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="add_is_active" value="1" checked class="rounded border-slate-300 text-blue-600 focus:ring-0">
                <label for="add_is_active" class="text-xs font-semibold text-slate-700 cursor-pointer">Status Akun Langsung Aktif</label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeUserModal('modal-add-user')" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition shadow-md shadow-blue-600/20 flex items-center gap-1.5">
                    <i data-lucide="check" class="w-4 h-4"></i> Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT PENGGUNA -->
<div id="modal-edit-user" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="p-2 bg-amber-100 text-amber-700 rounded-xl">
                    <i data-lucide="user-cog" class="w-5 h-5"></i>
                </span>
                <h3 class="font-bold text-slate-900 text-base">Edit Data Pengguna & Hak Akses</h3>
            </div>
            <button onclick="closeUserModal('modal-edit-user')" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="form-edit-user" method="POST" class="mt-4 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Pengguna <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="edit_name" required class="w-full text-xs border border-slate-300 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Email Login <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="edit_email" required class="w-full text-xs border border-slate-300 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hak Akses (Role) <span class="text-red-500">*</span></label>
                    <select name="role" id="edit_role" required class="w-full text-xs border border-slate-300 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500 bg-white">
                        <option value="gudang">Gudang (Bahan Baku)</option>
                        <option value="produksi">Produksi (SPK & Mesin)</option>
                        <option value="distribusi">Distribusi (Surat Jalan)</option>
                        <option value="owner">Owner (Pemilik IKM)</option>
                        <option value="admin">Administrator (Pusat)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unit IKM Mitra <span class="text-red-500">*</span></label>
                    <select name="ikm_name" id="edit_ikm_name" required class="w-full text-xs border border-slate-300 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500 bg-white">
                        @foreach($ikmOptions as $ikm)
                            <option value="{{ $ikm }}">{{ $ikm }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Reset Kata Sandi Baru</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full text-xs border border-slate-300 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">No. WhatsApp / HP</label>
                    <input type="text" name="phone" id="edit_phone" placeholder="Contoh: 081234567890" class="w-full text-xs border border-slate-300 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-0">
                <label for="edit_is_active" class="text-xs font-semibold text-slate-700 cursor-pointer">Status Akun Aktif</label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeUserModal('modal-edit-user')" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs transition shadow-md shadow-amber-600/20 flex items-center gap-1.5">
                    <i data-lucide="check" class="w-4 h-4"></i> Perbarui Akun
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddUserModal() {
        document.getElementById('modal-add-user').classList.remove('hidden');
    }

    function openEditUserModal(user) {
        document.getElementById('form-edit-user').action = `/users/${user.id}`;
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;
        document.getElementById('edit_ikm_name').value = user.ikm_name;
        document.getElementById('edit_phone').value = user.phone || '';
        document.getElementById('edit_is_active').checked = user.is_active;

        document.getElementById('modal-edit-user').classList.remove('hidden');
    }

    function closeUserModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
@endsection
