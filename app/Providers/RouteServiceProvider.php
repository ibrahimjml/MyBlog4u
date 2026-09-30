<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
            Route::middleware('web')
                ->group(base_path('routes/auth.php'));
            Route::middleware('web')
                ->group(base_path('routes/health.php'));
            Route::middleware(['web','can:makeAdminActions','demo'])
                  ->prefix('admin')
                  ->as('admin.')
                ->group(function () {
                  require base_path('routes/admin/admin.php');
                  require base_path('routes/admin/ads-routes.php');
                  require base_path('routes/admin/categories-routes.php');
                  require base_path('routes/admin/custom-pages-routes.php');
                  require base_path('routes/admin/google-analytics-routes.php');
                  require base_path('routes/admin/notifications-routes.php');
                  require base_path('routes/admin/optimization-routes.php');
                  require base_path('routes/admin/post-moderation-routes.php');
                  require base_path('routes/admin/posts-routes.php');
                  require base_path('routes/admin/rate-limit-routes.php');
                  require base_path('routes/admin/reports-routes.php');
                  require base_path('routes/admin/roles-permissions-routes.php');
                  require base_path('routes/admin/seo-routes.php');
                  require base_path('routes/admin/settings-routes.php');
                  require base_path('routes/admin/system-routes.php');
                  require base_path('routes/admin/slides-routes.php');
                  require base_path('routes/admin/tags-routes.php');
                  require base_path('routes/admin/users-routes.php');
                  require base_path('routes/admin/web-scraping-routes.php');
                });    
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
