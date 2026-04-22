<?php

namespace App\Http\Requests\SalaryInsight;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalaryInsightRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'role_title' => ['required', 'string', 'max:150'],
            'role_type' => ['required', 'in:full-time,internship,contract'],
            'base_salary' => ['required', 'numeric', 'min:0'],
            'bonus' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'location' => ['nullable', 'string', 'max:100'],
            'years_of_experience' => ['required', 'integer', 'min:0'],
            'outcome' => ['required', 'in:offer_received,counter_offer,accepted,rejected'],
            'offer_year' => ['nullable', 'integer', 'min:2000', 'max:' . date('Y')],
            'is_anonymous' => ['nullable', 'boolean'],
        ];
    }
}