<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CbtPesertaJawabanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'cbt_peserta_id'      => $this->cbt_peserta_id,
            'cbt_bank_soal_id'    => $this->cbt_bank_soal_id,
            'cbt_opsi_jawaban_id' => $this->cbt_opsi_jawaban_id,
            'jawaban_essay'       => $this->jawaban_essay,
            'ragu_ragu'           => (bool)$this->ragu_ragu,
            'urutan'              => $this->urutan,
            'skor'                => $this->skor,
            'bank_soal'           => $this->whenLoaded('bankSoal', function() {
                // Return simple array structure instead of creating another Resource for now
                $data = [
                    'id'         => $this->bankSoal->id,
                    'pertanyaan' => $this->bankSoal->pertanyaan,
                    'tipe_soal'  => $this->bankSoal->tipe_soal,
                ];

                if ($this->bankSoal->relationLoaded('opsiJawabans')) {
                    $data['opsi_jawabans'] = $this->bankSoal->opsiJawabans->map(function ($opsi) {
                        return [
                            'id'        => $opsi->id,
                            'teks_opsi' => $opsi->teks_opsi,
                            'is_benar'  => $opsi->is_benar, // May be hidden in frontend if testing
                        ];
                    });
                }

                if ($this->bankSoal->relationLoaded('pembahasan')) {
                    $data['pembahasan'] = [
                        'teks_pembahasan' => $this->bankSoal->pembahasan->teks_pembahasan ?? null,
                        'referensi'       => $this->bankSoal->pembahasan->referensi ?? null,
                    ];
                }

                return $data;
            }),
        ];
    }
}
