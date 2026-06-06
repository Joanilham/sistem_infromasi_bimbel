<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
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
        if ($this->has('nomor_rekening')) {
            $this->merge([
                'nomor_rekening' => preg_replace('/[^0-9]/', '', $this->nomor_rekening)
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
            'nama_bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|numeric|digits_between:10,16',
            'atas_nama' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
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
            'nomor_rekening.required' => 'Nomor rekening wajib diisi.',
            'nomor_rekening.numeric' => 'Nomor rekening harus berupa angka saja.',
            'nomor_rekening.digits_between' => 'Nomor rekening harus terdiri dari 10 sampai 16 digit.',
        ];
    }
}
