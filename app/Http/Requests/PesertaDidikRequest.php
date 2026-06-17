<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PesertaDidikRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $mergeData = [];

        if ($this->has('nisn')) {
            $mergeData['nomor_induk'] = $this->nisn;
        }

        if ($this->has('no_telepon')) {
            $mergeData['no_telepon'] = preg_replace('/\D/', '', $this->no_telepon);
        }

        if ($this->has('no_telepon_ayah')) {
            $mergeData['no_telepon_ayah'] = preg_replace('/\D/', '', $this->no_telepon_ayah);
        }

        if ($this->has('no_telepon_ibu')) {
            $mergeData['no_telepon_ibu'] = preg_replace('/\D/', '', $this->no_telepon_ibu);
        }

        if (!empty($mergeData)) {
            $this->merge($mergeData);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $routeParam = $this->route('peserta_didik');
        $id = is_object($routeParam) ? $routeParam->id : $routeParam;
        
        $rules = [
            'nama_lengkap'      => 'required|string|max:255',
            'nisn'              => 'required|digits:10|unique:peserta_didiks,nisn,' . $id,
            'nomor_induk'       => 'nullable|string|max:255|unique:peserta_didiks,nomor_induk,' . $id,
            'jenis_kelamin'     => 'required|in:L,P',
            'tempat_lahir'      => 'nullable|string|max:255',
            'tanggal_lahir'     => 'nullable|date',
            'agama'             => 'nullable|string|max:50',
            'alamat_lengkap'    => 'nullable|string',
            'asal_sekolah'      => 'required|string|max:255',
            'no_telepon'        => ['nullable', 'regex:/^[0-9]{8,15}$/', 'max:15'],
            'nama_ayah'         => 'nullable|string|max:255',
            'nama_ibu'          => 'nullable|string|max:255',
            'pekerjaan_ayah'    => 'nullable|string|max:255',
            'pekerjaan_ibu'     => 'nullable|string|max:255',
            'no_telepon_ayah'   => ['nullable', 'regex:/^[0-9]{8,15}$/', 'max:15'],
            'no_telepon_ibu'    => ['nullable', 'regex:/^[0-9]{8,15}$/', 'max:15'],
            'informasi_dari'    => 'nullable|string|max:255',
            'paket_bimbingan_id' => 'required|exists:paket_bimbingans,id',
            'kelompok_belajar_id' => 'nullable|exists:kelompok_belajars,id',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['status']         = 'required|in:Aktif,Keluar';
            $rules['tanggal_keluar'] = 'required_if:status,Keluar|nullable|date';
            $rules['alasan_keluar']  = 'required_if:status,Keluar|nullable|string';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'no_telepon.regex'      => 'Nomor telepon hanya boleh berisi angka (8-15 digit).',
            'no_telepon_ayah.regex' => 'Nomor telepon ayah hanya boleh berisi angka (8-15 digit).',
            'no_telepon_ibu.regex'  => 'Nomor telepon ibu hanya boleh berisi angka (8-15 digit).',
            'nisn.digits'           => 'NISN harus tepat 10 digit angka.',
            'nisn.unique'           => 'NISN ini sudah terdaftar pada sistem.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Illuminate\Support\Facades\Log::error('PesertaDidik Validation Failed:', $validator->errors()->toArray());
        parent::failedValidation($validator);
    }
}
