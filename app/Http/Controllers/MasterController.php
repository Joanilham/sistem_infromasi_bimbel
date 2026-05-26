<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master;
use Illuminate\Support\Facades\Storage;

class MasterController extends Controller
{
    use \App\Traits\HandlesImageUpload;

    public function index()
    {
        $master = Master::first() ?? new Master();
        return view('admin.master.index', compact('master'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_lembaga'      => 'nullable|string|max:255',
            'alamat_lembaga'    => 'nullable|string',
            'wa_url'            => 'nullable|url|max:255',
            'instance_id'       => 'nullable|string|max:255',
            'wa_token'          => 'nullable|string|max:255',
            'api_key'           => 'nullable|string|max:255',
            'logo'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        try {
            $master = Master::first() ?? new Master();

            $master->nama_lembaga   = $validated['nama_lembaga'] ?? $master->nama_lembaga;
            $master->alamat_lembaga = $validated['alamat_lembaga'] ?? $master->alamat_lembaga;
            $master->wa_url         = $validated['wa_url'] ?? $master->wa_url;
            $master->instance_id    = $validated['instance_id'] ?? $master->instance_id;
            $master->wa_token       = $validated['wa_token'] ?? $master->wa_token;
            $master->api_key        = $validated['api_key'] ?? $master->api_key;

            if ($request->hasFile('logo')) {
                // Hapus logo lama jika ada
                if ($master->logo) {
                    Storage::disk('public')->delete($master->logo);
                }

                // Kompres dan simpan logo baru
                $master->logo = $this->compressAndStore($request->file('logo'), 'logos', 80);
            }

            $master->save();
            
            // Hapus cache agar logo dan data master langsung ter-update di seluruh sistem
            \Illuminate\Support\Facades\Cache::forget('global_master');

            return redirect()->route('master.index')->with('success', 'Data Master berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Master Update Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui data master.');
        }
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
