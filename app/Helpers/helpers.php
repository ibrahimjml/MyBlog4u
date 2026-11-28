<?php

use App\DTOs\BlogFilterDTO;
use App\Enums\Blog\BlogTab;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
if(! function_exists('abort_if_user_blocked')){
   function abort_if_user_blocked($user)
   {
     abort_if($user->is_blocked, 404);
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

if(! function_exists('max_custom_links')){
   function max_custom_links(){
      return \App\Builders\UserBuilder::MAX_CUSTOM_SOCIAL_LINKS ?? 4;
   }
}

if(! function_exists('n8n_webhook_enabled')){
   function n8n_webhook_enabled(){
      return (bool) config('n8n.enabled');
   }
}

if(! function_exists('guest_not_allowed_filter_following')){
   function guest_not_allowed_filter_following(BlogFilterDTO|string|null $filter){
        $tab = $filter instanceof BlogFilterDTO ? $filter->tab : $filter;

        $isFollowing = $tab instanceof BlogTab
            ? $tab === BlogTab::FOLLOWING
            : in_array($tab, ['followings']);    
      if ($isFollowing && ! auth()->check()) {
            $loginUrl = route('login');

           $response = request()->expectsJson()
               ? response()->json([
                       'message' => 'Unauthenticated.',
                       'redirect' => $loginUrl
                        ], 401)
               : redirect()->guest($loginUrl);
       
           throw new HttpResponseException($response);
      }
    }
}

if(! function_exists('attach_author_cards')){
  function attach_author_cards(mixed $items): void
  {
    $items = $items instanceof LengthAwarePaginator
      ? $items->getCollection()
      : collect($items);

    $attach = function ($items) use (&$attach): void {
      foreach ($items as $item) {
        if (! isset($item->author_card)) {
            $item->setAttribute('author_card', $item->user
              ? \App\Http\Resources\UserCardResource::make($item->user)->resolve()
              : null);
         }
        if ($item->relationLoaded('replies') && $item->replies->isNotEmpty()) {
          $attach($item->replies);
        }
      }
    };

    $attach($items);
  }
}

if(! function_exists('infinite_scroll_response')){
  function infinite_scroll_response(string $html, LengthAwarePaginator $items, ?array $extra = []): \Illuminate\Http\JsonResponse
{
    return response()->json(array_merge([
        'html' => $html,
        'currentPage' => $items->currentPage(),
        'hasMore' => $items->hasMorePages(),
        'nextPage' => $items->hasMorePages() ? $items->currentPage() + 1 : null
    ], $extra));
}

if(! function_exists('resolve_login_type')){
  function resolve_login_type(string $login): string {
    return filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';
  }
}

}
