<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SubmitAnswerRequest extends FormRequest
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
            'question_id' => ['required', 'exists:questions,id'],
            'answer' => ['nullable', 'string'],
            'self_rating' => ['required', 'in:correct,partial,incorrect,skipped'],
            'note' => ['nullable', 'string', 'max:500'],
            'time_spent_seconds' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
