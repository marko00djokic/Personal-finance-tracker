<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlannedTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'            => ['required', 'in:income,expense'],
            'amount'          => ['required', 'numeric', 'min:0.01', 'max:9999999.99'],
            'category_id'     => ['nullable', 'integer', 'exists:categories,id'],
            'description'     => ['nullable', 'string', 'max:255'],
            'recurrence_type' => ['required', 'in:none,daily,weekly,monthly,yearly'],
            'recurrence_day'  => ['nullable', 'integer', 'min:1', 'max:31'],
            'next_due_date'   => ['required', 'date'],
            'is_active'       => ['nullable', 'boolean'],
        ];
    }
}
