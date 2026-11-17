<?php

namespace App\Http\Requests;

use App\Difficulty;
use App\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
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
            'status' => ['required', Rule::enum(TaskStatus::class)],
        ];
    }
}
