<?php


if (!function_exists('render_mentions')) {
  function  render_mentions(string $content): string
  {
    $pattern = '/@\[(\w[\w.-]+)\]/';

    return preg_replace(
      $pattern,
      '<a href="/@$1" class="font-semibold text-primary hover:underline">@$1</a>',
      e($content)
    );
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
     if (! $user->activation?->completed){
         abort(404);
        }
   }
}
if(! function_exists('media_driver')){
   function media_driver(){
      return config('filesystems.default');
   }
}
if(! function_exists('image_upload_size')){
   function image_upload_size(){
      return (bool) \App\Models\Setting::get('enable_image_optimization')
             ? (int) \App\Models\Setting::get('image_max_upload_size')
             : 5;
   }
}

if(! function_exists('n8n_webhook_enabled')){
   function n8n_webhook_enabled(){
      return (bool) config('n8n.enabled');
   }
}

if(! function_exists('guest_not_allowed_filter_following')){
   function guest_not_allowed_filter_following($sort){
    if (in_array($sort, ['followings']) && !auth()->check()) {
      throw new \Illuminate\Auth\AuthenticationException();
    }
   }
}

if(! function_exists('infinite_scroll_response')){
  function infinite_scroll_response(string $html, \Illuminate\Contracts\Pagination\LengthAwarePaginator $posts, ?array $extra = []): \Illuminate\Http\JsonResponse
{
    return response()->json(array_merge([
        'html' => $html,
        'currentPage' => $posts->currentPage(),
        'hasMore' => $posts->hasMorePages(),
        'nextPage' => $posts->hasMorePages() ? $posts->currentPage() + 1 : null
    ], $extra));
}

if(! function_exists('resolve_login_type')){
  function resolve_login_type(string $login): string {
    return filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';
  }
}
if (! function_exists('ensure_pending_two_factor_challenge')) {
    function ensure_pending_two_factor_challenge(): ?\Illuminate\Http\RedirectResponse
    {
        if (session()->has('2fa:user:id')) {
            return null;
        }

        if (auth()->check()) {
            return back();
        }

        return redirect()->route('login');
    }
}
}
