<?php

namespace App\Http\Requests\Roadmap;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTopicProgressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'in:not_started,in_progress,completed'],
            'progress_percentage' => ['nullable', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
