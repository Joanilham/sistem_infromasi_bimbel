<?php

namespace App\Http\Requests\Pendaftaran;

use Illuminate\Foundation\Http\FormRequest;

class PendaftaranStep3Request extends FormRequest
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
            'metode_pembayaran'   => 'required|string',
            'jenis_bayar'         => 'required|in:full,dp',
            'bukti_pembayaran'    => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'paket_bimbingan_id'  => 'required|exists:paket_bimbingans,id',
            'syarat_ketentuan'    => 'accepted', // WAJIB CENTANG S&K
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
            'bukti_pembayaran.required'   => 'Bukti pembayaran wajib diunggah.',
            'bukti_pembayaran.mimes'      => 'Format bukti pembayaran hanya diperbolehkan: JPG, JPEG, PNG, PDF.',
            'bukti_pembayaran.max'        => 'Ukuran file maksimal adalah 5MB.',
            'bukti_pembayaran.uploaded'   => 'File gagal diunggah. Pastikan ukuran file tidak melebihi batas sistem (kemungkinan maksimal 2MB) dan formatnya benar.',
            'paket_bimbingan_id.required' => 'Paket bimbingan belajar wajib dipilih.',
            'jenis_bayar.required'        => 'Pilihan jenis pembayaran (DP / Penuh) wajib dipilih.',
            'jenis_bayar.in'              => 'Jenis pembayaran tidak valid.',
            'syarat_ketentuan.accepted'   => 'Anda wajib mencentang dan menyetujui Syarat & Ketentuan pendaftaran.',
        ];
    }
}
