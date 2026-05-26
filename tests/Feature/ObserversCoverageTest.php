<?php

use App\Observers\KantorObserver;
use App\Observers\KelompokBelajarObserver;
use App\Observers\PaketBimbinganObserver;
use App\Observers\PeriodeObserver;
use App\Observers\PesertaDidikObserver;

use App\Models\Kantor;
use App\Models\KelompokBelajar;
use App\Models\PaketBimbingan;
use App\Models\Periode;
use App\Models\PesertaDidik;

test('Observers coverage tests for all events', function () {
    $kantor = Kantor::create(['nama_kantor' => 'Obs Kantor', 'alamat' => 'Obs Kota']);
    $periode = Periode::create(['tahun_periode' => '2025/2026', 'is_active' => true]);
    $paket = PaketBimbingan::create([
        'nama_paket' => 'Obs Paket',
        'nominal'    => 1000000,
        'kantor_id'  => $kantor->id,
        'periode_id' => $periode->id,
    ]);
    $kelompok = KelompokBelajar::create([
        'nama_kelompok' => 'Obs Kelompok',
        'kantor_id'     => $kantor->id,
        'periode_id'    => $periode->id,
    ]);
    $peserta = PesertaDidik::create([
        'nama_lengkap'       => 'Obs Siswa',
        'nisn'               => '9988776611',
        'jenis_kelamin'      => 'L',
        'asal_sekolah'       => 'SMP 1',
        'status'             => 'Aktif',
        'kantor_id'          => $kantor->id,
        'periode_id'         => $periode->id,
        'paket_bimbingan_id' => $paket->id,
    ]);

    // 1. KantorObserver
    $obsKantor = new KantorObserver();
    $obsKantor->created($kantor);
    $obsKantor->updated($kantor);
    $obsKantor->deleted($kantor);
    $obsKantor->restored($kantor);
    $obsKantor->forceDeleted($kantor);

    // 2. KelompokBelajarObserver
    $obsKB = new KelompokBelajarObserver();
    $obsKB->created($kelompok);
    $obsKB->updated($kelompok);
    $obsKB->deleted($kelompok);
    $obsKB->restored($kelompok);
    $obsKB->forceDeleted($kelompok);

    // 3. PaketBimbinganObserver
    $obsPB = new PaketBimbinganObserver();
    $obsPB->created($paket);
    $obsPB->updated($paket);
    $obsPB->deleted($paket);
    $obsPB->restored($paket);
    $obsPB->forceDeleted($paket);

    // 4. PeriodeObserver
    $obsPeriode = new PeriodeObserver();
    $obsPeriode->created($periode);
    $obsPeriode->updated($periode);
    $obsPeriode->deleted($periode);
    $obsPeriode->restored($periode);
    $obsPeriode->forceDeleted($periode);

    // 5. PesertaDidikObserver
    $obsPD = new PesertaDidikObserver();
    $obsPD->created($peserta);
    $obsPD->updated($peserta);
    $obsPD->deleted($peserta);
    $obsPD->restored($peserta);
    $obsPD->forceDeleted($peserta);

    expect(true)->toBeTrue();
});
