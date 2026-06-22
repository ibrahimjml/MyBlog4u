<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
  use HasFactory;
  protected $guarded = [];
  public static function get(string $key, $default = null)
  {
    return static::query()
      ->where('key', $key)
      ->value('value')
      ?? $default;
  }
  public static function booted()
  {
    $clearCache = fn() => Cache::forget('app.settings.cache');
    static::created($clearCache);
    static::updated($clearCache);
    static::deleted($clearCache);
  }
}
