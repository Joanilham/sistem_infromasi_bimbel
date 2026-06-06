<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilePhotoRequest extends FormRequest
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
            'photo' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
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
            'photo.image' => 'File yang diunggah harus berupa gambar.',
            'photo.mimes' => 'Format gambar hanya diperbolehkan: JPG, JPEG, PNG, WEBP.',
            'photo.max'   => 'Ukuran gambar maksimal adalah 5MB.',
        ];
    }
}
