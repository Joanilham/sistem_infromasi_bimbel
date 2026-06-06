<?php

namespace App\Http\Requests\Keuangan;

use Illuminate\Foundation\Http\FormRequest;

class PembayaranRequest extends FormRequest
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
        if ($this->has('nominal') && is_string($this->nominal)) {
            $this->merge([
                'nominal' => preg_replace('/[^0-9]/', '', $this->nominal)
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
        // For verifikasi, maybe tanggal is not required. Let's just make it conditionally required
        // But the original code had 'tanggal' required for store and update, not verifikasi.
        $rules = [
            'nominal'         => 'required|integer|min:1',
            'tipe_pembayaran' => 'required|in:TUNAI,TRANSFER',
        ];

        // Only require tanggal if it's not a verification route
        if (!$this->routeIs('keuangan.pembayaran.verifikasi')) {
            $rules['tanggal'] = 'required|date';
        }

        return $rules;
    }
}
