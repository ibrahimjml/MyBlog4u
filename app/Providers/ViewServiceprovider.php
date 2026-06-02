<?php

namespace App\Providers;

use App\Helpers\MetaHelpers;
use App\Models\SeoSetting;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    $this->setCustomSeo();
    $this->setMetaForPostPage();
    $this->setMetaForHashtagPage();
    $this->setMetaForProfilePage();
    $this->setNotificationsMenuData();
    $this->sendFollowingsIdsToView();


  }
  private function setMetaForPostpage()
  {
    View::composer('post', function ($view) {
      $post = request()->route('post');
      if ($post && is_object($post)) {
        $meta = MetaHelpers::generateMetaForPosts($post);
        MetaHelpers::setSection($meta);
        $view->with($meta);
      }
    });
  }
  private function setMetaForHashtagPage()
  {
    View::composer('hashtags.show', function ($view) {
      $hashtag = request()->route('hashtag');

      if ($hashtag && is_object($hashtag)) {
        $title = "Hashtag - {$hashtag->name} page";
        $description = "Welcome to {$hashtag->name} page";
        $meta = MetaHelpers::generateDefault($title, $description, [$hashtag->name]);
        MetaHelpers::setSection($meta);

        $view->with($meta);
      }
    });
  }
  private function setMetaForProfilePage()
  {
    View::composer('profile.profile', function ($view) {
      $user = request()->route('user');

      if ($user && is_object($user)) {
        $title = match (true) {
          request()->routeIs('profile.activity') => "{$user->name}'s Activity | Blog-Post",
          request()->routeIs('profile.aboutme') => "About {$user->name} | Blog-Post",
          default => "{$user->name}'s Profile | Blog-Post"
        };
        $desc = "{$user->name} profile page connect with him.";
        $meta = MetaHelpers::generateDefault($title, $desc, [], $user);
        MetaHelpers::setSection($meta);

        $view->with($meta);
      }
    });
  }
  private function setNotificationsMenuData()
  {
    view::composer('partials.notifications-menu', function ($view) {
      if (!auth()->check()) {
        return;
      }

      $notifications = auth()->user()->notifications()->get();
      $usernames = $notifications
        ->pluck('data')
        ->flatMap(fn($data) => collect($data)->filter(fn($val, $key) => str_contains($key, 'username')))
        ->unique()
        ->values();

      $users = User::whereIn('username', $usernames)->get()->keyBy('username');
      $view->with([
        'users' => $users,
        'notifications' => $notifications
      ]);
    });
  }
  private function sendFollowingsIdsToView()
  {
    view::composer('*', function ($view) {
      static $followings = null;

      if ($followings === null) {
        if (auth()->check()) {
          $followings = auth()->user()
            ->allFollowings()
            ->select('users.id', 'followers.status')
            ->pluck('followers.status', 'users.id')
            ->toArray();
        } else {
          $followings = [];
        }
      }
      $view->with('authFollowings', $followings);
    });
  }

  private function setCustomSeo()
  {
    View::composer(['components.head','admin.partials.layout'], function ($view) {
      $seoSettings = SeoSetting::first();
      if (! $seoSettings) {
        return;
      }
       $meta = MetaHelpers::generateCustomSeo($seoSettings->meta_title, $seoSettings->meta_description, $seoSettings->meta_keywords, $seoSettings->meta_og_image_url, $seoSettings->favicon_url,$seoSettings->author,$seoSettings->header_scripts,$seoSettings->footer_scripts);
        MetaHelpers::setSection($meta);

      $view->with($meta);
    });
  }
}
