<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Pengumuman;
use App\Traits\ApiResponse;

class PengumumanController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $pengumuman = Pengumuman::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $pengumuman->getCollection()->transform(function ($p) {
            if ($p->foto) {
                $p->foto = asset('storage/' . $p->foto);
            }
            return $p;
        });

        // Cache for 10 minutes, using pagination to save data
        return $this->successResponse($pengumuman, 'Data pengumuman berhasil diambil')->header('Cache-Control', 'public, max-age=600');
    }
}

