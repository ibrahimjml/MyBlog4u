<?php
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\{
    DashboardController,
    PinController,
    ProfileSettingsController,
  ProfileController,
  QrcodeController,
};
use App\Http\Controllers\{
    CategoryController,
    CommentController,
    Hashtagcontroller,
    HomeController,
    NotificationController,
    PostController,
    ReportPostController,
    PublicController,
    ReportCommentController,
    ReportProfileController,
    TinyMCEController,
};
use App\Http\Controllers\Admin\CustomPageController;
use App\Http\Controllers\Auth\TwoFactorController;

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
Route::get('/',HomeController::class)->name('home');
// sitemap
Route::get('/sitemap.xml',SitemapController::class)->name('sitemap');
// Blog
Route::get('/blog',[PostController::class,'blogpost'])->name('blog');
// Post
Route::get('/post/{post:slug}',[PostController::class,'viewPost'])->name('single.post');

//recent posts page by hashtag 
Route::get('/hashtag/{hashtag:name}',[Hashtagcontroller::class,'viewhashtag'])->name('viewhashtag');
//recent posts page by category
Route::get('/category/{category:name}',[CategoryController::class,'viewcategory'])->name('viewcategory');
// custom pages
Route::get('/c/{page:slug}', [CustomPageController::class, 'show'])->name('custom.page');
// Create Post
Route::get('/createpage',[PostController::class,'createpage'])->name('createpage');
Route::post('/create',[PostController::class,'create'])->name('create');
Route::get('/post/edit/{slug}',[PostController::class,'editpost'])->name('edit.post');
Route::delete('/post/{slug}',[PostController::class,'delete'])->name('delete.post');
Route::put('/post/update/{slug}',[PostController::class,'update'])->name('update.post');

// Rich editor handle upload
Route::post('/upload-image', [TinyMCEController::class, 'uploadImage'])->name('tinymce.upload');
Route::post('/image-delete', [TinyMCEController::class, 'deleteImage'])->name('tinymce.delete');
// user dashboard
Route::controller(DashboardController::class)
       ->prefix('dashboard')
       ->name('dashboard.')->group(function(){
Route::get('/', 'overview')->name('index');
Route::get('/posts', 'posts')->name('posts');
Route::get('/followers', 'followers')->name('followers');
Route::get('/pending-followers', 'pending_followers')->name('pending.followers');
Route::get('/followings', 'followings')->name('followings');
});
// profile
Route::controller(ProfileController::class)
    ->prefix('/@{user:username}')
    ->middleware('can:view,user')
    ->group(function(){

 Route::get('/','Home')->name('profile');
 Route::get('activity','activity')->name('profile.activity');
 Route::get('/about','aboutme')->name('profile.aboutme');
 
});
// settings
Route::prefix('profile')
->controller(ProfileSettingsController::class)
->middleware('password.confirm')
->group(function(){

  Route::get('/settings/info','profile_info')->name('profile.info');
  Route::get('/settings/account','profile_account')->name('profile.account');
  Route::get('/settings/privacy','account_privacy')->name('account.privacy');
  Route::get('/settings/security','two_factor_view')->name('two.factor.view');
  Route::post('/settings/privacy','profile_visibility')->name('profile.visibility');
  Route::put('/settings/info','update_info')->name('update.info');
  Route::put('/settings/account','update_account')->name('update.account');
  Route::delete('/account-delete','deleteaccount')->name('account.delete');
  Route::delete('/settings/delete/avatar','delete_avatar')->name('avatar.destroy');
  Route::delete('/settings/delete/cover','delete_cover')->name('cover.destroy');
  Route::delete('/delete/custom-link/{id}',  'destroy_link')->name('destroy.customlink');
  // two factor management
  Route::controller(TwoFactorController::class)->group(function(){
    Route::post('/enable-2fa','enable2fa')->name('enable.2fa');
    Route::post('/confirm-2fa','confirmTwofactor')->name('confirm.2fa');
    Route::put('/disable-2fa', 'disable2fa')->name('disable.2fa');
    Route::get('/download-recovery-codes',  'downloadRecoveryCodes')->name('download.recovery.codes');
    Route::put('/regenerate-recovery-codes',  'regenerate')->name('regenerate.recovery.codes');
  });
});
// qrcode generator
Route::get('/qr-code', QrcodeController::class)->name('qr-code.image');
// toggle pin/unpin
Route::post('/toggle/{post}/pin',[PinController::class,'togglePin'])->name('toggle.pin');
// Like
Route::post('/post/{post}/like',[PublicController::class,'like']);
// Follow
Route::post('/user/{user}/togglefollow',[PublicController::class,'toggleFollow'])->name('toggle.follow');
Route::post('/follow-request/accept/{follower}', [PublicController::class, 'accept'])->name('follow.accept');
Route::delete('/follow-request/reject/{follower}', [PublicController::class, 'reject'])->name('follow.reject');
Route::delete('/follower/remove/{follower}', [PublicController::class, 'removeFollower'])->name('follower.remove');
Route::post('/following/unfollow/{user}', [PublicController::class,'unfollow'])->name('following.unfollow');

// Search
Route::get('/search',[PostController::class,'search'])->name('blog.search');
// Comments
Route::get('/posts/{post}/comments', [CommentController::class, 'loadMore'])->name('posts.comments.load');
Route::post('/comment/{post}',[CommentController::class,'createComment']);
Route::post('/reply/{comment}',[CommentController::class,'reply'])->name('comment.reply');
Route::put('/comment/edit/{comment}',[CommentController::class,'editComment'])->name('edit.comment');
Route::delete('/comment/{comment}',[CommentController::class,'deleteComment'])->name('delete.comment');
// mention suer
Route::get('/users/search', [CommentController::class, 'search_mentioned']);
// reports
Route::prefix('/reports')->group(function(){
  Route::post('/post/{post}',[ReportPostController::class,'report_post'])->name('post.report');
  Route::post('/profile/{user:username}',[ReportProfileController::class,'report_profile'])->name('profile.report');
  Route::post('/comment/{comment}',[ReportCommentController::class,'report_comment'])->name('comment.report');
});
// Save Post
Route::post('/saved-post',[PublicController::class,'save']);
// Saved Posts Page
Route::get('/bookmarks',[PublicController::class,'getsavedposts'])->name('bookmarks');
// notifications
Route::get('/notifications/{id}/read', [NotificationController::class, 'markasread'])->name('notifications.read');
Route::get('/notifications/read/all', [NotificationController::class, 'markallasread'])->name('notifications.readall');
Route::delete('/notifications/{id}/delete', [NotificationController::class, 'delete'])->name('notifications.delete');
Route::delete('/notifications/deleteAll', [NotificationController::class, 'deleteAll'])->name('notifications.deleteAll');
