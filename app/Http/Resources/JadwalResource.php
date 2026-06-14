<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JadwalResource extends JsonResource
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
            'hari'          => $this->hari,
            'jam_mulai'     => substr($this->jam_mulai, 0, 5), // Format to HH:MM
            'jam_selesai'   => substr($this->jam_selesai, 0, 5),
            'ruangan'       => $this->ruangan,
            'mata_pelajaran'=> $this->whenLoaded('mataPelajaran', function() {
                return ['nama' => $this->mataPelajaran->nama_mapel ?? null];
            }),
            'guru'          => $this->whenLoaded('guru', function() {
                return ['name' => $this->guru->name ?? null];
            }),
            'rombel'        => $this->whenLoaded('rombel', function() {
                return ['nama_kelompok' => $this->rombel->nama_kelompok ?? null];
            }),
            'mapel'         => $this->whenLoaded('mataPelajaran', function() {
                return $this->mataPelajaran->nama_mapel ?? null;
            }),
            'nama_guru'     => $this->whenLoaded('guru', function() {
                return $this->guru->name ?? null;
            }),
        ];
    }
}
