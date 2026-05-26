<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KonteksController extends Controller
{
    /**
     * Tampilkan halaman inisial untuk memilih konteks sesudah login
     */
    public function selectContext(Request $request)
    {
        $user = $request->user();
        $isSuperAdmin = strtolower($user->level) === 'super admin';

        if ($isSuperAdmin) {
            $kantors = \App\Models\Kantor::all();
        } else {
            // Jika Admin (cabang), hanya boleh memilih kantor yang terasosiasi dengannya
            $assignedKantorId = $user->kantor_id ?: (\App\Models\Kantor::first()->id ?? null);
            if ($assignedKantorId) {
                $kantors = \App\Models\Kantor::where('id', $assignedKantorId)->get();
            } else {
                $kantors = collect();
            }
        }

        $periodes = \App\Models\Periode::all();
        
        return view('auth.select-context', compact('kantors', 'periodes'));
    }

    /**
     * Update pilihan kantor dan periode aktif ke session.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'kantor_id' => ['nullable', function($attribute, $value, $fail) {
                if ($value !== 'all' && !in_array($value, \App\Models\Kantor::pluck('id')->toArray())) {
                    $fail('Cabang/Kantor tidak valid.');
                }
            }],
            'periode_id' => ['nullable', 'integer', 'exists:periodes,id'],
            'redirect' => ['nullable', 'string']
        ]);

        $kantorId = $request->kantor_id;
        $isSuperAdmin = strtolower($user->level) === 'super admin';

        if (!$isSuperAdmin) {
            // Jika bukan Super Admin, paksa kantor_id ke yang terdaftar di user record (atau kantor pertama jika kosong)
            $kantorId = $user->kantor_id ?: (\App\Models\Kantor::first()->id ?? null);
            if ($kantorId) {
                $request->session()->put('kantor_id', $kantorId);
            } else {
                $request->session()->forget('kantor_id');
            }
        } else {
            // Superadmin bisa pilih 'all' (semua cabang)
            if ($kantorId === 'all' || !$kantorId) {
                $request->session()->put('kantor_id', 'all');
            } else {
                $request->session()->put('kantor_id', $kantorId);
            }
        }

        if ($request->filled('periode_id')) {
            $request->session()->put('periode_id', $request->periode_id);
        } else {
            $request->session()->forget('periode_id');
        }

        if ($request->filled('redirect')) {
            return redirect($request->redirect)->with('success', 'Konteks berhasil dikonfigurasi. Selamat Bekerja!');
        }

        return back()->with('success', 'Konteks kantor dan periode berhasil diubah.');
    }
}
