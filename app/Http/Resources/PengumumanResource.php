<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengumumanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'judul'      => $this->judul,
            'isi'        => $this->isi,
            'foto'       => $this->foto ? asset('storage/' . $this->foto) : null,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
