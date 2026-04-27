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
        $kantors = \App\Models\Kantor::all();
        $periodes = \App\Models\Periode::all();
        
        // Pilihan tampilan UI (Premium/Aesthetic)
        return view('auth.select-context', compact('kantors', 'periodes'));
    }

    /**
     * Update pilihan kantor dan periode aktif ke session.
     */
    public function update(Request $request)
    {
        $request->validate([
            'kantor_id' => ['nullable', 'integer', 'exists:kantors,id'],
            'periode_id' => ['nullable', 'integer', 'exists:periodes,id'],
            'redirect' => ['nullable', 'string']
        ]);

        if ($request->filled('kantor_id')) {
            $request->session()->put('kantor_id', $request->kantor_id);
        } else {
            $request->session()->forget('kantor_id');
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
