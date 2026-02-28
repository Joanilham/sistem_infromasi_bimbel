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
        $request->validate([
            'nama_lembaga' => 'nullable|string|max:255',
            'alamat_lembaga' => 'nullable|string',
            'instance_id' => 'nullable|string|max:255',
            'wa_token' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $master = Master::first() ?? new Master();

        $master->nama_lembaga = $request->nama_lembaga;
        $master->alamat_lembaga = $request->alamat_lembaga;
        $master->instance_id = $request->instance_id;
        $master->wa_token = $request->wa_token;

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
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
