<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:100'],
            'username' => ['sometimes', 'string', 'max:50', 'unique:users,username,' . auth()->id(), 'alpha_dash'],
            'bio' => ['nullable', 'string', 'max:500'],
            'current_role' => ['nullable', 'string', 'max:100'],
            'current_company' => ['nullable', 'string', 'max:100'],
            'years_of_experience' => ['nullable', 'integer', 'min:0', 'max:50'],
            'github_url' => ['nullable', 'url'],
            'linkedin_url' => ['nullable', 'url'],
            'portfolio_url' => ['nullable', 'url'],
        ];
    }
}