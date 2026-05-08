<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\KelompokBelajar;
use App\Models\CbtMapel;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Tampilan kalender mingguan + data jadwal.
     */
    public function index(Request $request)
    {
        $filterGuru   = $request->input('guru_id');
        $filterRombel = $request->input('rombel_id');

        $jadwals = Jadwal::inContext()
            ->with(['guru', 'rombel', 'mataPelajaran'])
            ->when($filterGuru, fn($q) => $q->where('guru_id', $filterGuru))
            ->when($filterRombel, fn($q) => $q->where('rombel_id', $filterRombel))
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        $guruList   = User::where('level', 'guru')->where('is_active', true)->orderBy('name')->get();
        $rombelList = KelompokBelajar::inContext()->orderBy('nama_kelompok')->get();
        $mapelList  = CbtMapel::orderBy('nama')->get();

        return view('admin.jadwal.index', compact(
            'jadwals', 'guruList', 'rombelList', 'mapelList',
            'filterGuru', 'filterRombel'
        ));
    }

    /**
     * Simpan slot jadwal baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'guru_id'            => 'required|exists:users,id',
            'rombel_id'          => 'nullable|exists:kelompok_belajars,id',
            'mata_pelajaran_id'  => 'nullable|exists:cbt_mapels,id',
            'hari'               => 'required|in:' . implode(',', Jadwal::HARI_LIST),
            'jam_mulai'          => 'required|date_format:H:i',
            'jam_selesai'        => 'required|date_format:H:i|after:jam_mulai',
            'ruangan'            => 'nullable|string|max:100',
        ]);

        $validated['kantor_id']  = session('kantor_id');
        $validated['periode_id'] = session('periode_id');

        // Cek konflik (warning saja, tetap simpan)
        $konflik = $this->cekKonflik(
            $validated['guru_id'],
            $validated['hari'],
            $validated['jam_mulai'],
            $validated['jam_selesai'],
            $validated['periode_id']
        );

        Jadwal::create($validated);

        $msg = 'Jadwal berhasil ditambahkan.';
        if ($konflik->isNotEmpty()) {
            $msg .= ' ⚠️ Warning: Guru memiliki ' . $konflik->count() . ' jadwal lain di slot waktu yang bertabrakan.';
        }

        return redirect()->route('admin.jadwal.index')->with('success', $msg);
    }

    /**
     * Update slot jadwal.
     */
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::inContext()->findOrFail($id);

        $validated = $request->validate([
            'guru_id'            => 'required|exists:users,id',
            'rombel_id'          => 'nullable|exists:kelompok_belajars,id',
            'mata_pelajaran_id'  => 'nullable|exists:cbt_mapels,id',
            'hari'               => 'required|in:' . implode(',', Jadwal::HARI_LIST),
            'jam_mulai'          => 'required|date_format:H:i',
            'jam_selesai'        => 'required|date_format:H:i|after:jam_mulai',
            'ruangan'            => 'nullable|string|max:100',
        ]);

        // Cek konflik (exclude jadwal ini sendiri)
        $konflik = $this->cekKonflik(
            $validated['guru_id'],
            $validated['hari'],
            $validated['jam_mulai'],
            $validated['jam_selesai'],
            $jadwal->periode_id,
            $jadwal->id
        );

        $jadwal->update($validated);

        $msg = 'Jadwal berhasil diperbarui.';
        if ($konflik->isNotEmpty()) {
            $msg .= ' ⚠️ Warning: Guru memiliki ' . $konflik->count() . ' jadwal lain di slot waktu yang bertabrakan.';
        }

        return redirect()->route('admin.jadwal.index')->with('success', $msg);
    }

    /**
     * Hapus slot jadwal.
     */
    public function destroy($id)
    {
        $jadwal = Jadwal::inContext()->findOrFail($id);
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * JSON daftar konflik untuk periode aktif.
     */
    public function konflik(Request $request)
    {
        $jadwals = Jadwal::inContext()
            ->with(['guru', 'rombel', 'mataPelajaran'])
            ->orderBy('guru_id')
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        $konflikList = [];

        foreach ($jadwals as $idx => $jadwal) {
            $overlaps = Jadwal::where('guru_id', $jadwal->guru_id)
                ->where('hari', $jadwal->hari)
                ->where('periode_id', $jadwal->periode_id)
                ->where('id', '!=', $jadwal->id)
                ->where(function ($q) use ($jadwal) {
                    $q->where('jam_mulai', '<', $jadwal->jam_selesai)
                      ->where('jam_selesai', '>', $jadwal->jam_mulai);
                })
                ->with(['rombel', 'mataPelajaran'])
                ->get();

            if ($overlaps->isNotEmpty()) {
                $konflikList[] = [
                    'jadwal'   => $jadwal,
                    'bentrok'  => $overlaps,
                ];
            }
        }

        return response()->json($konflikList);
    }

    /**
     * Duplikasi jadwal ke periode lain.
     */
    public function duplikasi(Request $request)
    {
        $request->validate([
            'target_periode_id' => 'required|exists:periodes,id',
        ]);

        $targetPeriodeId = $request->target_periode_id;
        $currentPeriodeId = session('periode_id');
        $currentKantorId  = session('kantor_id');

        if ($targetPeriodeId == $currentPeriodeId) {
            return back()->with('error', 'Periode tujuan tidak boleh sama dengan periode saat ini.');
        }

        $jadwals = Jadwal::where('kantor_id', $currentKantorId)
            ->where('periode_id', $currentPeriodeId)
            ->get();

        if ($jadwals->isEmpty()) {
            return back()->with('error', 'Tidak ada jadwal di periode saat ini untuk diduplikasi.');
        }

        $count = 0;
        foreach ($jadwals as $jadwal) {
            // Cek apakah sudah ada duplikat
            $exists = Jadwal::where('kantor_id', $currentKantorId)
                ->where('periode_id', $targetPeriodeId)
                ->where('guru_id', $jadwal->guru_id)
                ->where('hari', $jadwal->hari)
                ->where('jam_mulai', $jadwal->jam_mulai)
                ->where('jam_selesai', $jadwal->jam_selesai)
                ->exists();

            if (!$exists) {
                Jadwal::create([
                    'guru_id'           => $jadwal->guru_id,
                    'rombel_id'         => $jadwal->rombel_id,
                    'mata_pelajaran_id' => $jadwal->mata_pelajaran_id,
                    'hari'              => $jadwal->hari,
                    'jam_mulai'         => $jadwal->jam_mulai,
                    'jam_selesai'       => $jadwal->jam_selesai,
                    'ruangan'           => $jadwal->ruangan,
                    'kantor_id'         => $currentKantorId,
                    'periode_id'        => $targetPeriodeId,
                ]);
                $count++;
            }
        }

        return back()->with('success', "Berhasil menduplikasi {$count} jadwal ke periode tujuan.");
    }

    /**
     * Deteksi konflik guru di slot yang sama.
     */
    private function cekKonflik($guruId, $hari, $jamMulai, $jamSelesai, $periodeId, $excludeId = null)
    {
        return Jadwal::where('guru_id', $guruId)
            ->where('hari', $hari)
            ->where('periode_id', $periodeId)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)
                  ->where('jam_selesai', '>', $jamMulai);
            })
            ->get();
    }
}
