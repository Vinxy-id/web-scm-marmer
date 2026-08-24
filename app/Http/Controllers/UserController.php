<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Guard: Ensure only Owner or Admin can access User Management.
     */
    protected function authorizeAccess(): void
    {
        if (!in_array(auth()->user()->role ?? '', ['owner', 'admin'])) {
            abort(403, 'Akses ditolak. Menu Manajemen Pengguna hanya dapat diakses oleh Owner dan Administrator.');
        }
    }

    /**
     * Display a listing of users with filters and KPI metrics.
     */
    public function index(Request $request)
    {
        $this->authorizeAccess();

        $query = User::query()->orderBy('id', 'asc');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('ikm_name')) {
            $query->where('ikm_name', $request->ikm_name);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->paginate(15)->withQueryString();

        // KPI Metrics
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $adminOwnerCount = User::whereIn('role', ['owner', 'admin'])->count();
        $staffCount = User::whereIn('role', ['gudang', 'produksi', 'distribusi'])->count();

        // List of IKM options
        $ikmOptions = [
            'UD Cahaya Onix',
            'UD Putra Abadi',
            'Pusat Klaster Tulungagung',
        ];

        return view('users.index', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'adminOwnerCount',
            'staffCount',
            'ikmOptions'
        ));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $this->authorizeAccess();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6|max:100',
            'role' => 'required|in:owner,admin,gudang,produksi,distribusi',
            'phone' => 'nullable|string|max:20',
            'ikm_name' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Nama lengkap pengguna wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email sudah terdaftar di sistem.',
            'password.required' => 'Kata sandi awal wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'role.required' => 'Hak akses (role) wajib dipilih.',
            'role.in' => 'Role tidak valid.',
            'ikm_name.required' => 'Asal unit IKM wajib dipilih.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        User::create($validated);

        return redirect()->route('users.index')->with('success', "Akun pengguna '{$validated['name']}' dengan role '{$validated['role']}' berhasil dibuat.");
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $this->authorizeAccess();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:6|max:100',
            'role' => 'required|in:owner,admin,gudang,produksi,distribusi',
            'phone' => 'nullable|string|max:20',
            'ikm_name' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Nama lengkap pengguna wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email sudah digunakan oleh pengguna lain.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
            'role.required' => 'Hak akses (role) wajib dipilih.',
            'role.in' => 'Role tidak valid.',
            'ikm_name.required' => 'Asal unit IKM wajib dipilih.',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        // Guard: Prevent disabling own account
        if ($user->id === auth()->id() && !$validated['is_active']) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri yang sedang aktif digunakan.');
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', "Data akun pengguna '{$user->name}' berhasil diperbarui.");
    }

    /**
     * Toggle active/inactive status of a user.
     */
    public function toggleStatus(User $user)
    {
        $this->authorizeAccess();

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mengubah status akun sendiri yang sedang aktif digunakan.');
        }

        $newStatus = !$user->is_active;
        $user->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'diaktifkan kembali' : 'dinonaktifkan';
        return redirect()->route('users.index')->with('success', "Akses akun '{$user->name}' berhasil {$statusText}.");
    }

    /**
     * Remove the specified user or deactivate if has operational history.
     */
    public function destroy(User $user)
    {
        $this->authorizeAccess();

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri yang sedang aktif digunakan.');
        }

        // Safety Guard: Check if user has associated history
        $hasWorkOrders = $user->workOrders()->exists();
        $hasTransactions = $user->stockTransactions()->exists();

        if ($hasWorkOrders || $hasTransactions) {
            $user->update(['is_active' => false]);
            return redirect()->route('users.index')->with('warning', "Akun '{$user->name}' memiliki riwayat data operasional (SPK / Mutasi Stok), sehingga akun dialihkan ke status Nonaktif (Soft Deactivation) untuk menjaga integritas data audit.");
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')->with('success', "Akun pengguna '{$userName}' berhasil dihapus permanen dari sistem.");
    }
}
