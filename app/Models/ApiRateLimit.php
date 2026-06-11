<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ApiRateLimit extends Model
{
    use HasFactory;
    protected $table = 'api_rate_limits';
    protected $fillable = [
        'route_name',
        'max_attempts',
        'time_window',
        'description',
        'status',
        'method',
    ];
    protected $casts = [
        'status' => \App\Enums\ApiLimits\ApiLimitStatus::class,
        'method' => \App\Enums\ApiLimits\ApiLimitMethod::class,
    ];
    protected $attributes = [
        'status' => \App\Enums\ApiLimits\ApiLimitStatus::ACTIVE->value,
        'method' => \App\Enums\ApiLimits\ApiLimitMethod::ALL->value,
    ];

    protected static function booted()
    {
       $clearCache = fn() => Cache::forget('api_rate_limits.active');
       static::created($clearCache);
       static::updated($clearCache);
       static::deleted($clearCache);

    }
}
