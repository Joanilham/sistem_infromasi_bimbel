<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePenggunaRequest extends FormRequest
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
        $penggunaId = $this->route('pengguna')->id ?? $this->route('pengguna');

        return [
            'name'     => 'required|string|max:255',
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($penggunaId)],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($penggunaId)],
            'level'    => ['required', 'string', Rule::in($this->allowedLevels)],
            'password' => 'nullable|string|min:8|confirmed',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'string',
        ];
    }
}
