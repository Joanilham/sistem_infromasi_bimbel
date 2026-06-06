<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePenggunaRequest extends FormRequest
{
    protected $allowedLevels = ['Super Admin', 'Admin', 'Siswa', 'Guru'];

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
            'name'                  => 'required|string|max:255',
            'username'              => 'nullable|string|max:255|unique:users',
            'email'                 => 'required|string|email|max:255|unique:users',
            'level'                 => ['required', 'string', Rule::in($this->allowedLevels)],
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
            'permissions'           => 'nullable|array',
            'permissions.*'         => 'string',
        ];
    }
}
