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
            'logo'              => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'mail_host'         => 'nullable|string|max:255',
            'mail_port'         => 'nullable|string|max:10',
            'mail_username'     => 'nullable|string|max:255',
            'mail_password'     => 'nullable|string|max:255',
            'mail_encryption'   => 'nullable|string|max:50',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name'    => 'nullable|string|max:255',
        ];
    }
}
