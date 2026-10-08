<?php

namespace App\Http\Requests\Akademik;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuruRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                => 'required|string|max:255',
            'jenis_kelamin'       => 'nullable|in:Laki-Laki,Perempuan',
            'nip'                 => 'nullable|string|max:20',
            'alamat'              => 'nullable|string|max:500',
            'matapelajaran'       => 'nullable|string|max:255',
            'mata_pelajaran_ids'   => 'required_without:new_matapelajaran|array|min:1',
            'mata_pelajaran_ids.*' => 'integer|exists:cbt_mapels,id',
            'new_matapelajaran'   => 'nullable|string|max:255',
            'no_telp'             => ['nullable', 'regex:/^[0-9]{8,15}$/', 'max:15'],
            'email'               => 'required|string|email|max:255|unique:users',
            'password'            => 'required|string|min:8|confirmed',
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
            'no_telp.regex'                         => 'Nomor telepon hanya boleh berisi angka (8-15 digit).',
            'mata_pelajaran_ids.required_without'   => 'Pilih minimal satu mata pelajaran yang diampu oleh guru.',
            'mata_pelajaran_ids.min'                => 'Pilih minimal satu mata pelajaran yang diampu oleh guru.',
        ];
    }
}
