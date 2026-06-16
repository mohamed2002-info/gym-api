<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'price'         => ['required', 'numeric', 'min:0'],
        ];
    }
}
