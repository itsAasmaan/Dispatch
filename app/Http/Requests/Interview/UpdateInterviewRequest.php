<?php

namespace App\Http\Requests\Interview;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInterviewRequest extends FormRequest
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
            'role_title' => ['sometimes', 'string', 'max:150'],
            'role_type' => ['nullable', 'in:full-time,internship,contract'],
            'interview_date' => ['nullable', 'date', 'before_or_equal:today'],
            'location' => ['nullable', 'in:remote,onsite,hybrid'],
            'total_rounds' => ['sometimes', 'integer', 'min:1', 'max:20'],
            'years_of_experience' => ['sometimes', 'integer', 'min:0'],
            'outcome' => ['sometimes', 'in:offer_received,rejected,ghosted,pending,withdrew'],
            'difficulty' => ['sometimes', 'integer', 'min:1', 'max:5'],
            'overall_rating' => ['sometimes', 'integer', 'min:1', 'max:5'],
            'title' => ['sometimes', 'string', 'max:200'],
            'description' => ['sometimes', 'string', 'min:100'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
        ];
    }
}
