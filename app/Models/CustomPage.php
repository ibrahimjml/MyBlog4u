<?php

namespace App\Models;

use App\Enums\CustomPageStatus;
use Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomPage extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_active',
        'order',
        'show_in_footer'
    ];
    protected $casts = [
        'is_active' => CustomPageStatus::class,
        'show_in_footer' => 'boolean',
    ];
    protected static function booted()
    {
        $clearCache = fn() => Cache::forget('custom_pages');
        
        static::creating(function ($page) {
            $page->slug = Str::slug($page->title);
        });
        static::created($clearCache);
        static::updating(function ($page) {
            if ($page->isDirty('title')) {
                $page->slug = Str::slug($page->title);
            }
            });
        static::updated($clearCache);
        static::deleted($clearCache);
    }
}
