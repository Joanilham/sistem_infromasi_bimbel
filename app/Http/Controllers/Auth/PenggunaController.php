<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Http\Requests\Auth\StorePenggunaRequest;
use App\Http\Requests\Auth\UpdatePenggunaRequest;

class PenggunaController extends Controller
{
    protected array $allowedLevels = ['Super Admin', 'Admin', 'Staff'];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search  = $request->input('search', '');
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $sort    = in_array($request->input('sort'), ['id', 'name']) ? $request->input('sort') : 'id';
        $order   = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'desc';

        // Hanya tampilkan pengguna sesuai hak akses
        $penggunas = User::whereIn('level', $this->allowedLevels)
            ->when(strtolower(auth()->user()->level) !== 'super admin', function ($q) {
                $q->whereRaw('LOWER(level) != ?', ['super admin']);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                       ->orWhere('username', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy($sort, $order)
            ->paginate($perPage)
            ->withQueryString();
            
        return view('admin.pengguna.index', compact('penggunas', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pengguna.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePenggunaRequest $request)
    {
        if ($request->level === 'Super Admin') {
            abort(403, 'Pembuatan akun Super Admin baru tidak diizinkan demi keamanan sistem.');
        }

        // Cegah Admin biasa membuat Admin
        if (strtolower(auth()->user()->level) === 'admin' && in_array($request->level, ['Admin'])) {
            abort(403, 'Admin hanya dapat membuat akun Staff.');
        }

        $validated = $request->validated();

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = true;

        // Super Admin memiliki semua akses, tidak perlu array permissions
        if ($validated['level'] === 'Super Admin') {
            $validated['permissions'] = null;
        }

        unset($validated['password_confirmation']);
        User::create($validated);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pengguna = User::whereIn('level', $this->allowedLevels)->findOrFail($id);

        if (strtolower($pengguna->level) === 'super admin' && strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Anda tidak memiliki akses untuk mengelola Super Admin.');
        }

        // Admin biasa tidak boleh mengedit Admin
        if (strtolower(auth()->user()->level) === 'admin' && strtolower($pengguna->level) === 'admin') {
            abort(403, 'Admin hanya dapat mengelola akun Staff.');
        }

        if (!in_array($pengguna->level, $this->allowedLevels)) {
            return back()->withErrors(['error' => 'Pengguna ini tidak dapat dikelola dari halaman ini.']);
        }

        return view('admin.pengguna.edit', compact('pengguna'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePenggunaRequest $request, User $pengguna)
    {
        if (strtolower($pengguna->level) === 'super admin' && strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Anda tidak memiliki akses untuk mengelola Super Admin.');
        }
        
        // Admin biasa tidak boleh mengedit Admin (termasuk dirinya sendiri lewat menu ini)
        if (strtolower(auth()->user()->level) === 'admin' && strtolower($pengguna->level) === 'admin') {
            abort(403, 'Admin hanya dapat mengelola akun Staff.');
        }

        if ($request->level === 'Super Admin' && $pengguna->level !== 'Super Admin') {
            abort(403, 'Perubahan role ke Super Admin tidak diizinkan demi keamanan sistem.');
        }

        // Cegah update ke Admin oleh Admin biasa
        if (strtolower(auth()->user()->level) === 'admin' && in_array($request->level, ['Admin']) && strtolower($pengguna->level) !== $request->level) {
            abort(403, 'Admin hanya dapat mengatur level sebagai Staff.');
        }

        $validated = $request->validated();

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Tidak boleh nonaktifkan akun sendiri
        $validated['is_active'] = $request->boolean('is_active', true);
        if ($pengguna->id === Auth::id() && !$validated['is_active']) {
            return back()->withErrors(['error' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.']);
        }

        // Tidak boleh mengubah level akun sendiri
        if ($pengguna->id === Auth::id() && $validated['level'] !== Auth::user()->level) {
            return back()->withErrors(['error' => 'Anda tidak dapat mengubah level akun Anda sendiri.']);
        }

        if ($validated['level'] === 'Super Admin') {
            $validated['permissions'] = null;
        }

        unset($validated['password_confirmation']);
        $pengguna->update($validated);

        return redirect()->route('pengguna.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive($id)
    {
        $pengguna = User::whereIn('level', $this->allowedLevels)->findOrFail($id);

        if (strtolower($pengguna->level) === 'super admin' && strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Anda tidak memiliki akses untuk mengelola Super Admin.');
        }

        if (strtolower(auth()->user()->level) === 'admin' && strtolower($pengguna->level) === 'admin') {
            abort(403, 'Admin hanya dapat mengelola akun Staff.');
        }

        if ($pengguna->id === Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.']);
        }

        $pengguna->is_active = !$pengguna->is_active;
        $pengguna->save();

        $status = $pengguna->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('pengguna.index')->with('success', "Pengguna berhasil {$status}.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pengguna = User::whereIn('level', $this->allowedLevels)->findOrFail($id);

        if (strtolower($pengguna->level) === 'super admin' && strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Anda tidak memiliki akses untuk mengelola Super Admin.');
        }

        if ($pengguna->id === Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $pengguna->delete();
        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}


