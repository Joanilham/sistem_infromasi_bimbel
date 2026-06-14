<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CBT\CbtUjian;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class GuruUjianController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $user = $request->user();

        if (strtolower($user->level) !== 'guru') {
            return $this->errorResponse('Akses ditolak.', 403);
        }

        // Ambil ujian yang dibuat oleh guru ini
        $query = CbtUjian::with(['mapel'])->where('created_by', $user->id);

        if ($request->filled('search')) {
            $query->where('nama_ujian', 'like', '%' . $request->search . '%');
        }

        $ujians = $query->orderBy('created_at', 'desc')->paginate(20);

        return $this->successResponse([
            'data' => $ujians->items(),
            'current_page' => $ujians->currentPage(),
            'last_page' => $ujians->lastPage(),
            'total' => $ujians->total(),
        ], 'Daftar ujian berhasil dimuat');
    }
}
