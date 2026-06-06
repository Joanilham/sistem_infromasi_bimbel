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
            'name'          => 'required|string|max:255',
            'jenis_kelamin' => 'nullable|in:Laki-Laki,Perempuan',
            'nip'           => 'nullable|string|max:20',
            'alamat'        => 'nullable|string|max:500',
            'matapelajaran' => 'required|string|max:255',
            'no_telp'       => ['nullable', 'regex:/^[0-9]{8,15}$/', 'max:15'],
            'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($guruId)],
            'password'      => 'nullable|string|min:8|confirmed',
            'status'        => 'required|in:Aktif,Keluar',
            'tanggal_keluar' => 'nullable|date',
            'alasan_keluar' => 'nullable|string',
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
            'no_telp.regex' => 'Nomor telepon hanya boleh berisi angka (8-15 digit).',
        ];
    }
}
