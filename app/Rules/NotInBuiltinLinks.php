<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotInBuiltinLinks implements ValidationRule
{
    public function __construct(private ?User $user = null) {}

    /**
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->user) {
            return;
        }

        $builder = $this->user->newQuery();
        $value   = (string) $value;

        if ($builder->isBuiltinSocialPlatform($value)) {
            $fail('This platform already exists in your built-in social links.');

            return;
        }

        if ($builder->isBuiltinSocialUrl($value)) {
            $fail('This URL already exists in your built-in social links.');
        }
    }
}
