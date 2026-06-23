<?php

namespace App\Providers;


use App\Enums\CustomPageStatus;
use App\Models\CustomPage;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
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

    $this->setNotificationsMenuData();
    $this->sendFollowingsIdsToView();
    $this->sendCustomPagesToFooter();


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

  private function sendCustomPagesToFooter()
  {
    view()->composer('components.footer', function ($view) {
      $footerPages = Cache::rememberForever('custom_pages', function () {
        return CustomPage::query()
          ->where('is_active', CustomPageStatus::ACTIVE->value)
          ->where('show_in_footer', true)
          ->orderBy('order')
          ->orderBy('title')
          ->get();
      });
      $categories = Cache::rememberForever('categories_footer', function () {
        return \App\Models\Category::query()
          ->orderBy('is_featured', 'desc')
          ->take(5)
          ->get();
      });
      $view->with(['footerPages' => $footerPages, 'categories' => $categories]);
    });
  }

}
