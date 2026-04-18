<?php

namespace App\Http\Requests\Interview;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInterviewRequest extends FormRequest
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
            'company_id' => ['required', 'exists:companies,id'],
            'role_title' => ['required', 'string', 'max:150'],
            'role_type' => ['nullable', 'in:full-time,internship,contract'],
            'interview_date' => ['nullable', 'date', 'before_or_equal:today'],
            'location' => ['nullable', 'in:remote,onsite,hybrid'],
            'total_rounds' => ['required', 'integer', 'min:1', 'max:20'],
            'years_of_experience' => ['required', 'integer', 'min:0'],
            'outcome' => ['required', 'in:offer_received,rejected,ghosted,pending,withdrew'],
            'difficulty' => ['required', 'integer', 'min:1', 'max:5'],
            'overall_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'min:100'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'status' => ['nullable', 'in:draft,published'],

            // Rounds
            'rounds' => ['required', 'array', 'min:1'],
            'rounds.*.round_number' => ['required', 'integer', 'min:1'],
            'rounds.*.round_type' => ['required', 'in:hr,technical,system_design,dsa,managerial,assignment,cultural_fit,other'],
            'rounds.*.title' => ['nullable', 'string', 'max:150'],
            'rounds.*.description' => ['required', 'string', 'min:20'],
            'rounds.*.tips' => ['nullable', 'string'],
            'rounds.*.duration_minutes' => ['nullable', 'integer', 'min:1'],
            'rounds.*.difficulty' => ['required', 'in:easy,medium,hard'],
            'rounds.*.cleared' => ['required', 'boolean'],
        ];
    }
}
