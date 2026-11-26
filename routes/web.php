<?php
use App\Http\Controllers\Admin\CustomPageController;
use App\Http\Controllers\Admin\Scraping\ScrapSettingController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HashtagController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportCommentController;
use App\Http\Controllers\ReportPostController;
use App\Http\Controllers\ReportProfileController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TinyMCEController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\PinController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ProfileSettingsController;
use App\Http\Controllers\User\QrcodeController;
use App\Http\Controllers\User\TrustedDeviceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// HOME
Route::get('/', HomeController::class)->name('home');
// sitemap
Route::get('/{key}.{extension}', SitemapController::class)
    ->where('key', '^'.collect(\App\Services\Sitemap\SitemapManager::getKeys())->map(fn (string $key) => '(?:'.preg_quote($key, '/').')')->implode('|').'$')
    ->whereIn('extension', \App\Services\Sitemap\SitemapManager::allowedExtensions())
    ->name('public.sitemap.index');

// geerate dynamic image for post cover/og
Route::get('/post/{type}/{post:slug}.png', [PostController::class, 'generateCover'])
    ->whereIn('type', ['cover', 'og'])
    ->name('posts.cover');
// Blog
Route::get('/blog', [PostController::class, 'blogpost'])->name('blog');
// Post
Route::get('/post/{post:slug}', [PostController::class, 'viewPost'])->name('single.post');
//recent posts page by hashtag
Route::get('/hashtag/{hashtag:name}', HashtagController::class)->name('viewhashtag');
//recent posts page by category
Route::get('/category/{category:name}', CategoryController::class)->name('viewcategory');
// latest scraped news
Route::get('/discover-news', NewsController::class)->name('news');
// scrap cron run
Route::get('/scraping/cron/{token}', [ScrapSettingController::class, 'cronRun'])->name('cron.run');
// custom pages
Route::get('/c/{page:slug}', [CustomPageController::class, 'show'])->name('custom.page');
// Create Post
Route::get('/createpage', [PostController::class, 'createpage'])->name('createpage');
Route::post('/create', [PostController::class, 'create'])->name('create');
Route::get('/post/edit/{slug}', [PostController::class, 'editpost'])->name('edit.post');
Route::delete('/post/{slug}', [PostController::class, 'delete'])->name('delete.post');
Route::put('/post/update/{slug}', [PostController::class, 'update'])->name('update.post');

// Rich editor handle upload
Route::post('/upload-image', [TinyMCEController::class, 'uploadImage'])->name('tinymce.upload');
Route::post('/image-delete', [TinyMCEController::class, 'deleteImage'])->name('tinymce.delete');
// user dashboard
Route::controller(DashboardController::class)
    ->prefix('dashboard')
    ->name('dashboard.')->group(function () {
        Route::get('/', 'overview')->name('index');
        Route::get('/posts', 'posts')->name('posts');
        Route::get('/followers', 'followers')->name('followers');
        Route::get('/pending-followers', 'pending_followers')->name('pending.followers');
        Route::get('/followings', 'followings')->name('followings');
    });
// profile
Route::controller(ProfileController::class)
    ->prefix('/@{user:username}')
    ->name('profile.')
    ->group(function () {

        Route::get('/', 'Home')->name('home');
        Route::get('activity', 'activity')->name('activity');
        Route::get('/about', 'aboutme')->name('aboutme');

    });
// settings
Route::prefix('settings')
     ->controller(ProfileSettingsController::class)
     ->middleware('password.confirm')
     ->group(function(){

  Route::get('info','profile_info')->name('info');
  Route::get('account','profile_account')->name('account');
  Route::get('privacy','account_privacy')->name('account.privacy');
  Route::get('security','two_factor_view')->name('two.factor.view');
  Route::get('sessions', 'active_sessions')->name('active.sessions');
  Route::delete('sessions/{activeSession}', 'logout_session')->name('active.sessions.destroy');
  Route::post('privacy','profile_visibility')->name('profile.visibility');
  Route::put('info','update_info')->name('update.info');
  Route::put('account','update_account')->name('update.account');
  Route::delete('account-delete','deleteaccount')->name('account.delete');
  Route::delete('delete/avatar','delete_avatar')->name('avatar.destroy');
  Route::delete('delete/cover','delete_cover')->name('cover.destroy');
  Route::delete('delete/custom-link/{id}',  'destroy_link')->name('destroy.customlink');
  // two factor management
  Route::controller(TwoFactorController::class)
        ->group(function(){
    Route::post('/enable-2fa','enable2fa')->name('enable.2fa');
    Route::post('/confirm-2fa','confirmTwofactor')->name('confirm.2fa');
    Route::put('/disable-2fa', 'disable2fa')->name('disable.2fa');
    Route::get('/download-recovery-codes',  'downloadRecoveryCodes')->name('download.recovery.codes');
    Route::put('/regenerate-recovery-codes',  'regenerate')->name('regenerate.recovery.codes');
  });
  // two factor trusted devices
  Route::controller(TrustedDeviceController::class)
        ->group(function () {
    Route::get('trusted-devices','index')->name('trusted-devices.index');
    Route::delete('trusted-devices/{device}', 'destroy')->name('trusted-devices.destroy');
    Route::delete('trusted-devices', 'destroyAll')->name('trusted-devices.destroy-all');
  });
});
// qrcode generator
Route::get('/qr-code', QrcodeController::class)->name('qr-code.image');
// toggle pin/unpin
Route::post('/toggle/{post}/pin', [PinController::class, 'togglePin'])->name('toggle.pin');
// Like
Route::post('/post/{post}/like', [PublicController::class, 'like']);
// Follow
Route::post('/user/{user}/togglefollow', [PublicController::class, 'toggleFollow'])->name('toggle.follow');
Route::post('/follow-request/accept/{follower}', [PublicController::class, 'accept'])->name('follow.accept');
Route::delete('/follow-request/reject/{follower}', [PublicController::class, 'reject'])->name('follow.reject');
Route::delete('/follower/remove/{follower}', [PublicController::class, 'removeFollower'])->name('follower.remove');
Route::post('/following/unfollow/{user}', [PublicController::class, 'unfollow'])->name('following.unfollow');

// Search
Route::get('/search', [PostController::class, 'search'])->name('blog.search');
// Comments
Route::get('/posts/{post}/comments', [CommentController::class, 'loadMore'])->name('posts.comments.load');
Route::post('/comment/{post}', [CommentController::class, 'createComment']);
Route::post('/reply/{comment}', [CommentController::class, 'reply'])->name('comment.reply');
Route::put('/comment/edit/{comment}', [CommentController::class, 'editComment'])->name('edit.comment');
Route::delete('/comment/{comment}', [CommentController::class, 'deleteComment'])->name('delete.comment');
// mention suer
Route::get('/users/search', [CommentController::class, 'search_mentioned']);
// reports
Route::prefix('/reports')->group(function () {
    Route::post('/post/{post}', [ReportPostController::class, 'report_post'])->name('post.report');
    Route::post('/profile/{user:username}', [ReportProfileController::class, 'report_profile'])->name('profile.report');
    Route::post('/comment/{comment}', [ReportCommentController::class, 'report_comment'])->name('comment.report');
});
// Save Post
Route::post('/saved-post', [PublicController::class, 'save']);
// Saved Posts Page
Route::get('/bookmarks', [PublicController::class, 'getsavedposts'])->name('bookmarks');
// notifications
Route::get('/notifications/{id}/read', [NotificationController::class, 'markasread'])->name('notifications.read');
Route::get('/notifications/read/all', [NotificationController::class, 'markallasread'])->name('notifications.readall');
Route::delete('/notifications/{id}/delete', [NotificationController::class, 'delete'])->name('notifications.delete');
Route::delete('/notifications/deleteAll', [NotificationController::class, 'deleteAll'])->name('notifications.deleteAll');
Route::get('/notification/render/{notification}', [NotificationController::class, 'render'])->name('render');
