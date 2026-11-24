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

    protected function prepareForValidation(): void
    {
        if ($this->filled('start_time') && $this->filled('end_time')) {
            $this->merge([
                'time' => $this->input('start_time').' - '.$this->input('end_time'),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('courses', 'name')->where('user_id', $this->user()->id)],
            'code' => ['nullable', 'string', 'max:20'],
            'color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'day' => ['nullable', 'string', 'max:20'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after_or_equal:start_time'],
            'time' => ['nullable', 'string', 'max:50', 'regex:/^\d{2}:\d{2} - \d{2}:\d{2}$/'],
            'sks' => ['nullable', 'integer', 'min:1', 'max:10'],
            'lecturer' => ['nullable', 'string', 'max:100'],
        ];
    }
}
