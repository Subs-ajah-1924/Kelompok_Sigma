<?php

namespace App\Http\Requests;

use App\Difficulty;
use App\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:5000'],
            'course_id' => ['nullable', 'integer', Rule::exists('courses', 'id')->where('user_id', $this->user()->id)],
            'deadline' => ['required', 'date'],
            'difficulty' => ['required', Rule::enum(Difficulty::class)],
            'status' => ['nullable', Rule::enum(TaskStatus::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul tugas wajib diisi.',
            'deadline.required' => 'Deadline wajib diisi.',
            'difficulty.required' => 'Tingkat kesulitan wajib dipilih.',
        ];
    }
}
