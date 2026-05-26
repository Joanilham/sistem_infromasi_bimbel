<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PesertaDidikRequest;

use App\Models\PesertaDidik;
use App\Models\PaketBimbingan;
use App\Models\KelompokBelajar;
use App\Traits\ExportsExcel;

class PesertaDidikController extends Controller
{
  use ExportsExcel;

  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $search     = $request->input('search', '');
    $perPage    = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
    $paketId    = $request->input('paket_id');
    $kelompokId = $request->input('kelompok_id');
    $jk         = $request->input('jenis_kelamin');
    $sort       = in_array($request->input('sort'), ['id', 'nama_lengkap', 'paket_bimbingan_id', 'kelompok_belajar_id']) ? $request->input('sort') : 'id';
    $order      = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'desc';

    $pesertaDidiks = PesertaDidik::aktif()
        ->inContext()
        ->with('paketBimbingan', 'kelompokBelajar')
        ->when($search, function ($q) use ($search) {
            $q->where(function ($sq) use ($search) {
                $sq->where('nama_lengkap', 'like', "%{$search}%")
                   ->orWhere('nisn', 'like', "%{$search}%")
                   ->orWhere('asal_sekolah', 'like', "%{$search}%");
            });
        })
        ->when($paketId, fn($q) => $q->where('paket_bimbingan_id', $paketId))
        ->when($kelompokId, fn($q) => $q->where('kelompok_belajar_id', $kelompokId))
        ->when($jk, fn($q) => $q->where('jenis_kelamin', $jk))
        ->orderBy($sort, $order)
        ->paginate($perPage)
        ->withQueryString();

    $paketBimbingans = PaketBimbingan::get();
    $kelompokBelajars = KelompokBelajar::inContext()->orderBy('nama_kelompok')->get();

    return view('admin.peserta_didik.aktif', compact('pesertaDidiks', 'paketBimbingans', 'kelompokBelajars', 'search', 'perPage'));
  }

  /**
   * Daftar peserta didik yang sudah keluar.
   */
  public function keluar(Request $request)
  {
    $search     = $request->input('search', '');
    $perPage    = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
    $paketId    = $request->input('paket_id');
    $jk         = $request->input('jenis_kelamin');
    $sort       = in_array($request->input('sort'), ['id', 'nama_lengkap', 'tanggal_keluar']) ? $request->input('sort') : 'tanggal_keluar';
    $order      = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'desc';

    $pesertaDidiks = PesertaDidik::keluar()
        ->inContext()
        ->with('paketBimbingan')
        ->when($search, function ($q) use ($search) {
            $q->where(function ($sq) use ($search) {
                $sq->where('nama_lengkap', 'like', "%{$search}%")
                   ->orWhere('nisn', 'like', "%{$search}%")
                   ->orWhere('asal_sekolah', 'like', "%{$search}%");
            });
        })
        ->when($paketId, fn($q) => $q->where('paket_bimbingan_id', $paketId))
        ->when($jk, fn($q) => $q->where('jenis_kelamin', $jk))
        ->orderBy($sort, $order)
        ->paginate($perPage)
        ->withQueryString();

    $paketBimbingans = PaketBimbingan::get();

    return view('admin.peserta_didik.keluar', compact('pesertaDidiks', 'paketBimbingans', 'search', 'perPage'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $paketBimbingans  = PaketBimbingan::get();
    $kelompokBelajars = KelompokBelajar::inContext()->orderBy('nama_kelompok')->get();
    return view('admin.peserta_didik.create', compact('paketBimbingans', 'kelompokBelajars'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(PesertaDidikRequest $request)
  {
    $validated = $request->validated();

    try {
      $peserta = PesertaDidik::create($validated + [
          'status' => 'Aktif',
          'kantor_id' => session('kantor_id'),
          'periode_id' => session('periode_id')
      ]);

      // Kirim Notifikasi WA (Manual Add)
      $nomor = $peserta->no_telepon ?? $peserta->no_telepon_ayah ?? $peserta->no_telepon_ibu;
      if ($nomor) {
          $pesan = "📚 *Data Siswa Aktif*\n\nHalo *{$peserta->nama_lengkap}*,\n\nAdmin telah menambahkan data Anda ke dalam sistem Bimbingan Belajar. Anda sekarang telah terdaftar sebagai siswa aktif.\n\nSelamat belajar dan sukses selalu! 🙏";
          \App\Services\WhatsAppService::sendAsync($nomor, $pesan);
      }

      \App\Services\CacheService::clearPesertaCache();

      return redirect()->route('peserta-didik.index')->with('success', 'Data Peserta Didik berhasil ditambahkan!');
    } catch (\Exception $e) {
      \Illuminate\Support\Facades\Log::error('Student Store Error: ' . $e->getMessage());
      return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menambahkan data siswa.');
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $pesertaDidik     = PesertaDidik::findOrFail($id);
    $paketBimbingans  = PaketBimbingan::get();
    $kelompokBelajars = KelompokBelajar::inContext()->orderBy('nama_kelompok')->get();
    return view('admin.peserta_didik.edit', compact('pesertaDidik', 'paketBimbingans', 'kelompokBelajars'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(PesertaDidikRequest $request, string $id)
  {
    $pesertaDidik = PesertaDidik::findOrFail($id);

    $validated = $request->validated();

    // Hapus field keluar jika status bukan Keluar
    if ($validated['status'] !== 'Keluar') {
      $validated['tanggal_keluar'] = null;
      $validated['alasan_keluar']  = null;
    }

    try {
      $pesertaDidik->update($validated);
      \App\Services\CacheService::clearPesertaCache();
      return redirect()->route('peserta-didik.index')->with('success', 'Data Peserta Didik berhasil diperbarui!');
    } catch (\Exception $e) {
      \Illuminate\Support\Facades\Log::error('Student Update Error: ' . $e->getMessage());
      return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui data siswa.');
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $pesertaDidik = PesertaDidik::inContext()->findOrFail($id);
    $pesertaDidik->delete();

    \App\Services\CacheService::clearPesertaCache();

    return redirect()->back()->with('success', 'Data Peserta Didik berhasil dihapus!');
  }


  /**
   * Export keluar students to Excel (XML Spreadsheet 2003).
   */
  public function exportKeluar()
  {
    $rows = PesertaDidik::keluar()
      ->inContext()
      ->with('paketBimbingan', 'kelompokBelajar')
      ->orderBy('tanggal_keluar', 'desc')
      ->get();

    $filename = 'Peserta_Didik_Keluar_' . date('d-m-Y') . '.xls';
    $headers = ['No', 'Nama Lengkap', 'NISN', 'Jenis Kelamin', 'Asal Sekolah', 'Paket Bimbingan', 'Kelompok', 'No. Telepon', 'Tanggal Keluar', 'Nama Ayah', 'Nama Ibu', 'Keterangan Keluar'];
    $cols    = count($headers);

    $xml = $this->xmlOpen('Peserta Didik Keluar', $cols, '#991B1B', '#DC2626', '#FEF2F2');
    
    $widths = [30, 140, 80, 80, 160, 130, 80, 100, 100, 110, 110, 200];
    $xml .= '<Worksheet ss:Name="Peserta Didik Keluar"><Table ss:DefaultRowHeight="18">';
    foreach ($widths as $w) {
      $xml .= '<Column ss:Width="' . $w . '"/>' . "\n";
    }

    $xml .= $this->xmlTitleRow('DATA PESERTA DIDIK KELUAR — GENIUS EDUCATION', $cols);
    $xml .= $this->xmlInfoRow('Diekspor: ' . now()->format('d/m/Y H:i') . ' WIB  |  Jumlah: ' . $rows->count() . ' Peserta', $cols);
    $xml .= $this->xmlHeaderRow($headers);

    foreach ($rows as $i => $p) {
      $even = ($i % 2 === 0);
      $sd   = $even ? 's_data'  : 's_data2';
      $st   = $even ? 's_text'  : 's_text2';

      $xml .= '<Row ss:Height="18">';
      $xml .= $this->xmlNum($i + 1, $sd);
      $xml .= $this->xmlStr($p->nama_lengkap, $sd);
      $xml .= $this->xmlStr($p->nisn, $st);
      $xml .= $this->xmlStr($p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan', $sd);
      $xml .= $this->xmlStr($p->asal_sekolah, $sd);
      $xml .= $this->xmlStr(optional($p->paketBimbingan)->nama_paket ?? '', $sd);
      $xml .= $this->xmlStr(optional($p->kelompokBelajar)->nama_kelompok ?? '', $sd);
      $xml .= $this->xmlStr($p->no_telepon, $st);
      $xml .= $this->xmlStr($p->tanggal_keluar ?? '', $sd);
      $xml .= $this->xmlStr($p->nama_ayah ?? '', $sd);
      $xml .= $this->xmlStr($p->nama_ibu ?? '', $sd);
      $xml .= $this->xmlStr($p->alasan_keluar ?? '', $sd);
      $xml .= '</Row>' . "\n";
    }

    $xml .= '</Table></Worksheet></Workbook>';
    return $this->xlsResponse($xml, $filename);
  }

  /**
   * Export active students to Excel (XML Spreadsheet 2003).
   */
  public function export()
  {
    $rows = PesertaDidik::aktif()
      ->inContext()
      ->with('paketBimbingan', 'kelompokBelajar')
      ->orderBy('nama_lengkap')
      ->get();

    $filename = 'Peserta_Didik_Aktif_' . date('d-m-Y') . '.xls';
    $headers = ['No', 'Nama Lengkap', 'NISN', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Agama', 'Alamat', 'Asal Sekolah', 'No. Telepon', 'Paket Bimbingan', 'Kelompok', 'Nama Ayah', 'No. Telp Ayah', 'Nama Ibu', 'No. Telp Ibu'];
    $cols = count($headers);

    $xml = $this->xmlOpen('Peserta Didik Aktif', $cols, '#1E40AF', '#2563EB', '#EFF6FF');
    
    $widths = [30, 140, 80, 80, 100, 85, 70, 160, 130, 100, 110, 80, 100, 100, 100, 100];
    $xml .= '<Worksheet ss:Name="Peserta Didik Aktif"><Table ss:DefaultRowHeight="18">';
    foreach ($widths as $w) {
      $xml .= '<Column ss:Width="' . $w . '"/>' . "\n";
    }

    $xml .= $this->xmlTitleRow('DATA PESERTA DIDIK AKTIF — GENIUS EDUCATION', $cols);
    $xml .= $this->xmlInfoRow('Diekspor: ' . now()->format('d/m/Y H:i') . ' WIB  |  Jumlah: ' . $rows->count() . ' Peserta', $cols);
    $xml .= $this->xmlHeaderRow($headers);

    foreach ($rows as $i => $p) {
      $even = ($i % 2 === 0);
      $sd   = $even ? 's_data'  : 's_data2';
      $st   = $even ? 's_text'  : 's_text2';

      $xml .= '<Row ss:Height="18">';
      $xml .= $this->xmlNum($i + 1, $sd);
      $xml .= $this->xmlStr($p->nama_lengkap, $sd);
      $xml .= $this->xmlStr($p->nisn, $st);
      $xml .= $this->xmlStr($p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan', $sd);
      $xml .= $this->xmlStr($p->tempat_lahir ?? '', $sd);
      $xml .= $this->xmlStr($p->tanggal_lahir ?? '', $sd);
      $xml .= $this->xmlStr($p->agama ?? '', $sd);
      $xml .= $this->xmlStr($p->alamat_lengkap ?? '', $sd);
      $xml .= $this->xmlStr($p->asal_sekolah, $sd);
      $xml .= $this->xmlStr($p->no_telepon, $st);
      $xml .= $this->xmlStr(optional($p->paketBimbingan)->nama_paket ?? '', $sd);
      $xml .= $this->xmlStr(optional($p->kelompokBelajar)->nama_kelompok ?? '', $sd);
      $xml .= $this->xmlStr($p->nama_ayah ?? '', $sd);
      $xml .= $this->xmlStr($p->no_telepon_ayah ?? '', $st);
      $xml .= $this->xmlStr($p->nama_ibu ?? '', $sd);
      $xml .= $this->xmlStr($p->no_telepon_ibu ?? '', $st);
      $xml .= '</Row>' . "\n";
    }

    $xml .= '</Table></Worksheet></Workbook>';
    return $this->xlsResponse($xml, $filename);
  }
}
