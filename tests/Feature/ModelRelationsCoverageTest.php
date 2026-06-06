<?php

use App\Models\KelompokBelajar;
use App\Models\PaketBimbingan;
use App\Models\Absensi;
use App\Models\JadwalMapel;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\CbtBab;
use App\Models\CbtMapel;
use App\Models\CbtOpsiJawaban;
use App\Models\CbtPembahasan;
use App\Models\CbtUjianAssign;
use Illuminate\Database\Eloquent\Relations\Relation;

test('models relationships return valid relation objects', function () {
    // 1. KelompokBelajar
    $kb = new KelompokBelajar();
    expect($kb->pesertaDidiks())->toBeInstanceOf(Relation::class);
    expect($kb->kantor())->toBeInstanceOf(Relation::class);
    expect($kb->periode())->toBeInstanceOf(Relation::class);

    // 2. PaketBimbingan
    $pb = new PaketBimbingan();
    expect($pb->kantor())->toBeInstanceOf(Relation::class);
    expect($pb->periode())->toBeInstanceOf(Relation::class);

    // 3. Absensi
    $abs = new Absensi();
    expect($abs->pesertaDidik())->toBeInstanceOf(Relation::class);

    // 4. JadwalMapel
    $jm = new JadwalMapel();
    expect($jm->guru())->toBeInstanceOf(Relation::class);

    // 5. Pemasukan
    $pem = new Pemasukan();
    expect($pem->kategori())->toBeInstanceOf(Relation::class);
    expect($pem->user())->toBeInstanceOf(Relation::class);

    // 6. Pengeluaran
    $peng = new Pengeluaran();
    expect($peng->kategori())->toBeInstanceOf(Relation::class);
    expect($peng->user())->toBeInstanceOf(Relation::class);

    // 7. CbtBab
    $cb = new CbtBab();
    expect($cb->mapel())->toBeInstanceOf(Relation::class);
    expect($cb->bankSoals())->toBeInstanceOf(Relation::class);

    // 8. CbtMapel
    $cm = new CbtMapel();
    expect($cm->babs())->toBeInstanceOf(Relation::class);
    expect($cm->bankSoals())->toBeInstanceOf(Relation::class);

    // 9. CbtOpsiJawaban
    $co = new CbtOpsiJawaban();
    expect($co->bankSoal())->toBeInstanceOf(Relation::class);

    // 10. CbtPembahasan
    $cp = new CbtPembahasan();
    expect($cp->bankSoal())->toBeInstanceOf(Relation::class);

    // 11. CbtUjianAssign
    $cua = new CbtUjianAssign();
    expect($cua->ujian())->toBeInstanceOf(Relation::class);
});
