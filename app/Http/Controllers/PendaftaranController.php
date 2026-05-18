<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranSiswa;
use App\Models\PembayaranPendaftaran;
use App\Models\PaketBimbingan;
use App\Models\KelompokBelajar;
use App\Models\Kantor;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Traits\HandlesImageUpload;

class PendaftaranController extends Controller
{
    use HandlesImageUpload;
    // ──────────────────────────────────────────
    // STEP 1: Buat Akun + Pilih Kantor
    // ──────────────────────────────────────────

    public function step1()
    {
        $kantors = Kantor::orderBy('nama_kantor')->get();
        return view('pendaftaran.step1', compact('kantors'));
    }

    public function step1Store(Request $request)
    {
        $request->validate([
            'email'      => 'required|email|unique:pendaftaran_siswas,email',
            'password'   => [
                'required', 
                'confirmed', 
                \Illuminate\Validation\Rules\Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'kantor_id'  => 'required|exists:kantors,id',
        ], [
            'email.unique'      => 'Email ini sudah terdaftar. Gunakan email lain.',
            'kantor_id.required'=> 'Silakan pilih kantor/cabang tujuan.',
            'kantor_id.exists'  => 'Kantor yang dipilih tidak valid.',
            'password.min'      => 'Password minimal harus 8 karakter.',
        ]);

        Session::put('daftar_email', $request->email);
        Session::put('daftar_password', $request->password);
        Session::put('daftar_kantor_id', $request->kantor_id);

        return redirect()->route('daftar.step2');
    }

    // ──────────────────────────────────────────
    // STEP 2: Data Diri
    // ──────────────────────────────────────────

    public function step2()
    {
        if (!Session::has('daftar_email')) {
            return redirect()->route('daftar.step1');
        }
        $kantorId  = Session::get('daftar_kantor_id');
        $pakets    = PaketBimbingan::when($kantorId, fn($q) => $q->where('kantor_id', $kantorId))->get();
        $kelompoks = KelompokBelajar::when($kantorId, fn($q) => $q->where('kantor_id', $kantorId))->get();
        return view('pendaftaran.step2', compact('pakets', 'kelompoks'));
    }

    public function step2Store(Request $request)
    {
        if (!Session::has('daftar_email')) {
            return redirect()->route('daftar.step1');
        }

        $request->validate([
            'nama_lengkap'       => 'required|string|max:255',
            'jenis_kelamin'      => 'required|in:L,P',
            'asal_sekolah'       => 'required|string|max:255',
            'paket_bimbingan_id' => 'nullable|exists:paket_bimbingans,id',
            'no_telepon'         => ['nullable', 'regex:/^[0-9]{8,15}$/'],
            'no_telepon_ayah'    => ['nullable', 'regex:/^[0-9]{8,15}$/'],
            'no_telepon_ibu'     => ['nullable', 'regex:/^[0-9]{8,15}$/'],
        ]);

        Session::put('daftar_data', $request->except('_token'));

        return redirect()->route('daftar.step3');
    }

    // ──────────────────────────────────────────
    // STEP 3: Pembayaran & Simpan
    // ──────────────────────────────────────────

    public function step3()
    {
        if (!Session::has('daftar_email') || !Session::has('daftar_data')) {
            return redirect()->route('daftar.step1');
        }
        $data      = Session::get('daftar_data');
        $kantorId  = Session::get('daftar_kantor_id');
        $paketId   = $data['paket_bimbingan_id'] ?? null;
        $paket     = $paketId ? PaketBimbingan::find($paketId) : null;
        $pakets    = PaketBimbingan::when($kantorId, fn($q) => $q->where('kantor_id', $kantorId))->get();
        return view('pendaftaran.step3', compact('paket', 'pakets'));
    }

    public function step3Store(Request $request)
    {
        if (!Session::has('daftar_email') || !Session::has('daftar_data')) {
            return redirect()->route('daftar.step1');
        }

        $request->validate([
            'metode_pembayaran'   => 'required|string',
            'bukti_pembayaran'    => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'paket_bimbingan_id'  => 'nullable|exists:paket_bimbingans,id',
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti_pembayaran.mimes'    => 'Format bukti pembayaran hanya diperbolehkan: JPG, JPEG, PNG, PDF.',
            'bukti_pembayaran.max'      => 'Ukuran file maksimal adalah 5MB.',
        ]);

        $data      = Session::get('daftar_data');
        
        // Update paket_bimbingan_id di session jika user memilih/mengubah di Step 3
        if ($request->filled('paket_bimbingan_id')) {
            $data['paket_bimbingan_id'] = $request->paket_bimbingan_id;
            Session::put('daftar_data', $data);
        }
        $email     = Session::get('daftar_email');
        $password  = Session::get('daftar_password');
        $kantorId  = Session::get('daftar_kantor_id');

        // Ambil kantor sesuai pilihan siswa & periode aktif
        $kantor  = Kantor::find($kantorId) ?? Kantor::first();
        $periode = Periode::where('is_active', true)->first();

        // Generate token verifikasi email
        $verifToken = Str::random(64);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $pendaftaran = PendaftaranSiswa::create([
                'email'                   => $email,
                'password'                => Hash::make($password),
                'email_verification_token'=> $verifToken,
                'email_verified_at'       => null,
                'nama_lengkap'            => $data['nama_lengkap'],
                'nisn'                    => $data['nisn'] ?? null,
                'jenis_kelamin'           => $data['jenis_kelamin'],
                'tempat_lahir'            => $data['tempat_lahir'] ?? null,
                'tanggal_lahir'           => $data['tanggal_lahir'] ?? null,
                'agama'                   => $data['agama'] ?? null,
                'alamat_lengkap'          => $data['alamat_lengkap'] ?? null,
                'no_telepon'              => $data['no_telepon'] ?? null,
                'asal_sekolah'            => $data['asal_sekolah'],
                'paket_bimbingan_id'      => $data['paket_bimbingan_id'] ?? null,
                'kelompok_belajar_id'     => $data['kelompok_belajar_id'] ?? null,
                'informasi_dari'          => $data['informasi_dari'] ?? null,
                'nama_ayah'               => $data['nama_ayah'] ?? null,
                'pekerjaan_ayah'          => $data['pekerjaan_ayah'] ?? null,
                'no_telepon_ayah'         => $data['no_telepon_ayah'] ?? null,
                'nama_ibu'                => $data['nama_ibu'] ?? null,
                'pekerjaan_ibu'           => $data['pekerjaan_ibu'] ?? null,
                'no_telepon_ibu'          => $data['no_telepon_ibu'] ?? null,
                'status'                  => 'menunggu',
                'kantor_id'               => $kantor?->id,
                'periode_id'              => $periode?->id,
            ]);

            // Upload bukti pembayaran (Kompresi jika gambar)
            $file = $request->file('bukti_pembayaran');
            if (in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'webp'])) {
                $path = $this->compressAndStore($file, 'bukti_pembayaran');
            } else {
                $path = $file->store('bukti_pembayaran', 'public');
            }

            // Jumlah diambil dari paket yang dipilih
            $jumlahDibayar = null;
            if (!empty($data['paket_bimbingan_id'])) {
                $paketTerpilih = \App\Models\PaketBimbingan::find($data['paket_bimbingan_id']);
                $jumlahDibayar = $paketTerpilih?->nominal;
            }

            PembayaranPendaftaran::create([
                'pendaftaran_siswa_id' => $pendaftaran->id,
                'metode_pembayaran'    => $request->metode_pembayaran,
                'jumlah'               => $jumlahDibayar,
                'bukti_pembayaran'     => $path,
                'status'               => 'menunggu',
            ]);

            \Illuminate\Support\Facades\DB::commit();

            // Kirim Notifikasi WA Pendaftaran Baru
            $nomor = $pendaftaran->no_telepon ?? $pendaftaran->no_telepon_ayah ?? $pendaftaran->no_telepon_ibu;
            if ($nomor) {
                dispatch(function () use ($pendaftaran, $nomor) {
                    try {
                        $pesan = "📚 *Pendaftaran Berhasil*\n\nHalo *{$pendaftaran->nama_lengkap}*,\n\nTerima kasih telah mendaftar di bimbingan belajar kami! Data pendaftaran Anda telah kami terima dengan status: *MENUNGGU VERIFIKASI*.\n\nSilakan tunggu konfirmasi selanjutnya dari admin melalui WhatsApp ini.\n\nTerima kasih. 🙏";
                        (new \App\Services\WhatsAppService())->sendMessage($nomor, $pesan);
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Gagal kirim WA Pendaftaran Baru: " . $e->getMessage());
                    }
                })->afterResponse();
            }

            Session::forget(['daftar_email', 'daftar_password', 'daftar_data', 'daftar_kantor_id']);
            return redirect()->route('daftar.selesai')->with('kode_pendaftaran', $pendaftaran->id);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Pendaftaran Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memproses pendaftaran. Silakan coba beberapa saat lagi.');
        }
    }

    // Halaman selesai
    public function selesai()
    {
        return view('pendaftaran.selesai');
    }

    // ──────────────────────────────────────────────────────────
    // VERIFIKASI EMAIL (link yang dikirim ke email siswa)
    // ──────────────────────────────────────────────────────────

    public function verifikasiEmail(Request $request, $token)
    {
        $pendaftaran = PendaftaranSiswa::where('email_verification_token', $token)->first();

        if (!$pendaftaran) {
            return redirect()->route('login')->withErrors([
                'email' => 'Link verifikasi tidak valid atau sudah digunakan.',
            ]);
        }

        if ($pendaftaran->isEmailVerified()) {
            return redirect()->route('login')->with('info', 'Email Anda sudah diverifikasi sebelumnya.');
        }

        $pendaftaran->update([
            'email_verified_at'        => now(),
            'email_verification_token' => null,
        ]);

        return redirect()->route('login')->with('success',
            '✅ Email berhasil diverifikasi! Silakan login dan tunggu proses verifikasi admin.'
        );
    }

    // ──────────────────────────────────────────────────────────
    // ADMIN: Daftar & Detail Pendaftaran
    // ──────────────────────────────────────────────────────────

    public function adminIndex(Request $request)
    {
        $search     = $request->input('search', '');
        $status     = $request->input('status');
        $paketId    = $request->input('paket_id');
        $perPage    = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $sort       = in_array($request->input('sort'), ['id', 'nama_lengkap', 'paket_bimbingan_id']) ? $request->input('sort') : 'id';
        $order      = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'desc';

        $isSuperAdmin = auth()->check() && strtolower(auth()->user()->level) === 'super admin';
        $kantorId = session('kantor_id');

        $pendaftarans = PendaftaranSiswa::with(['paketBimbingan', 'pembayaran', 'kantor'])
            ->unless($isSuperAdmin, fn($q) => $q->where('kantor_id', $kantorId))
            ->when($search, function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($paketId, fn($q) => $q->where('paket_bimbingan_id', $paketId))
            ->orderBy($sort, $order)
            ->paginate($perPage)
            ->withQueryString();

        $pakets = PaketBimbingan::inContext()->get();

        return view('admin.pendaftaran.index', compact('pendaftarans', 'search', 'perPage', 'pakets'));
    }

    public function adminShow(PendaftaranSiswa $pendaftaran)
    {
        $pendaftaran->load(['paketBimbingan', 'kelompokBelajar', 'pembayaran', 'kantor']);
        return view('admin.pendaftaran.show', compact('pendaftaran'));
    }

    // ──────────────────────────────────────────────────────────
    // ADMIN: Verifikasi (Terima / Tolak)
    // ──────────────────────────────────────────────────────────

    public function adminVerifikasi(Request $request, PendaftaranSiswa $pendaftaran)
    {
        $request->validate([
            'aksi'          => 'required|in:diverifikasi,ditolak',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        // Pastikan tidak memproses yang sudah diverifikasi/ditolak
        if ($pendaftaran->status !== 'menunggu') {
            return back()->withErrors(['aksi' => 'Pendaftaran ini sudah diproses sebelumnya.']);
        }

        if ($request->aksi === 'diverifikasi') {
            // Buat record PesertaDidik dengan kantor & periode dari pendaftaran siswa
            $peserta = \App\Models\PesertaDidik::create([
                'kantor_id'           => $pendaftaran->kantor_id,
                'periode_id'          => $pendaftaran->periode_id,
                'nama_lengkap'        => $pendaftaran->nama_lengkap,
                'nisn'                => $pendaftaran->nisn ?? ('REG-' . $pendaftaran->id),
                'jenis_kelamin'       => $pendaftaran->jenis_kelamin,
                'tempat_lahir'        => $pendaftaran->tempat_lahir,
                'tanggal_lahir'       => $pendaftaran->tanggal_lahir,
                'agama'               => $pendaftaran->agama,
                'alamat_lengkap'      => $pendaftaran->alamat_lengkap,
                'no_telepon'          => $pendaftaran->no_telepon,
                'asal_sekolah'        => $pendaftaran->asal_sekolah,
                'paket_bimbingan_id'  => $pendaftaran->paket_bimbingan_id,
                'kelompok_belajar_id' => $pendaftaran->kelompok_belajar_id,
                'informasi_dari'      => $pendaftaran->informasi_dari,
                'nama_ayah'           => $pendaftaran->nama_ayah,
                'pekerjaan_ayah'      => $pendaftaran->pekerjaan_ayah,
                'no_telepon_ayah'     => $pendaftaran->no_telepon_ayah,
                'nama_ibu'            => $pendaftaran->nama_ibu,
                'pekerjaan_ibu'       => $pendaftaran->pekerjaan_ibu,
                'no_telepon_ibu'      => $pendaftaran->no_telepon_ibu,
                'status'              => 'Aktif',
            ]);

            // Buat akun User siswa
            \App\Models\User::create([
                'name'             => $pendaftaran->nama_lengkap,
                'email'            => $pendaftaran->email,
                'username'         => $pendaftaran->email,
                'password'         => $pendaftaran->password, // sudah di-hash
                'level'            => 'siswa',
                'is_active'        => true,
                'peserta_didik_id' => $peserta->id,
            ]);

            // Konfirmasi pembayaran
            if ($pendaftaran->pembayaran) {
                $pendaftaran->pembayaran->update(['status' => 'dikonfirmasi']);
            }
        }

        if ($request->aksi === 'ditolak') {
            if ($pendaftaran->pembayaran) {
                $pendaftaran->pembayaran->delete();
            }
            $pendaftaran->delete();
        } else {
            $pendaftaran->update([
                'status'             => $request->aksi,
                'catatan_admin'      => $request->catatan_admin,
                'email_verified_at'  => $request->aksi === 'diverifikasi' ? now() : $pendaftaran->email_verified_at,
            ]);
        }

        // Bersihkan cache dashboard jika ada
        try {
            $kantorId  = $pendaftaran->kantor_id;
            $periodeId = $pendaftaran->periode_id;
            \Illuminate\Support\Facades\Cache::forget("dash_total_peserta_{$kantorId}_{$periodeId}");
            \Illuminate\Support\Facades\Cache::forget("dash_peserta_baru_{$kantorId}_{$periodeId}");
            \Illuminate\Support\Facades\Cache::forget("data_peserta_didiks_aktif_{$kantorId}_{$periodeId}");
        } catch (\Throwable $e) {}

        // Kirim Notifikasi WA setelah commit
        if ($request->aksi === 'diverifikasi' || $request->aksi === 'ditolak') {
            $nomor = $pendaftaran->no_telepon ?? $pendaftaran->no_telepon_ayah ?? $pendaftaran->no_telepon_ibu;
            if ($nomor) {
                dispatch(function () use ($pendaftaran, $request, $nomor) {
                    try {
                        if ($request->aksi === 'diverifikasi') {
                            $pesan = "🎉 *Pendaftaran Diterima*\n\nAssalamu'alaikum Bapak/Ibu,\n\nSelamat! Pendaftaran siswa atas nama:\n*{$pendaftaran->nama_lengkap}*\n\n✅ Telah *DIVERIFIKASI* dan diterima sebagai siswa aktif.\n\nAkun siswa telah dibuat:\n📧 Email: {$pendaftaran->email}\n\nSilakan login ke sistem untuk melihat informasi lebih lanjut.\n\nTerima kasih. 🙏";
                        } else {
                            $pesan = "❌ *Pendaftaran Ditolak*\n\nAssalamu'alaikum Bapak/Ibu,\n\nMohon maaf, pendaftaran siswa atas nama:\n*{$pendaftaran->nama_lengkap}*\n\nBelum dapat kami terima karena beberapa hal.\n\nCatatan: " . ($request->catatan_admin ?? '-') . "\n\nTerima kasih atas pengertiannya. 🙏";
                        }
                        (new \App\Services\WhatsAppService())->sendMessage($nomor, $pesan);
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Gagal kirim WA Verifikasi: " . $e->getMessage());
                    }
                })->afterResponse();
            }
        }

        return back()->with('success', $request->aksi === 'diverifikasi' ? '✅ Pendaftaran diverifikasi! Akun siswa berhasil dibuat.' : '❌ Pendaftaran ditolak.');
    }
}
