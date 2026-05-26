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
    // STEP 1: Buat Akun + Pilih Kantorf

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
            'password.confirmed'=> 'Konfirmasi password tidak cocok. Pastikan Anda mengetik ulang sandi dengan benar.',
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
        
        // Hapus filter kantor. Tarik SEMUA data secara paksa menggunakan withoutGlobalScopes()
        $pakets    = PaketBimbingan::get();
        
        return view('pendaftaran.step2', compact('pakets'));
    }

    public function step2Store(Request $request)
    {
        if (!Session::has('daftar_email')) {
            return redirect()->route('daftar.step1');
        }

        $request->validate([
            'nama_lengkap'       => 'required|string|max:255',
            'jenis_kelamin'      => 'required|in:L,P',
            'no_telepon'         => ['required', 'regex:/^\+?[0-9]{8,15}$/'],
            'alamat_lengkap'     => 'required|string',
            'asal_sekolah'       => 'required|string|max:255',
            'nama_ayah'          => 'required_without:nama_ibu|nullable|string',
            'no_telepon_ayah'    => ['required_without:no_telepon_ibu', 'nullable', 'regex:/^\+?[0-9]{8,15}$/'],
            'nama_ibu'           => 'required_without:nama_ayah|nullable|string',
            'no_telepon_ibu'     => ['required_without:no_telepon_ayah', 'nullable', 'regex:/^\+?[0-9]{8,15}$/'],
        ], [
            'no_telepon.required' => 'Nomor telepon/WhatsApp wajib diisi agar bimbel dapat menghubungi Anda.',
            'alamat_lengkap.required' => 'Alamat lengkap wajib diisi.',
            'nama_ayah.required_without' => 'Anda wajib mengisi Nama Ayah ATAU Nama Ibu.',
            'no_telepon_ayah.required_without' => 'Anda wajib mengisi Nomor Telepon Ayah ATAU Ibu untuk keperluan komunikasi dengan wali.',
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
        
        $data            = Session::get('daftar_data');
        $paketId         = $data['paket_bimbingan_id'] ?? null;
        
        // Gunakan withoutGlobalScopes() juga di sini
        $paket           = $paketId ? PaketBimbingan::find($paketId) : null;
        $pakets          = PaketBimbingan::get();
        
        $master          = \App\Models\Master::first();
        $dpPersenMinimal = $master?->dp_persen_minimal ?? 10;
        $banks           = \App\Models\Bank::where('is_active', true)->get();
        
        return view('pendaftaran.step3', compact('paket', 'pakets', 'dpPersenMinimal', 'banks'));
    }

    public function step3Store(Request $request)
    {
        if (!Session::has('daftar_email') || !Session::has('daftar_data')) {
            return redirect()->route('daftar.step1');
        }

        $request->validate([
            'metode_pembayaran'   => 'required|string',
            'jenis_bayar'         => 'required|in:full,dp',
            'bukti_pembayaran'    => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'paket_bimbingan_id'  => 'required|exists:paket_bimbingans,id',
            'syarat_ketentuan'    => 'accepted', // WAJIB CENTANG S&K
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti_pembayaran.mimes'    => 'Format bukti pembayaran hanya diperbolehkan: JPG, JPEG, PNG, PDF.',
            'bukti_pembayaran.max'      => 'Ukuran file maksimal adalah 5MB.',
            'bukti_pembayaran.uploaded' => 'File gagal diunggah. Pastikan ukuran file tidak melebihi batas sistem (kemungkinan maksimal 2MB) dan formatnya benar.',
            'paket_bimbingan_id.required'=> 'Paket bimbingan belajar wajib dipilih.',
            'jenis_bayar.required'      => 'Pilihan jenis pembayaran (DP / Penuh) wajib dipilih.',
            'jenis_bayar.in'            => 'Jenis pembayaran tidak valid.',
            'syarat_ketentuan.accepted' => 'Anda wajib mencentang dan menyetujui Syarat & Ketentuan pendaftaran.',
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

            // LANGSUNG BUAT AKUN USER AGAR SISWA BISA LOGIN KE DASHBOARD
            \App\Models\User::create([
                'name'       => $pendaftaran->nama_lengkap,
                'email'      => $pendaftaran->email,
                'username'   => $pendaftaran->email,
                'password'   => $password, // Menggunakan sandi mentah karena Model User sudah otomatis melakukan hash
                'level'      => 'siswa',
                'is_active'  => true,
                'status'     => 'menunggu', // Kunci agar tertahan di Dashboard kuning
                'kantor_id'  => $kantor?->id,
                'periode_id' => $periode?->id,
            ]);

            // Upload bukti pembayaran (Kompresi jika gambar)
            $file = $request->file('bukti_pembayaran');
            if (in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'webp'])) {
                $path = $this->compressAndStore($file, 'bukti_pembayaran');
            } else {
                $path = $file->store('bukti_pembayaran', 'public');
            }

            // ── Hitung jumlah pembayaran berdasarkan jenis_bayar ──
            $jenisBayar    = $request->jenis_bayar; // 'full' atau 'dp'
            $jumlahDibayar = null;
            $nominalPaket  = null;

            if (!empty($data['paket_bimbingan_id'])) {
                $paketTerpilih = \App\Models\PaketBimbingan::find($data['paket_bimbingan_id']);
                $nominalPaket  = $paketTerpilih?->nominal ?? 0;

                if ($jenisBayar === 'dp') {
                    // Hitung DP berdasarkan persentase minimal dari paket_bimbingan
                    $dpPersenMinimal = $paketTerpilih?->dp_persen_minimal ?? 10;
                    $jumlahDibayar   = (int) ceil($nominalPaket * ($dpPersenMinimal / 100));
                } else {
                    // Bayar penuh
                    $jumlahDibayar = $nominalPaket;
                }
            }

            PembayaranPendaftaran::create([
                'pendaftaran_siswa_id' => $pendaftaran->id,
                'metode_pembayaran'    => $request->metode_pembayaran,
                'jumlah'               => $jumlahDibayar,
                'jenis_bayar'          => $jenisBayar,
                'bukti_pembayaran'     => $path,
                'status'               => 'menunggu',
            ]);

            \Illuminate\Support\Facades\DB::commit();

            // Kirim Notifikasi WA Pendaftaran Baru
            $nomor = $pendaftaran->no_telepon ?? $pendaftaran->no_telepon_ayah ?? $pendaftaran->no_telepon_ibu;
            if ($nomor) {
                $pesan = "📚 *Pendaftaran Berhasil*\n\nHalo *{$pendaftaran->nama_lengkap}*,\n\nTerima kasih telah mendaftar di bimbingan belajar kami! Data pendaftaran Anda telah kami terima dengan status: *MENUNGGU VERIFIKASI*.\n\nSilakan login ke sistem untuk memantau status Anda.\n\nTerima kasih. 🙏";
                \App\Services\WhatsAppService::sendAsync($nomor, $pesan);
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
        $kode = \Illuminate\Support\Facades\Session::get('kode_pendaftaran');
        $pendaftaran = $kode ? PendaftaranSiswa::with(['pembayaran', 'paketBimbingan'])->find($kode) : null;
        
        return view('pendaftaran.selesai', compact('pendaftaran'));
    }

    // ──────────────────────────────────────────────────────────
    // VERIFIKASI EMAIL (link yang dikirim ke email siswa) - BISA DIABAIKAN SEKARANG
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

        $pakets = PaketBimbingan::get();

        return view('admin.pendaftaran.index', compact('pendaftarans', 'search', 'perPage', 'pakets'));
    }

    public function adminShow(PendaftaranSiswa $pendaftaran)
    {
        $pendaftaran->load(['paketBimbingan', 'kelompokBelajar', 'pembayaran', 'kantor']);
        $kelompoks = KelompokBelajar::withCount('pesertaDidiks')->get();
        return view('admin.pendaftaran.show', compact('pendaftaran', 'kelompoks'));
    }

    // ──────────────────────────────────────────────────────────
    // ADMIN: Verifikasi (Terima / Tolak)
    // ──────────────────────────────────────────────────────────

    public function adminVerifikasi(Request $request, PendaftaranSiswa $pendaftaran)
    {
        $request->validate([
            'aksi'                   => 'required|in:diverifikasi,ditolak',
            'catatan_admin'          => 'nullable|string|max:1000',
            'kelompok_belajar_id'    => 'nullable|exists:kelompok_belajars,id',
            'nominal_paket'          => 'nullable|numeric|min:0',
            'jumlah_cicilan'         => 'nullable|integer|min:1',
            'jatuh_tempo_berikutnya' => 'nullable|date',
            'batas_waktu'            => 'nullable|date',
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
                'nisn'                => $pendaftaran->nisn ?: null,
                'jenis_kelamin'       => $pendaftaran->jenis_kelamin,
                'tempat_lahir'        => $pendaftaran->tempat_lahir,
                'tanggal_lahir'       => $pendaftaran->tanggal_lahir,
                'agama'               => $pendaftaran->agama,
                'alamat_lengkap'      => $pendaftaran->alamat_lengkap,
                'no_telepon'          => $pendaftaran->no_telepon,
                'asal_sekolah'        => $pendaftaran->asal_sekolah,
                'paket_bimbingan_id'  => $pendaftaran->paket_bimbingan_id,
                'kelompok_belajar_id' => $request->kelompok_belajar_id,
                'informasi_dari'      => $pendaftaran->informasi_dari,
                'nama_ayah'           => $pendaftaran->nama_ayah,
                'pekerjaan_ayah'      => $pendaftaran->pekerjaan_ayah,
                'no_telepon_ayah'     => $pendaftaran->no_telepon_ayah,
                'nama_ibu'            => $pendaftaran->nama_ibu,
                'pekerjaan_ibu'       => $pendaftaran->pekerjaan_ibu,
                'no_telepon_ibu'      => $pendaftaran->no_telepon_ibu,
                'status'              => 'Aktif',
            ]);

            // Aktifkan akun User siswa yang sudah dibuat saat pendaftaran
            $userSiswa = \App\Models\User::where('email', $pendaftaran->email)->first();
            if ($userSiswa) {
                $userSiswa->update([
                    'status'           => 'aktif', // Buka gembok dashboard
                    'peserta_didik_id' => $peserta->id,
                ]);
            }

            // Konfirmasi pembayaran pendaftaran
            if ($pendaftaran->pembayaran) {
                $pendaftaran->pembayaran->update(['status' => 'dikonfirmasi']);
            }

            // ── Catat Transaksi DP Otomatis (jika pendaftaran menggunakan DP) ──
            $pembayaranSiswa = \App\Models\PembayaranSiswa::where('peserta_didik_id', $peserta->id)->first();

            if ($pembayaranSiswa && $pendaftaran->pembayaran && $pendaftaran->pembayaran->jenis_bayar === 'dp' && $pendaftaran->pembayaran->jumlah > 0) {
                $noKwitansiDp = \App\Models\TransaksiPembayaran::generateNoKwitansi('DP');
                \App\Models\TransaksiPembayaran::create([
                    'pembayaran_siswa_id' => $pembayaranSiswa->id,
                    'nominal'             => $pendaftaran->pembayaran->jumlah,
                    'tanggal'             => now(),
                    'tipe_pembayaran'     => $pendaftaran->pembayaran->metode_pembayaran === 'Tunai' ? 'TUNAI' : 'TRANSFER',
                    'no_kwitansi'         => $noKwitansiDp,
                    'penerima'            => auth()->user()->name,
                    'user_id'             => auth()->id(),
                    'status'              => 'SUKSES', // DP yang diverifikasi langsung sukses
                    'catatan_siswa'       => 'DP Pendaftaran (dibayar saat daftar)',
                ]);
            }
            // Update batas waktu jatuh tempo atau cicilan jika admin menentukannya
            if ($pembayaranSiswa) {
                $nominalPaket = $request->filled('nominal_paket') ? $request->nominal_paket : ($pendaftaran->paketBimbingan?->nominal ?? 0);
                
                $dpDibayar = 0;
                if ($pendaftaran->pembayaran && $pendaftaran->pembayaran->status === 'dikonfirmasi') {
                    $dpDibayar = $pendaftaran->pembayaran->jumlah;
                }

                $sisaTagihan = max(0, $nominalPaket - $dpDibayar);
                $jumlahCicilan = $request->jumlah_cicilan ?: null;
                $nominalPerCicilan = null;
                
                if ($jumlahCicilan && $jumlahCicilan > 0) {
                    $nominalPerCicilan = (int) ceil($sisaTagihan / $jumlahCicilan);
                }

                $updateData = [
                    'total_harus_dibayar' => $nominalPaket,
                    'jumlah_cicilan' => $jumlahCicilan,
                    'nominal_per_cicilan' => $nominalPerCicilan,
                ];

                if ($request->filled('jatuh_tempo_berikutnya')) {
                    $updateData['jatuh_tempo_berikutnya'] = $request->jatuh_tempo_berikutnya;
                    $updateData['batas_waktu'] = $request->jatuh_tempo_berikutnya; // Sync batas_waktu juga
                } elseif ($request->filled('batas_waktu')) {
                    $updateData['batas_waktu'] = $request->batas_waktu;
                }

                $pembayaranSiswa->update($updateData);
            }
        }

        if ($request->aksi === 'ditolak') {
            if ($pendaftaran->pembayaran) {
                $pendaftaran->pembayaran->delete();
            }
            
            // Hapus juga akun usernya agar email bisa dipakai daftar lagi
            $userSiswa = \App\Models\User::where('email', $pendaftaran->email)->first();
            if ($userSiswa) {
                $userSiswa->delete();
            }
            
            $pendaftaran->delete();
        } else {
            $pendaftaran->update([
                'status'              => $request->aksi,
                'catatan_admin'       => $request->catatan_admin,
                'kelompok_belajar_id' => $request->aksi === 'diverifikasi' ? $request->kelompok_belajar_id : $pendaftaran->kelompok_belajar_id,
                'email_verified_at'   => $request->aksi === 'diverifikasi' ? now() : $pendaftaran->email_verified_at,
            ]);
        }

        // Bersihkan cache dashboard jika ada
        try {
            \App\Services\CacheService::clearPesertaCache($pendaftaran->kantor_id, $pendaftaran->periode_id);
        } catch (\Throwable $e) {}

        // Kirim Notifikasi WA setelah commit
        if ($request->aksi === 'diverifikasi' || $request->aksi === 'ditolak') {
            $nomor = $pendaftaran->no_telepon ?? $pendaftaran->no_telepon_ayah ?? $pendaftaran->no_telepon_ibu;
            if ($nomor) {
                if ($request->aksi === 'diverifikasi') {
                    $pesan = "🎉 *Pendaftaran Diterima*\n\nAssalamu'alaikum Bapak/Ibu,\n\nSelamat! Pendaftaran siswa atas nama:\n*{$pendaftaran->nama_lengkap}*\n\n✅ Telah *DIVERIFIKASI* dan diterima sebagai siswa aktif.\n\nAkun siswa telah dibuat:\n📧 Email: {$pendaftaran->email}\n\nSilakan login ke sistem untuk melihat informasi lebih lanjut.\n\nTerima kasih. 🙏";
                } else {
                    $pesan = "❌ *Pendaftaran Ditolak*\n\nAssalamu'alaikum Bapak/Ibu,\n\nMohon maaf, pendaftaran siswa atas nama:\n*{$pendaftaran->nama_lengkap}*\n\nBelum dapat kami terima karena beberapa hal.\n\nCatatan: " . ($request->catatan_admin ?? '-') . "\n\nTerima kasih atas pengertiannya. 🙏";
                }
                \App\Services\WhatsAppService::sendAsync($nomor, $pesan);
            }
        }

        // Alihkan admin kembali ke tabel pendaftaran, BUKAN return back()
        $pesanSukses = $request->aksi === 'diverifikasi' 
            ? '✅ Pendaftaran diverifikasi! Akun siswa berhasil dibuat.' 
            : '❌ Pendaftaran ditolak dan data telah dihapus.';
            
        return redirect()->route('admin.pendaftaran.index')->with('success', $pesanSukses);
    }
}