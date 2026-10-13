<?php

namespace App\Http\Requests\Lms;

use Illuminate\Foundation\Http\FormRequest;

class GradeSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'feedback_guru' => ['nullable', 'string', 'max:5000'],
            'status' => ['nullable', 'in:graded'],
        ];
    }
}
