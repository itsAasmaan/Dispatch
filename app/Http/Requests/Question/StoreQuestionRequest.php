<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:300'],
            'description' => ['nullable', 'string'],
            'answer' => ['nullable', 'string'],
            'category' => ['required', 'in:dsa,system_design,behavioural,frontend,backend,devops,database,other'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'companies' => ['nullable', 'array'],
            'companies.*' => ['string', 'max:100'],
        ];
    }
}