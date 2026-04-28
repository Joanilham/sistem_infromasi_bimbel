<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    /**
     * Role yang diizinkan dikelola oleh Administrator via halaman ini.
     * Siswa & Guru TIDAK bisa diubah levelnya ke administrator/staff melalui sini.
     */
    protected array $allowedLevels = ['administrator', 'staff'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Hanya tampilkan pengguna dengan level administrator & staff
        $penggunas = User::whereIn('level', $this->allowedLevels)->latest()->get();
        return view('admin.pengguna.index', compact('penggunas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'username'              => 'nullable|string|max:255|unique:users',
            'email'                 => 'required|string|email|max:255|unique:users',
            'level'                 => ['required', 'string', Rule::in($this->allowedLevels)],
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = true;

        unset($validated['password_confirmation']);
        User::create($validated);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pengguna = User::whereIn('level', $this->allowedLevels)->findOrFail($id);

        // Pastikan pengguna yang diupdate memang level administrator/staff
        if (!in_array($pengguna->level, $this->allowedLevels)) {
            return back()->withErrors(['error' => 'Pengguna ini tidak dapat dikelola dari halaman ini.']);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($pengguna->id)],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($pengguna->id)],
            'level'    => ['required', 'string', Rule::in($this->allowedLevels)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

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

        if ($pengguna->id === Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $pengguna->delete();
        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
