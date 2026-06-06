<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;

class MasterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // We assume auth check is handled in middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama_lembaga'      => 'nullable|string|max:255',
            'alamat_lembaga'    => 'nullable|string',
            'wa_url'            => 'nullable|url|max:255',
            'instance_id'       => 'nullable|string|max:255',
            'wa_token'          => 'nullable|string|max:255',
            'api_key'           => 'nullable|string|max:255',
            'logo'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ];
    }
}
