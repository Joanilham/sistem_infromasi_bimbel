<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KonteksController extends Controller
{
    /**
     * Update pilihan kantor dan periode aktif ke session.
     */
    public function update(Request $request)
    {
        $request->validate([
            'kantor_id' => ['nullable', 'integer', 'exists:kantors,id'],
            'periode_id' => ['nullable', 'integer', 'exists:periodes,id'],
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

        return back()->with('success', 'Konteks kantor dan periode berhasil diubah.');
    }
}
