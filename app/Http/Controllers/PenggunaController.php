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
     * Display a listing of the resource.
     */
    public function index()
    {
        $penggunas = User::latest()->get();
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
            'level'                 => 'required|string|in:administrator,staff',
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
        $pengguna = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($pengguna->id)],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($pengguna->id)],
            'level'    => 'required|string|in:administrator,staff',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Update status aktif / nonaktif (hanya dari form)
        $validated['is_active'] = $request->boolean('is_active', true);

        // Tidak boleh nonaktifkan akun sendiri
        if ($pengguna->id === Auth::id() && !$validated['is_active']) {
            return back()->withErrors(['error' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.']);
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
        $pengguna = User::findOrFail($id);

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
        $pengguna = User::findOrFail($id);

        if ($pengguna->id === Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $pengguna->delete();
        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
