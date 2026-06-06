<?php

namespace App\Http\Requests\Akademik;

use Illuminate\Foundation\Http\FormRequest;

class KelompokBelajarRequest extends FormRequest
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
            'nama_kelompok' => 'required|string|max:255',
        ];
    }
}
