<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbsensiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'tanggal'        => $this->tanggal ? $this->tanggal->format('Y-m-d') : null,
            'jam_masuk'      => $this->jam_masuk,
            'jam_pulang'     => $this->jam_pulang,
            'status_masuk'   => $this->status_masuk,
            'keterangan'     => $this->keterangan,
        ];
    }
}
