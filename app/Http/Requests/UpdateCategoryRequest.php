<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:100'],
            'type'          => ['required', 'in:income,expense'],
            'color'         => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'icon'          => ['nullable', 'string', 'max:50'],
            'monthly_limit' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
