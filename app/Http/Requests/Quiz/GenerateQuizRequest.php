<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GenerateQuizRequest extends FormRequest
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
            'type' => ['required', 'in:topic,company,role,mixed,mock_interview'],
            'topic' => ['required_if:type,topic', 'nullable', 'string'],
            'company' => ['required_if:type,company', 'nullable', 'string'],
            'role' => ['required_if:type,role', 'nullable', 'in:frontend,backend,fullstack,devops,data_engineer,mobile'],
            'difficulty' => ['nullable', 'in:easy,medium,hard,mixed'],
            'total_questions' => ['nullable', 'integer', 'min:5', 'max:50'],
            'time_limit' => ['nullable', 'integer', 'min:5', 'max:120'],
            'is_timed' => ['nullable', 'boolean'],
        ];
    }
}
