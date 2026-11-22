<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('courses', 'name')->where('user_id', $this->user()->id)],
            'code' => ['nullable', 'string', 'max:20'],
            'color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'day' => ['nullable', 'string', 'max:20'],
            'time' => ['nullable', 'string', 'max:50'],
            'sks' => ['nullable', 'integer', 'min:1', 'max:10'],
            'lecturer' => ['nullable', 'string', 'max:100'],
        ];
    }
}
