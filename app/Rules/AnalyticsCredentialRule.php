<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AnalyticsCredentialRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
          $message = 'This credential is invalid Google Analytics credentials.';

        if (! is_string($value) || ! Str::isJson($value)) {
            $fail($message);

            return;
        }

        $content = json_decode($value, true);

        if (! is_array($content)) {
            $fail($message);

            return;
        }

        $validator = Validator::make($content, [
            'type' => ['required', 'string', 'in:service_account'],
            'project_id' => ['required', 'string'],
            'private_key_id' => ['required', 'string'],
            'private_key' => ['required', 'string'],
            'client_email' => ['required', 'string'],
            'client_id' => ['required', 'string'],
            'auth_uri' => ['required', 'string'],
            'token_uri' => ['required', 'string'],
            'auth_provider_x509_cert_url' => ['required', 'string'],
            'client_x509_cert_url' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            $fail($message);
        }
    }
}
