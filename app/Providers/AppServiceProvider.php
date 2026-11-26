<?php

namespace App\Providers;

use App\Contracts\Services\ActiveSessionService as ActiveSessionContract;
use App\Contracts\Services\TrustedDeviceService as TrustedDeviceContract;
use App\Contracts\Services\TwoFactorService as TwoFactorContract;
use App\Models\{Category, Like, Post, PostReport, Setting, SmtpSetting, User};
use App\Observers\{ LikeObserver, PostObserver, PostReportObserver, CategoryObserver, UserObserver};
use App\Repositories\Caches\CategoryCacheDecorator;
use App\Repositories\Caches\NewsCacheDecorator;
use App\Repositories\Caches\UserCacheDecorator;
use App\Repositories\Eloquent\NewsRepository;
use App\Repositories\Eloquent\PostRepository;
use App\Repositories\Caches\PostCacheDecorator;
use App\Repositories\Caches\TagCacheDecorator;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\TagRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\CategoryInterface;
use App\Repositories\Interfaces\NewsInterface;
use App\Repositories\Interfaces\PostInterface;
use App\Repositories\Interfaces\TagInterface;
use App\Repositories\Interfaces\UserInterface;
use App\Services\MediaDriverResolver;
use App\Services\Sitemap\Sitemap;
use App\Services\TwoFactorService;
use App\Services\User\ActiveSessionService;
use App\Services\User\TrustedDeviceService;
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
    $this->app->singleton(Sitemap::class);
    $this->app->alias(Sitemap::class, 'sitemap');

    $this->app->singleton(TwoFactorContract::class, TwoFactorService::class);
    $this->app->singleton(TrustedDeviceContract::class, TrustedDeviceService::class);
    $this->app->singleton(ActiveSessionContract::class, ActiveSessionService::class);

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

    $this->app->bind(UserInterface::class, function ($app) {
      if (config('cache.enabled')) {
        return new UserCacheDecorator($app->make(UserRepository::class));
      }
      return $app->make(UserRepository::class);
    });
    $this->app->bind(NewsInterface::class, function ($app) {
      if (config('cache.enabled')) {
        return new NewsCacheDecorator($app->make(NewsRepository::class));
      }
      return $app->make(NewsRepository::class);
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
    Blade::directive('redirectUrl', function ($expression) {
    return <<<PHP
           <?php
           
           \$url = auth()->check()
               ? ($expression)()
               : route('login');
           
           echo "window.location.href='" . e(\$url) . "';";
           ?>
           PHP;
    });

    Blade::directive('svg', function ($arguments) {
        return "<?php 
            \$args = [$arguments];
            \$name = \$args[0] ?? '';
            
            \$path = resource_path(\"svg/{\$name}.svg\");
            
            if (file_exists(\$path)) {
                \$svg = file_get_contents(\$path);
                echo \$svg;
            }
        ?>";
    });
  }
  public function bootDynamicConfigSmtp()
  {
    if ( !Schema::hasTable('smtpsettings')) {
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
    if ( !Schema::hasColumn('auth_security_rules', 'require_captcha')) {
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
