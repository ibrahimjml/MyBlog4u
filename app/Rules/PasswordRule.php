<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rules\Password;

class PasswordRule implements ValidationRule
{
  /**
   * Run the validation rule.
   *
   * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
   */
  public function validate(string $attribute, mixed $value, Closure $fail): void
  {
    $rule = app()->isProduction()
      ? Password::min(8)
        ->mixedCase()
        ->letters()
        ->numbers()
        ->symbols()
        ->uncompromised()
      : Password::min(8);

    $validator = validator(
      [$attribute => $value],
      [$attribute => [$rule]]
    );

    if ($validator->fails()) {
      $fail($validator->errors()->first($attribute));
    }
  }
}
