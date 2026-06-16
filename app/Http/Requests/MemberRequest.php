<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // ID du membre en cours de modification (null en création).
        $memberId = $this->route('member');

        return [
            'first_name'           => ['required', 'string', 'max:255'],
            'last_name'            => ['required', 'string', 'max:255'],
            'email'                => ['required', 'email', 'max:255', Rule::unique('members', 'email')->ignore($memberId)],
            'phone'                => ['nullable', 'string', 'max:30'],
            'join_date'            => ['required', 'date'],
            'expiry_date'          => ['required', 'date', 'after_or_equal:join_date'],
            'subscription_type_id' => ['required', 'exists:subscription_types,id'],
        ];
    }
}
