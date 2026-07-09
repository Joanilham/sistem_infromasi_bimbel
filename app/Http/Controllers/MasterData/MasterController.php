<?php

namespace App\Http\Controllers\MasterData;
use App\Models\System\Message;

use App\Http\Controllers\Controller;

use App\Models\MasterData\Master;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\MasterData\MasterRequest;
use App\Http\Requests\MasterData\TestWhatsAppRequest;

class MasterController extends Controller
{
    use \App\Traits\HandlesImageUpload;

    public function index()
    {
        $master = Master::first() ?? new Master();
        return view('admin.master.index', compact('master'));
    }

    public function update(MasterRequest $request)
    {
        $validated = $request->validated();

        try {
            $master = Master::first() ?? new Master();

            $fieldsToUpdate = [
                'nama_lembaga', 'alamat_lembaga', 'wa_url', 'instance_id', 'wa_token', 'api_key',
                'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name',
                'cloud_backup_provider', 'gdrive_client_id', 'gdrive_client_secret', 'gdrive_refresh_token', 'gdrive_folder_id'
            ];

            foreach ($fieldsToUpdate as $field) {
                if (array_key_exists($field, $validated)) {
                    $master->$field = $validated[$field];
                }
            }

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
            \Illuminate\Support\Facades\Cache::forget('welcome_page_data');

            return redirect()->route('master.index')->with('success', 'Data Master berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Master Update Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui data master.');
        }
    }

    public function testWhatsApp(TestWhatsAppRequest $request)
    {
        $validated = $request->validated();

        $waService = new \App\Services\WhatsAppService();
        $response = $waService->sendMessage($request->phone, $request->message);

        if ($response['status'] == 'success') {
            return redirect()->back()->with('success', 'Pesan Uji Coba WhatsApp berhasil dikirim!');
        } else {
            return redirect()->back()->withErrors(['wa_error' => 'Gagal mengirim pesan: ' . ($response['message'] ?? 'Periksa kembali pengaturan Gateway.')]);
        }
    }

    public function testEmail(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            // PAKSA BACA DARI DATABASE SECARA REAL-TIME UNTUK MENGHINDARI CACHE OCTANE
            $master = Master::first();
            if ($master && $master->mail_host) {
                config([
                    'mail.default' => 'smtp',
                    'mail.mailer' => 'smtp',
                    'mail.mailers.smtp.host' => $master->mail_host,
                    'mail.mailers.smtp.port' => $master->mail_port,
                    'mail.mailers.smtp.encryption' => $master->mail_encryption,
                    'mail.mailers.smtp.username' => $master->mail_username,
                    'mail.mailers.smtp.password' => $master->mail_password,
                    'mail.from.address' => $master->mail_from_address,
                    'mail.from.name' => $master->mail_from_name,
                ]);
                \Illuminate\Support\Facades\Mail::purge();
                if (app()->bound('mail.manager')) {
                    app('mail.manager')->forgetMailers();
                }
            }

            \Illuminate\Support\Facades\Mail::raw('Halo! Ini adalah pesan uji coba dari konfigurasi Layanan Email (SMTP) Sistem Informasi Bimbel Sistem Akademik. Jika Anda menerima email ini, berarti pengaturan email Anda sudah benar dan berfungsi dengan baik.', function ($message) use ($request, $master) {
                $message->to($request->email)
                        ->subject('Uji Coba Pengaturan Email - Sistem Akademik');
                        
                // Pastikan From address explicitly di-set jika belum ada di config global
                if ($master && $master->mail_from_address) {
                    $message->from($master->mail_from_address, $master->mail_from_name ?? 'Sistem Akademik');
                }
            });

            return redirect()->back()->with('success', 'Email uji coba berhasil dikirim ke ' . $request->email);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Test Email Error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['email_error' => 'Gagal mengirim email: ' . $e->getMessage()]);
        }
    }
}




