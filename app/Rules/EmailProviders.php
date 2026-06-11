<?php

namespace App\Rules;

use App\Models\AuthSecurityRule;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailProviders implements ValidationRule
{
  /**
   * Run the validation rule.
   *
   * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
   */
  public function validate(string $attribute, mixed $value, Closure $fail): void
  {
    $record = AuthSecurityRule::first()?->allowed_email_domains ?? '';
    $allowedDomains = array_filter(array_map('trim', explode(',', $record)));

    [, $domain] = explode('@', $value);

    if (!empty($allowedDomains) && !in_array($domain, $allowedDomains, true)) {
      $fail('The :attribute belongs to an unsupported domain, please use supported one.');
      return;
    }


  }
}
