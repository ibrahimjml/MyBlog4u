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
    
    $this->setNotificationsMenuData();
    $this->sendFollowingsIdsToView();


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


}
