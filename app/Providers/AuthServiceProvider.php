<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use App\Models\SocialLink;
use App\Models\User;
use App\Policies\CommentPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\PostPolicy;
use App\Policies\SocialLinkPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
      Post::class=>PostPolicy::class,
      User::class=>UserPolicy::class,
      Comment::class=>CommentPolicy::class,
      SocialLink::class=>SocialLinkPolicy::class,
      Notification::class=>NotificationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        
        Gate::define("makeAdminActions", function ($user) {
          return  $user->hasPermission('Access') || $user->email === 'admin@mail.ru';
      });

  
    }
}
