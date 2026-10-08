<?php

namespace App\Http\Requests\Pendaftaran;

use Illuminate\Foundation\Http\FormRequest;

class PendaftaranStep2Request extends FormRequest
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
            'nama_lengkap'       => 'required|string|max:255',
            'nisn'               => ['required', 'digits:10', 'regex:/^[0-9]{10}$/', 'unique:pendaftaran_siswas,nisn', 'unique:peserta_didiks,nisn'],
            'jenis_kelamin'      => 'required|in:L,P',
            'no_telepon'         => ['required', 'regex:/^\+?[0-9]{8,15}$/'],
            'alamat_lengkap'     => 'required|string',
            'asal_sekolah'       => 'required|string|max:255',
            'nama_ayah'          => 'required_without:nama_ibu|nullable|string',
            'no_telepon_ayah'    => ['required_without:no_telepon_ibu', 'nullable', 'regex:/^\+?[0-9]{8,15}$/'],
            'nama_ibu'           => 'required_without:nama_ayah|nullable|string',
            'no_telepon_ibu'     => ['required_without:no_telepon_ayah', 'nullable', 'regex:/^\+?[0-9]{8,15}$/'],
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
            'nisn.required'                    => 'Nomor Induk Siswa Nasional (NISN) wajib diisi untuk verifikasi dan akses materi LMS.',
            'nisn.digits'                      => 'NISN harus terdiri dari tepat 10 digit angka.',
            'nisn.regex'                       => 'Format NISN tidak valid, hanya boleh berisi 10 digit angka.',
            'nisn.unique'                      => 'NISN ini sudah terdaftar dalam sistem bimbel.',
            'no_telepon.required'              => 'Nomor telepon/WhatsApp wajib diisi agar bimbel dapat menghubungi Anda.',
            'alamat_lengkap.required'          => 'Alamat lengkap wajib diisi.',
            'nama_ayah.required_without'       => 'Anda wajib mengisi Nama Ayah ATAU Nama Ibu.',
            'no_telepon_ayah.required_without' => 'Anda wajib mengisi Nomor Telepon Ayah ATAU Ibu untuk keperluan komunikasi dengan wali.',
        ];
    }
}
