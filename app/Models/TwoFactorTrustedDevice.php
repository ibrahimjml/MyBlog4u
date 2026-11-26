<?php

namespace App\Models;

use App\Support\TrustedDeviceTokenSupport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwoFactorTrustedDevice extends Model
{
  use HasFactory;

  protected $table = 'two_factor_trusted_devices';
  
  protected $fillable = [
    'user_id',
    'token_hash',
    'device_name',
    'user_agent',
    'ip',
    'last_used_at',
    'expires_at',
  ];
  
  protected $hidden = [
     'token_hash'
  ];

  protected $casts = [
    'last_used_at' => 'datetime',
    'expires_at' => 'datetime',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function isStale(): bool
  {
     return ! $this->last_used_at || $this->last_used_at->lt(now()->subDay());
  }

  public static function findValidForUser(User $user, string $selector, string $validator): ?self
  {
      return $user->twoFactorTrustedDevice()
          ->where('token_hash', TrustedDeviceTokenSupport::hash($selector, $validator))
          ->where('expires_at', '>', now())
          ->first();
  }
}
