<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'level' => $this->level,
            'status' => $this->status,
            'is_active' => $this->is_active,
            // If it's a student, we add extra fields computed in AuthController
            'no_telp'    => $this->when(isset($this->no_telp), $this->no_telp),
            'alamat'     => $this->when(isset($this->alamat), $this->alamat),
            'nis'        => $this->when(isset($this->nis), $this->nis),
            'dispensasi' => $this->when(isset($this->dispensasi), $this->dispensasi),
            'is_overdue' => $this->when(isset($this->is_overdue), $this->is_overdue),
        ];
    }
}
