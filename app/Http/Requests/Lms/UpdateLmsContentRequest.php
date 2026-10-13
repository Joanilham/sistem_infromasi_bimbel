<?php

namespace App\Http\Requests\Lms;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLmsContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:materi,tugas'],
            'title' => ['required', 'string', 'max:255'],
            'subject' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:10000'],
            'kelompok_belajar_id' => ['required', 'integer', 'exists:kelompok_belajars,id'],
            'due_at' => ['nullable', 'date'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,csv,zip,rar,jpg,jpeg,png,webp', 'max:20480'],
        ];
    }
}
