<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Traits\ExportsExcel;
use App\Http\Requests\Akademik\StoreGuruRequest;
use App\Http\Requests\Akademik\UpdateGuruRequest;

class GuruController extends Controller
{
    use ExportsExcel;

    /**
     * Display a listing of active guru.
     */
    public function index(Request $request)
    {
        $search     = $request->input('search', '');
        $perPage    = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $mapel      = $request->input('matapelajaran');
        $sort       = in_array($request->input('sort'), ['id', 'name', 'matapelajaran']) ? $request->input('sort') : 'id';
        $order      = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'desc';

        $gurus = $this->buildIndexQuery($search, $mapel, $sort, $order, $perPage);

        $daftarMapel = User::where('level', 'Guru')->inContext()->whereNotNull('matapelajaran')->distinct()->pluck('matapelajaran');

        return view('admin.guru.aktif', compact('gurus', 'daftarMapel', 'search', 'perPage'));
    }

    /**
     * Display a listing of guru who have left.
     */
    public function keluar(Request $request)
    {
        $search     = $request->input('search', '');
        $perPage    = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $mapel      = $request->input('matapelajaran');
        $sort       = in_array($request->input('sort'), ['id', 'name', 'tanggal_keluar']) ? $request->input('sort') : 'tanggal_keluar';
        $order      = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'desc';

        $gurus = $this->buildKeluarQuery($search, $mapel, $sort, $order, $perPage);

        $daftarMapel = User::where('level', 'guru')->inContext()->whereNotNull('matapelajaran')->distinct()->pluck('matapelajaran');

        return view('admin.guru.keluar', compact('gurus', 'daftarMapel', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.guru.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGuruRequest $request)
    {
        $validated = $request->validated();

        $validated['password']  = Hash::make($validated['password']);
        $validated['level']     = 'Guru';
        $validated['status']    = 'Aktif';
        $validated['is_active'] = true;
        $validated['username']  = $request->email;

        try {
            unset($validated['password_confirmation']);
            User::create($validated + [
                'kantor_id' => session('kantor_id'),
                'periode_id' => session('periode_id'),
            ]);

            \App\Services\CacheService::clearGuruCache();

            return redirect()->route('manajemen-guru.index')->with('success', 'Guru berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Guru Store Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menambahkan data guru.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $guru = User::where('level', 'Guru')->findOrFail($id);
        return view('admin.guru.edit', compact('guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGuruRequest $request, string $id)
    {
        $guru = User::where('level', 'Guru')->inContext()->findOrFail($id);

        $validated = $request->validated();

        // Hapus field keluar jika status bukan Keluar
        if ($validated['status'] !== 'Keluar') {
            $validated['tanggal_keluar'] = null;
            $validated['alasan_keluar']  = null;
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        try {
            $validated['username'] = $validated['email'];
            $validated['is_active'] = ($validated['status'] === 'Aktif');
            unset($validated['password_confirmation']);
            $guru->update($validated);

            // Debug: log after update
            \Illuminate\Support\Facades\Log::info('Guru updated successfully:', ['id' => $guru->id, 'status' => $guru->fresh()->status]);

            \App\Services\CacheService::clearGuruCache();

            return redirect()->route('manajemen-guru.index')->with('success', 'Data guru berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Guru Update Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui data guru.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $guru = User::where('level', 'Guru')->inContext()->findOrFail($id);
        $guru->delete();

        \App\Services\CacheService::clearGuruCache();

        return redirect()->back()->with('success', 'Guru berhasil dihapus.');
    }

    /**
     * Export active guru to Excel (XML Spreadsheet 2003).
     */
    public function export()
    {
        $gurus = User::where('level', 'Guru')
            ->where('status', 'Aktif')
            ->inContext()
            ->latest()
            ->get();

        $filename = 'Guru_Aktif_' . date('d-m-Y') . '.xls';
        $headers = ['No', 'Nama Lengkap', 'L/P', 'Alamat', 'NIP', 'Mata Pelajaran', 'No. Telp', 'Email'];
        $cols    = count($headers);

        $xml = $this->xmlOpen('Guru Aktif', $cols, '#059669', '#059669', '#ECFDF5');

        $widths = [30, 150, 50, 180, 80, 150, 80, 180];
        $xml .= '<Worksheet ss:Name="Guru Aktif"><Table ss:DefaultRowHeight="18">';
        foreach ($widths as $w) {
            $xml .= '<Column ss:Width="' . $w . '"/>' . "\n";
        }

        $xml .= $this->xmlTitleRow('DATA GURU AKTIF — GENIUS EDUCATION', $cols);
        $xml .= $this->xmlInfoRow('Tanggal Export: ' . date('d-m-Y H:i:s'), $cols);
        $xml .= $this->xmlHeaderRow($headers);

        $xml .= $this->generateXmlRowsAktif($gurus);

        $xml .= '</Table></Worksheet></Workbook>';
        return $this->xlsResponse($xml, $filename);
    }

    /**
     * Export guru who have left to Excel (XML Spreadsheet 2003).
     */
    public function exportKeluar()
    {
        $gurus = User::where('level', 'Guru')
            ->where('status', 'Keluar')
            ->inContext()
            ->orderBy('tanggal_keluar', 'desc')
            ->get();

        $filename = 'Guru_Keluar_' . date('d-m-Y') . '.xls';
        $headers = ['No', 'Nama Lengkap', 'L/P', 'Alamat', 'NIP', 'Mata Pelajaran', 'No. Telp', 'Email', 'Tanggal Keluar', 'Alasan Keluar'];
        $cols    = count($headers);

        $xml = $this->xmlOpen('Guru Keluar', $cols, '#991B1B', '#DC2626', '#FEF2F2');

        $widths = [30, 140, 50, 150, 80, 130, 80, 150, 100, 180];
        $xml .= '<Worksheet ss:Name="Guru Keluar"><Table ss:DefaultRowHeight="18">';
        foreach ($widths as $w) {
            $xml .= '<Column ss:Width="' . $w . '"/>' . "\n";
        }

        $xml .= $this->xmlTitleRow('DATA GURU KELUAR — GENIUS EDUCATION', $cols);
        $xml .= $this->xmlInfoRow('Tanggal Export: ' . date('d-m-Y H:i:s'), $cols);
        $xml .= $this->xmlHeaderRow($headers);

        $xml .= $this->generateXmlRowsKeluar($gurus);

        $xml .= '</Table></Worksheet></Workbook>';
        return $this->xlsResponse($xml, $filename);
    }

    // ─── PRIVATE HELPERS ──────────────────────────────────────────────

    private function buildIndexQuery($search, $mapel, $sort, $order, $perPage)
    {
        $jenisKelamin = request('jenis_kelamin');
        
        return User::where('level', 'Guru')
            ->where('status', 'Aktif')
            ->inContext()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                       ->orWhere('nip', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($mapel, fn($q) => $q->where('matapelajaran', $mapel))
            ->when($jenisKelamin, fn($q) => $q->where('jenis_kelamin', $jenisKelamin))
            ->orderBy($sort, $order)
            ->paginate($perPage)
            ->withQueryString();
    }

    private function buildKeluarQuery($search, $mapel, $sort, $order, $perPage)
    {
        $jenisKelamin = request('jenis_kelamin');
        
        return User::where('level', 'Guru')
            ->where('status', 'Keluar')
            ->inContext()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                       ->orWhere('nip', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($mapel, fn($q) => $q->where('matapelajaran', $mapel))
            ->when($jenisKelamin, fn($q) => $q->where('jenis_kelamin', $jenisKelamin))
            ->orderBy($sort, $order)
            ->paginate($perPage)
            ->withQueryString();
    }

    private function generateXmlRowsAktif($gurus)
    {
        $xml = '';
        foreach ($gurus as $index => $guru) {
            $style = $index % 2 === 0 ? 's_data' : 's_data2';
            $textStyle = $index % 2 === 0 ? 's_text' : 's_text2';

            $xml .= '<Row ss:Height="18">';
            $xml .= $this->xmlNum($index + 1, $style);
            $xml .= $this->xmlStr($guru->name, $textStyle);
            $xml .= $this->xmlStr($guru->jenis_kelamin == 'Laki-Laki' ? 'L' : ($guru->jenis_kelamin == 'Perempuan' ? 'P' : '-'), $textStyle);
            $xml .= $this->xmlStr($guru->alamat, $textStyle);
            $xml .= $this->xmlStr($guru->nip, $textStyle);
            $xml .= $this->xmlStr($guru->matapelajaran, $textStyle);
            $xml .= $this->xmlStr($guru->no_telp, $textStyle);
            $xml .= $this->xmlStr($guru->email, $textStyle);
            $xml .= '</Row>' . "\n";
        }
        return $xml;
    }

    private function generateXmlRowsKeluar($gurus)
    {
        $xml = '';
        foreach ($gurus as $index => $guru) {
            $style = $index % 2 === 0 ? 's_data' : 's_data2';
            $textStyle = $index % 2 === 0 ? 's_text' : 's_text2';

            $xml .= '<Row ss:Height="18">';
            $xml .= $this->xmlNum($index + 1, $style);
            $xml .= $this->xmlStr($guru->name, $textStyle);
            $xml .= $this->xmlStr($guru->jenis_kelamin == 'Laki-Laki' ? 'L' : ($guru->jenis_kelamin == 'Perempuan' ? 'P' : '-'), $textStyle);
            $xml .= $this->xmlStr($guru->alamat, $textStyle);
            $xml .= $this->xmlStr($guru->nip, $textStyle);
            $xml .= $this->xmlStr($guru->matapelajaran, $textStyle);
            $xml .= $this->xmlStr($guru->no_telp, $textStyle);
            $xml .= $this->xmlStr($guru->email, $textStyle);
            $xml .= $this->xmlStr($guru->tanggal_keluar, $textStyle);
            $xml .= $this->xmlStr($guru->alasan_keluar, $textStyle);
            $xml .= '</Row>' . "\n";
        }
        return $xml;
    }
}

