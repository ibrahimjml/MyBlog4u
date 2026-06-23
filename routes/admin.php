<?php

use App\Http\Controllers\Admin\{ AdminController, CategoriesController, CommentReportController, CustomPageController, NotificationsController, TagsController, PermissionsController, PostReportController, PostsController, ProfileReportController, RolesController, SlidesController, UsersController };
use App\Http\Controllers\Admin\Ads\AdController;
use App\Http\Controllers\Admin\Analytics\AnalyticsController;
use App\Http\Controllers\Admin\ApiRateLimit\ApiRateLimitController;
use App\Http\Controllers\Admin\MediaSettingController;
use App\Http\Controllers\Admin\Optimization\MaintenanceController;
use App\Http\Controllers\Admin\Optimization\SeoController;
use App\Http\Controllers\Admin\PostModeration\PostModerationController;
use App\Http\Controllers\Admin\Settings\{ AuthSecurityController, BackupsController, ManageNotificationController, SmtpController };
use Illuminate\Support\Facades\Route;
Route::prefix('admin')
    ->middleware(['can:makeAdminActions','demo'])
    ->name('admin.')
    ->group(function () {
  
  Route::get('/panel', [AdminController::class,'admin'])->name("panel");
  // posts
  Route::controller(PostsController::class)
         ->prefix('posts')
         ->name('posts.')
         ->group(function () {
  Route::get('/', 'posts')->name('page');
  Route::get('/featured', 'featuredPage')->name('featured.page');
  Route::post('/featured', 'createFeature')->name('featured.create');
  Route::put('/{post}/feature','toggleFeature')->name('featured.toggle');
  Route::put('/{post}/status','editStatus')->name('status');
  });
  // post moderation
  Route::controller(PostModerationController::class)
         ->prefix('post-moderation')
         ->name('posts.moderation.')
         ->group(function () {
  Route::get('/', 'moderationPage')->name('index');
  Route::put('/update-rules', 'updateRules')->name('update.rules');
  });
  // custom pages
  Route::controller(CustomPageController::class)
        ->prefix('custom-pages')
        ->name('custom-pages.')
        ->group(function () {
  Route::get('/', 'index')->name('index');
  Route::get('/create', 'create')->name('create');
  Route::post('/create', 'store')->name('store');
  Route::get('/{page}/edit', 'edit')->name('edit');
  Route::put('/{page}/edit', 'update')->name('update');
  Route::delete('/{page}/delete', 'destroy')->name('delete');
  });
  // users
  Route::controller(UsersController::class)
        ->prefix('users')
        ->name('users.')
        ->group(function () {
  Route::get('/', 'users')->name('page');
  Route::post('/create','createUser')->name('create');
  Route::put('/{user}/edit','updateUser')->name('update');
  Route::put('/{user}/role', 'role')->name('role');
  Route::patch('/{user}/activate', 'activateUser')->name('activate');
  Route::put('/{user}/toggle', 'toggle')->name('block');
  Route::delete('/{user}/delete', 'destroy')->name('delete');
  });
  // reports
  Route::prefix('reports')->group(function () {
  Route::controller(PostReportController::class)
         ->prefix('posts')
        ->name('reports.posts.')
        ->group(function(){
    Route::get('/', 'reports')->name('index');
    Route::delete('/{report}/delete', 'delete')->name('delete');
    Route::patch('/{report}/status','status')->name('status');
  });
   Route::controller(ProfileReportController::class)
        ->prefix('profiles')
        ->name('reports.profiles.')
        ->group(function(){
  Route::get('/', 'reports')->name('index');
  Route::delete('/{report}/delete', 'delete')->name('delete');
  Route::patch('/{report}/status','status')->name('status');
});
   Route::controller(CommentReportController::class)
        ->prefix('comments')
        ->name('reports.comments.')
       ->group(function(){
  Route::get('/', 'reports')->name('index');
  Route::delete('/{report}/delete', 'delete')->name('delete');
  Route::patch('/{report}/status','status')->name('status');
});
    });
// manage Tags
Route::controller(TagsController::class)
      ->prefix('tags')
      ->name('tags.')
      ->group(function(){
Route::get('/', 'hashtags')->name('index');
Route::post('/tag', 'create')->name('create');
Route::put('/edit/{hashtag}', 'edit')->name('update');
Route::delete('/{hashtag}', 'delete')->name('delete');
Route::put('/{hashtag}/feature','toggle')->name('feature');
});
// Categories 
Route::controller(CategoriesController::class)
        ->prefix('categories')
        ->name('categories.')
        ->group(function(){
Route::get('/','categories')->name('index');
Route::post('/category', 'create')->name('create');
Route::put('/edit/{category}', 'edit')->name('update');
Route::delete('/delete/{category}', 'delete')->name('delete');
Route::put('/{category}/feature','toggle')->name('feature');
  });
  // notifications
  Route::controller(NotificationsController::class)
            ->prefix('notifications')
            ->name('notifications.')
            ->group(function(){
  Route::get('/', 'notifications')->name('index');
  Route::get('/notifications/{id}/read',  'markasread')->name('read');
  });
  // roles and permissions
  Route::resource('roles',RolesController::class);
  Route::resource('permissions',PermissionsController::class);
  //slides
  Route::resource('slides',SlidesController::class)->except(['create','edit']);
  // system settings
  Route::prefix('settings')->name('settings.')->group(function(){
  // auth and security 
  Route::controller(AuthSecurityController::class)->group(function(){
  Route::get('/auth-settings', 'auth_settings')->name('auth.index');
  Route::put('/auth-settings', 'update_auth_settings')->name('auth.update');
  });
  // media settings
  Route::prefix('media')->controller(MediaSettingController::class)->name('media.')->group(function(){
  Route::get('/','index')->name('index');
  Route::put('/update','mediaSettingUpdate')->name('update');
  });
  // backups 
  Route::controller(BackupsController::class)->group(function(){
  Route::get('db-backup','backup_view')->name('backup.view');
  Route::get('backup-download/{file}','backup_download')->name('backup.download');
  Route::delete('/backup-delete/{file}','backup_destroy')->name('backup.destroy');
  });
  // manage notifications
  Route::controller(ManageNotificationController::class)->group(function(){
  Route::get('/notification-controls','notification_view')->name('notification.view');
  Route::patch('/notification-controls','toggle_notification')->name('notification.toggle');
  });
  // smtp mail
  Route::controller(SmtpController::class)->group(function(){
  Route::get('/smtpmails','smtp')->name('smtp');
  Route::post('/smtpmails','smtp_config')->name('smtp.config');
  Route::post('/testmail','testmail')->name('smtp.test');
  });
    });
    // Analytics
    Route::controller(AnalyticsController::class)->prefix('analytics')->name('analytics.')->group(function(){
    Route::get('/','index')->name('index');
    Route::put('/update','updateAnalytics')->name('update');
    Route::post('/json','jsonUploadFile')->name('json');
    });
    // Ads management
    Route::prefix('ads')->controller(AdController::class)->name('ads.')->group(function(){
      Route::get('/', 'index')->name('index');
      Route::post('/create', 'store')->name('store');
      Route::put('/{ad}/update', 'update')->name('update');
      Route::patch('/{ad}/status', 'toggleStatus')->name('toggle.status');
      Route::delete('/{ad}/delete', 'destroy')->name('destroy');
    });
  // API rate limits
  Route::prefix('api-limits')->controller(ApiRateLimitController::class)->name('api-limits.')->group(function(){
    Route::get('/', 'index')->name('index');
    Route::post('/create', 'store')->name('store');
    Route::put('/{limit}/update', 'update')->name('update');
    Route::patch('/{limit}/status', 'toggleStatus')->name('toggle.status');
    Route::delete('/{limit}/delete', 'destroy')->name('destroy');
  });
  // optimization
  Route::prefix('optimize')->controller(MaintenanceController::class)->name('optimize.')->group(function(){
  Route::get('/maintenance','maintenance_page')->name('maintenance');
  Route::post('/run','run_artisans')->name('run');
  });
  Route::prefix('seo')->controller(SeoController::class)->name('seo.')->group(function(){
    Route::get('/', 'index')->name('index');
    Route::put('/update', 'update')->name('update');
  });
  
});
