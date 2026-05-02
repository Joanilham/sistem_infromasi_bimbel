<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranSiswa;
use App\Models\PembayaranPendaftaran;

class NotifikasiController extends Controller
{
    public function index()
    {
        $kantorId = session('kantor_id');
        
        // Pendaftaran Menunggu Verifikasi
        $pendaftaranMenunggu = PendaftaranSiswa::with('kantor', 'paketBimbingan')
            ->where('status', 'menunggu')
            ->when($kantorId, fn($q) => $q->where('kantor_id', $kantorId))
            ->latest()
            ->get();
            
        // Pembayaran Menunggu Konfirmasi
        $pembayaranBelumDikonfirmasi = PembayaranPendaftaran::with('pendaftaranSiswa.kantor', 'pendaftaranSiswa.paketBimbingan')
            ->where('status', 'menunggu')
            ->whereHas('pendaftaranSiswa', fn($q) => $q
                ->when($kantorId, fn($q2) => $q2->where('kantor_id', $kantorId))
            )
            ->latest()
            ->get();

        return view('admin.notifikasi.index', compact('pendaftaranMenunggu', 'pembayaranBelumDikonfirmasi'));
    }
}
