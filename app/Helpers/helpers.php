<?php

if (!function_exists('render_mentions')) {
  function  render_mentions(string $content): string
  {
    $pattern = '/@\[(\w[\w.-]+)\]/';

    return preg_replace(
      $pattern,
      '<a href="/@$1" class="font-semibold text-blue-500 hover:underline">@$1</a>',
      e($content)
    );
  }
}

if (! function_exists('hasCompleted2FA')) {
  function hasCompleted2FA()
  {
    return auth()->check() && session()->get('2fa:passed', true);
  }
}

if (! function_exists('abort_unless_require_registration')) {
  function abort_unless_require_registration()
  {
    $allowedRegistration = \App\Models\AuthSecurityRule::first()?->allow_registration ?? true;
    abort_unless($allowedRegistration, 403, 'Registration is currently disabled contact support.');
  }
}

if(! function_exists('abort_if_user_not_activated')){
   function abort_if_user_not_activated($user)
   {
     if ($user->hasRole(\App\Enums\UserRole::USER->value) &&
        (! $user->activation || ! $user->activation->completed))
         {
      abort(404);
      }
   }
}
if(! function_exists('media_driver')){
   function media_driver(){
      return config('filesystems.default');
   }
}