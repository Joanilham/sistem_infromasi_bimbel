<?php

namespace App\Http\Controllers\Pendaftaran;
use App\Models\MasterData\Master;

use App\Http\Controllers\Controller;

use App\Models\Pendaftaran\PendaftaranSiswa;
use App\Models\Keuangan\PembayaranPendaftaran;
use App\Models\Akademik\PaketBimbingan;
use App\Models\Akademik\KelompokBelajar;
use App\Models\MasterData\Kantor;
use App\Models\MasterData\Periode;
use App\Services\PendaftaranService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Pendaftaran\PendaftaranStep2Request;
use App\Http\Requests\Pendaftaran\PendaftaranStep3Request;
use App\Http\Requests\Pendaftaran\VerifikasiPendaftaranRequest;
use Illuminate\Support\Str;
use App\Traits\HandlesImageUpload;

class PendaftaranController extends Controller
{
    use HandlesImageUpload;

    public function __construct(protected PendaftaranService $pendaftaranService)
    {}
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

    public function step2Store(PendaftaranStep2Request $request)
    {
        if (!Session::has('daftar_email')) {
            return redirect()->route('daftar.step1');
        }

        $request->validated();

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
        
        $master          = \App\Models\MasterData\Master::first();
        $dpPersenMinimal = $master?->dp_persen_minimal ?? 10;
        $banks           = \App\Models\MasterData\Bank::where('is_active', true)->get();
        
        return view('pendaftaran.step3', compact('paket', 'pakets', 'dpPersenMinimal', 'banks'));
    }

    public function step3Store(PendaftaranStep3Request $request)
    {
        if (!Session::has('daftar_email') || !Session::has('daftar_data')) {
            return redirect()->route('daftar.step1');
        }

        $request->validated();

        $data      = Session::get('daftar_data');
        if ($request->filled('paket_bimbingan_id')) {
            $data['paket_bimbingan_id'] = $request->paket_bimbingan_id;
            Session::put('daftar_data', $data);
        }

        try {
            $pendaftaran = $this->pendaftaranService->createPendaftaran($request, [
                'data'      => Session::get('daftar_data'),
                'email'     => Session::get('daftar_email'),
                'password'  => Session::get('daftar_password'),
                'kantor_id' => Session::get('daftar_kantor_id'),
            ]);

            Session::forget(['daftar_email', 'daftar_password', 'daftar_data', 'daftar_kantor_id']);
            return redirect()->route('daftar.selesai')->with('kode_pendaftaran', $pendaftaran->id);

        } catch (\Exception $e) {
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
        $kelompoks = KelompokBelajar::withCount(['pesertaDidiks' => function ($query) {
            $query->aktif()->inContext();
        }])->get();
        return view('admin.pendaftaran.show', compact('pendaftaran', 'kelompoks'));
    }

    // ──────────────────────────────────────────────────────────
    // ADMIN: Verifikasi (Terima / Tolak)
    // ──────────────────────────────────────────────────────────

    public function adminVerifikasi(VerifikasiPendaftaranRequest $request, PendaftaranSiswa $pendaftaran)
    {
        $request->validated();

        // Pastikan tidak memproses yang sudah diverifikasi/ditolak
        if ($pendaftaran->status !== 'menunggu') {
            return back()->withErrors(['aksi' => 'Pendaftaran ini sudah diproses sebelumnya.']);
        }

        $pesan = $this->pendaftaranService->verifikasi($request, $pendaftaran);

        // Bersihkan cache dashboard
        try {
            \App\Services\CacheService::clearPesertaCache($pendaftaran->kantor_id, $pendaftaran->periode_id);
        } catch (\Throwable $e) {}

        return redirect()->route('admin.pendaftaran.index')->with('success', $pesan);
    }
}



