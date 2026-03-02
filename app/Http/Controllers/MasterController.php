<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master;
use Illuminate\Support\Facades\Storage;

class MasterController extends Controller
{
    public function index()
    {
        $master = Master::first() ?? new Master();
        return view('admin.master.index', compact('master'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_lembaga'   => 'nullable|string|max:255',
            'alamat_lembaga' => 'nullable|string',
            'instance_id'    => 'nullable|string|max:255',
            'wa_token'       => 'nullable|string|max:255',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $master = Master::first() ?? new Master();

        $master->nama_lembaga   = $validated['nama_lembaga'] ?? $master->nama_lembaga;
        $master->alamat_lembaga = $validated['alamat_lembaga'] ?? $master->alamat_lembaga;
        $master->instance_id    = $validated['instance_id'] ?? $master->instance_id;
        $master->wa_token       = $validated['wa_token'] ?? $master->wa_token;

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($master->logo && Storage::disk('public')->exists($master->logo)) {
                Storage::disk('public')->delete($master->logo);
            }

            $path = $request->file('logo')->store('logos', 'public');
            $master->logo = $path;
        }

        $master->save();

        return redirect()->route('master.index')->with('success', 'Data Master berhasil diperbarui.');
    }
}
