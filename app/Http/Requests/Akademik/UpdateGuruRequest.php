<?php

namespace App\Http\Requests\Akademik;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGuruRequest extends FormRequest
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
     */
    protected function prepareForValidation()
    {
        if (!$this->has('mata_pelajaran_ids') && !$this->has('new_matapelajaran') && $this->filled('matapelajaran')) {
            $this->merge([
                'new_matapelajaran' => $this->matapelajaran,
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
        // Parameter 'id' atau 'guru' didapat dari route model binding / segment
        // Bergantung pada definisi rute, kita asumsikan namanya 'guru' atau langsung pakai segment terakhir
        $guruId = $this->route('guru') ?: $this->route('id');

        return [
            'name'                 => 'required|string|max:255',
            'jenis_kelamin'        => 'nullable|in:Laki-Laki,Perempuan',
            'nip'                  => 'nullable|string|max:20',
            'alamat'               => 'nullable|string|max:500',
            'matapelajaran'        => 'required_without_all:mata_pelajaran_ids,new_matapelajaran|nullable|string|max:255',
            'mata_pelajaran_ids'   => 'required_without_all:matapelajaran,new_matapelajaran|array|min:1',
            'mata_pelajaran_ids.*' => 'integer|exists:cbt_mapels,id',
            'new_matapelajaran'    => 'nullable|string|max:255',
            'no_telp'              => ['nullable', 'regex:/^[0-9]{8,15}$/', 'max:15'],
            'email'                => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($guruId)],
            'password'             => 'nullable|string|min:8|confirmed',
            'status'               => 'required|in:Aktif,Keluar',
            'tanggal_keluar'       => 'nullable|date',
            'alasan_keluar'        => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'no_telp.regex'                           => 'Nomor telepon hanya boleh berisi angka (8-15 digit).',
            'matapelajaran.required_without_all'      => 'Pilih minimal satu mata pelajaran yang diampu oleh guru.',
            'mata_pelajaran_ids.required_without_all' => 'Pilih minimal satu mata pelajaran yang diampu oleh guru.',
            'mata_pelajaran_ids.min'                  => 'Pilih minimal satu mata pelajaran yang diampu oleh guru.',
        ];
    }
}
