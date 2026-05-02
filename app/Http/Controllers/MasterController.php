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
            'wa_url'         => 'nullable|url|max:255',
            'instance_id'    => 'nullable|string|max:255',
            'wa_token'       => 'nullable|string|max:255',
            'api_key'  => 'nullable|string|max:255',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $master = Master::first() ?? new Master();

        $master->nama_lembaga   = array_key_exists('nama_lembaga', $validated) ? $validated['nama_lembaga'] : $master->nama_lembaga;
        $master->alamat_lembaga = array_key_exists('alamat_lembaga', $validated) ? $validated['alamat_lembaga'] : $master->alamat_lembaga;
        $master->wa_url         = array_key_exists('wa_url', $validated) ? $validated['wa_url'] : $master->wa_url;
        $master->instance_id    = array_key_exists('instance_id', $validated) ? $validated['instance_id'] : $master->instance_id;
        $master->wa_token       = array_key_exists('wa_token', $validated) ? $validated['wa_token'] : $master->wa_token;
        $master->api_key  = array_key_exists('api_key', $validated) ? $validated['api_key'] : $master->_api_key;

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

    public function testWhatsApp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $waService = new \App\Services\WhatsAppService();
        $response = $waService->sendMessage($request->phone, $request->message);

        if ($response['status'] == 'success') {
            return redirect()->back()->with('success', 'Pesan Uji Coba WhatsApp berhasil dikirim!');
        } else {
            return redirect()->back()->withErrors(['wa_error' => 'Gagal mengirim pesan: ' . ($response['message'] ?? 'Periksa kembali pengaturan Gateway.')]);
        }
    }
}
