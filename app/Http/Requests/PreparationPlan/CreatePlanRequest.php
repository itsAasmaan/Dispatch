<?php

namespace App\Http\Requests\PreparationPlan;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreatePlanRequest extends FormRequest
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
            'target_role' => ['required', 'in:frontend,backend,fullstack,devops,data_engineer,mobile'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'interview_date' => ['nullable', 'date', 'after:today'],
            'start_date' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }
}
