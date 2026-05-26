<?php

namespace App\Models;

use App\Enums\CustomPageStatus;
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
        static::creating(function ($page) {
            $page->slug = Str::slug($page->title);
        });
        static::updating(function ($page) {
            if ($page->isDirty('title')) {
                $page->slug = Str::slug($page->title);
            }
        });
    }
}
