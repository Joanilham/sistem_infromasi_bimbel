<?php

namespace App\Http\Requests\Pendaftaran;

use Illuminate\Foundation\Http\FormRequest;

class VerifikasiPendaftaranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('nominal_paket') && is_string($this->nominal_paket)) {
            $this->merge([
                'nominal_paket' => preg_replace('/[^0-9]/', '', $this->nominal_paket)
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'aksi'                   => 'required|in:diverifikasi,ditolak',
            'catatan_admin'          => 'nullable|string|max:1000',
            'kelompok_belajar_id'    => 'nullable|exists:kelompok_belajars,id',
            'nominal_paket'          => 'nullable|numeric|min:0',
            'jumlah_cicilan'         => 'nullable|integer|min:1',
            'jatuh_tempo_berikutnya' => 'nullable|date',
            'batas_waktu'            => 'nullable|date',
        ];
    }
}
