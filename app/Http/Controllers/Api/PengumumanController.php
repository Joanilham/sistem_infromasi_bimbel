<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Pengumuman;
use App\Traits\ApiResponse;
use App\Http\Resources\PengumumanResource;

class PengumumanController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $pengumuman = Pengumuman::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Cache for 10 minutes, using pagination to save data
        return $this->successResponse([
            'data'         => PengumumanResource::collection($pengumuman->items()),
            'current_page' => $pengumuman->currentPage(),
            'last_page'    => $pengumuman->lastPage(),
        ], 'Data pengumuman berhasil diambil')->header('Cache-Control', 'public, max-age=600');
    }
}

