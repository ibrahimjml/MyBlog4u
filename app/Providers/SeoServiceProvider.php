<?php

namespace App\Providers;

use App\Helpers\MetaHelpers;
use App\Models\SeoSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SeoServiceProvider extends ServiceProvider
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
  }

  private function setMetaForPostpage()
  {
    View::composer('post', function ($view) {
      $post = request()->route('post');
      if ($post && is_object($post)) {
        $meta = MetaHelpers::generateMetaForPosts($post);
        MetaHelpers::shareToView($meta);
      }
    });
  }
  private function setMetaForHashtagPage()
  {
    View::composer('hashtags.show', function ($view) {
      $hashtag = request()->route('hashtag');

      if ($hashtag && is_object($hashtag)) {
        $title = "Posts with #{$hashtag->name}";
        $description = "Welcome to #{$hashtag->name} hashtag page, explore posts tagged with #{$hashtag->name} and join the conversation!";
        $meta = MetaHelpers::generateDefault($title, $description, [$hashtag->name]);
        MetaHelpers::shareToView($meta);

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
          default => "{$user->name}'s Profile | Myblog4u"
        };
        $desc = "Explore the profile of {$user->name}, a content creator on Myblog4u.";
        $meta = MetaHelpers::generateDefault($title, $desc, [], $user);
        MetaHelpers::shareToView($meta);

      }
    });
  }
  private function setCustomSeo()
  {
    $seoSettings = SeoSetting::first();
    View::composer('*', function ($view) use ($seoSettings){

      $route = request()->route()?->getName();
      $skipRoutes = [
        'single.post',
        'viewhashtag',
        'profile.home',
        'profile.activity',
        'profile.aboutme',
      ];
      if (in_array($route, $skipRoutes)) {
        return;
      }
      

      $meta = MetaHelpers::generateCustomSeo(
        $seoSettings?->author,
        $seoSettings?->favicon_url,
        $seoSettings?->meta_og_image_url,
        $seoSettings?->meta_title,
        $seoSettings?->meta_description,
        $seoSettings?->meta_keywords,
        $seoSettings?->header_scripts,
        $seoSettings?->footer_scripts
      );

      MetaHelpers::shareToView($meta);
    });
  }
}
