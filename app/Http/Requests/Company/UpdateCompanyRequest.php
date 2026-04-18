<?php

namespace App\Http\Requests\Company;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:100', 'unique:companies,name,' . $this->route('company')],
            'website' => ['nullable', 'url'],
            'description' => ['nullable', 'string'],
            'tagline' => ['nullable', 'string', 'max:200'],
            'industry' => ['nullable', 'string', 'max:100'],
            'headquarters' => ['nullable', 'string', 'max:100'],
            'size' => ['nullable', 'in:1-10,11-50,51-200,201-500,501-1000,1001-5000,5001+'],
            'founded_year' => ['nullable', 'integer', 'min:1800', 'max:' . date('Y')],
            'linkedin_url' => ['nullable', 'url'],
            'twitter_url' => ['nullable', 'url'],
            'glassdoor_url' => ['nullable', 'url'],
        ];
    }
}
