<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransaksiPembayaranResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'nominal'          => $this->nominal,
            'tanggal'          => $this->tanggal ? $this->tanggal->format('Y-m-d') : null,
            'tipe_pembayaran'  => $this->tipe_pembayaran,
            'no_kwitansi'      => $this->no_kwitansi,
            'penerima'         => $this->penerima,
            'status'           => $this->status,
            'bukti_pembayaran' => $this->bukti_pembayaran ? asset('storage/' . $this->bukti_pembayaran) : null,
            'catatan_siswa'    => $this->catatan_siswa,
            'alasan_penolakan' => $this->alasan_penolakan,
            'bank_tujuan'      => $this->whenLoaded('bankTujuan', function() {
                return new BankResource($this->bankTujuan);
            }),
            'created_at'       => $this->created_at ? $this->created_at->toIso8601String() : null,
        ];
    }
}
