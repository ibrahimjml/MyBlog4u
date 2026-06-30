<?php

namespace App\Providers;

use App\Models\{Category, Like, Post, PostReport, Setting, SmtpSetting, User};
use App\Observers\{ LikeObserver, PostObserver, PostReportObserver, CategoryObserver, UserObserver};
use App\Repositories\Caches\CategoryCacheDecorator;
use App\Repositories\Eloquent\PostRepository;
use App\Repositories\Caches\PostCacheDecorator;
use App\Repositories\Caches\TagCacheDecorator;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\TagRepository;
use App\Repositories\Interfaces\CategoryInterface;
use App\Repositories\Interfaces\PostInterface;
use App\Repositories\Interfaces\TagInterface;
use App\Services\MediaDriverResolver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {

    $this->app->bind(PostInterface::class, function ($app) {
      if (config('cache.enabled')) {
        return new PostCacheDecorator($app->make(PostRepository::class));
      }
      return $app->make(PostRepository::class);
    });

    $this->app->bind(TagInterface::class, function ($app) {
      if (config('cache.enabled')) {
        return new TagCacheDecorator($app->make(TagRepository::class));
      }
      return $app->make(TagRepository::class);
    });

    $this->app->bind(CategoryInterface::class, function ($app) {
      if (config('cache.enabled')) {
        return new CategoryCacheDecorator($app->make(CategoryRepository::class));
      }
      return $app->make(CategoryRepository::class);
    });
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    app(MediaDriverResolver::class)->resolveAndApply();
    $this->bootEvents();
    $this->bootBladeDirectives();
    $this->bootDynamicConfigSmtp();
    $this->bootDynamicConfigRecaptcha();
    $this->setGoogleAnalytics();
  }


  public function bootEvents()
  {
    Post::observe(PostObserver::class);
    Like::observe(LikeObserver::class);
    PostReport::observe(PostReportObserver::class);
    User::observe(UserObserver::class);
    Category::observe(CategoryObserver::class);
  }
  public function bootBladeDirectives()
  {
    // Custom Blade component for post card
    Blade::component('partials.postcard', 'postcard');

    // Custom Blade directive to check status of following relationship
    Blade::if('following', function ($status) {
      return $status instanceof \App\Enums\FollowerStatus
        && $status === \App\Enums\FollowerStatus::ACCEPTED;
    });

    // Custom Blade directive to check if reCAPTCHA is enabled
    Blade::if('recaptcha_enabled', function () {
      $authSecurityRule = \App\Models\AuthSecurityRule::first();
      return $authSecurityRule && $authSecurityRule->require_captcha;
    });
  }
  public function bootDynamicConfigSmtp()
  {
    if (app()->runningInConsole() || !Schema::hasTable('smtpsettings')) {
      return;
    }
    $smtp = SmtpSetting::first();
    if ($smtp) {
      $data = [
        'default' => $smtp->mail_transport,
        'mailers' => [
          $smtp->mail_transport => [
            'transport' => $smtp->mail_transport,
            'host' => $smtp->mail_host,
            'port' => $smtp->mail_port,
            'username' => $smtp->mail_username,
            'password' => $smtp->mail_password,
            'encryption' => $smtp->mail_encryption,
            'timeout' => null,
            'auth_mode' => null,
          ],
        ],
        'from' => [
          'address' => $smtp->mail_from,
          'name' => config('app.name'),
        ],
      ];

      Config::set('mail.mailers', $data['mailers']);
      Config::set('mail.default', $data['default']);
      Config::set('mail.from', $data['from']);
    }
  }
  public function bootDynamicConfigRecaptcha()
  {
    if (app()->runningInConsole() || !Schema::hasColumn('auth_security_rules', 'require_captcha')) {
      return;
    }
    $authSecurityRule = \App\Models\AuthSecurityRule::first();
    if ($authSecurityRule && $authSecurityRule->require_captcha) {
      Config::set('services.captcha.sitekey', $authSecurityRule->recaptcha_sitekey);
      Config::set('services.captcha.secretkey', $authSecurityRule->recaptcha_secretkey);
    }
  }
  private function setGoogleAnalytics()
  {
    if (!Schema::hasTable('settings')) {
      return;
    }
    $settings = Setting::pluck('value', 'key');
    config([
      'analytics.analytics_enabled' => $settings->get('analytics_dashboard_widgets', false),
      'analytics.property_id' => $settings->get('analytics_property_id'),
    ]);
  }
}