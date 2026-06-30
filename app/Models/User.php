<?php

namespace App\Models;

use App\Builders\UserBuilder;
use App\Enums\FollowerStatus;
use App\Notifications\VerifyEmailQueued;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasPermissionsTrait;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Scout\Searchable;

class User extends Authenticatable implements MustVerifyEmail
{
  use Searchable, HasApiTokens, HasFactory, Notifiable, HasPermissionsTrait;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'username',
    'email',
    'password',
    'has_two_factor_enabled',
    'two_factor_secret',
    'two_factor_recovery_codes',
    'recovery_codes_downloaded',
    'phone',
    'age',
    'avatar',
    'cover_photo',
    'github',
    'linkedin',
    'twitter',
    'bio',
    'is_blocked',
    'aboutme',
    'username_changed_at'
  ];

  public function newEloquentBuilder($query): UserBuilder
  {
    return new UserBuilder($query);
  }
   public function receivesBroadcastNotificationsOn(): string
    {
        return 'notifications.' . $this->id;
    }
  public function sendEmailVerificationNotification()
  {
    $this->notify(new VerifyEmailQueued);
  }
  public function activation()
  {
    return $this->hasOne(Activation::class);
  }
  public function scopeActivated($query)
  {
    return $query->whereHas('activation', function ($q) {
      $q->where('completed', true);
    });
  }
  public function post()
  {
    return $this->hasMany(Post::class);
  }

  public function identityVerification()
  {
    return $this->hasOne(IdentityVerification::class);
  }
  public function profile(): HasOne
  {
    return $this->hasOne(Profile::class);
  }
  public function likes()
  {
    return $this->hasMany(Like::class);
  }

  public function comments()
  {
    return $this->hasMany(Comment::class);
  }
  public function replies()
  {
    return $this->comments()->whereNotNull('parent_id');
  }
  public function profileViews()
  {
    return $this->hasMany(ProfileView::class, 'profile_id');
  }
  public function mentioned()
  {
    return $this->belongsToMany(Comment::class, 'comment_mentions');
  }
  public function followings()
  {
    return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id')
      ->withPivot('status')
      ->wherePivot('status', FollowerStatus::ACCEPTED->value);
  }
  public function followers()
  {
    return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id')
      ->withPivot('status')
      ->wherePivot('status', FollowerStatus::ACCEPTED->value);
  }
  public function pendingFollowers()
  {
    return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id')
      ->withPivot('status')
      ->wherePivot('status', FollowerStatus::PENDING->value);
  }
  public function allFollowings()
  {
    return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id')->withPivot('status');
  }
  public function isFollowing(User $user)
  {
    return $this->followings()->where('user_id', $user->id)->exists();
  }
  public function reportsReceived()
  {
    return $this->hasMany(ProfileReport::class, 'profile_id');
  }
  public function reportsSubmitted()
  {
    return $this->hasMany(ProfileReport::class, 'reporter_id');
  }
  public function socialLinks()
  {
    return $this->hasMany(SocialLink::class);
  }
  public function adminNotificationSettings()
  {
    return $this->hasMany(AdminNotificationSetting::class);
  }
  public function getAvatarUrlAttribute()
  {
    return $this->avatar !== "default.jpg"
      ? Storage::url('avatars/' . $this->avatar)
      : Storage::url('avatars/' . 'default.jpg');
  }
  public function getCoverAttribute()
  {
    return $this->cover_photo === 'sunset.jpg'
      ? Storage::url('covers/' . 'sunset.jpg')
      : Storage::url('covers/' . $this->cover_photo);
  }
  protected $hidden = [
    'password',
    'remember_token',
    'two_factor_secret',
    'two_factor_recovery_codes',
  ];


  protected $casts = [
    'email_verified_at' => 'datetime',
    'username_changed_at' => 'datetime',
    'has_two_factor_enabled' => 'boolean',
    'is_blocked' => 'boolean',
    'recovery_codes_downloaded' => 'boolean',
    'age' => 'integer',
  ];
}
