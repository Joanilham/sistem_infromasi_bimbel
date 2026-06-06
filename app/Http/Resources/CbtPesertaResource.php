<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CbtPesertaResource extends JsonResource
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
            'cbt_ujian_id'  => $this->cbt_ujian_id,
            'status'        => $this->status,
            'skor'          => $this->skor,
            'attempt_ke'    => $this->attempt_ke,
            'waktu_mulai'   => $this->waktu_mulai ? $this->waktu_mulai->toIso8601String() : null,
            'waktu_selesai' => $this->waktu_selesai ? $this->waktu_selesai->toIso8601String() : null,
        ];
    }
}
