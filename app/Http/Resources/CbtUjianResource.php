<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CbtUjianResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'judul'         => $this->judul,
            'waktu_mulai'   => $this->waktu_mulai ? $this->waktu_mulai->toIso8601String() : null,
            'waktu_selesai' => $this->waktu_selesai ? $this->waktu_selesai->toIso8601String() : null,
            'durasi'        => $this->durasi,
            'is_aktif'      => $this->is_aktif,
            'status_peserta'=> $this->status_peserta ?? null,
            'skor_peserta'  => $this->skor_peserta ?? null,
            'attempt_ke'    => $this->attempt_ke ?? 0,
            'mode'          => $this->mode,
            'token'         => $this->token,
            'tampilkan_hasil'=> $this->tampilkan_hasil,
        ];
    }
}
