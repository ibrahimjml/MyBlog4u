<?php

namespace App\Http\Requests\App\Auth;

use Illuminate\Foundation\Http\FormRequest;

class TwoFactorRecoveryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recovery_code' => ['required', 'string'],
            'trust_device' => ['sometimes', 'boolean'],
        ];
    }
}
