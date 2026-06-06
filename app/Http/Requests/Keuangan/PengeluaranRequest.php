<?php

namespace App\Http\Requests\Keuangan;

use Illuminate\Foundation\Http\FormRequest;

class PengeluaranRequest extends FormRequest
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
        return [
            'tanggal'     => 'required|date',
            'kategori_id' => 'required|exists:kategori_pengeluaran,id',
            'nominal'     => 'required|integer|min:1',
            'keterangan'  => 'nullable|string|max:255',
        ];
    }
}
