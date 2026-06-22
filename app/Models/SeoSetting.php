<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SeoSetting extends Model
{
  use HasFactory;
  protected $fillable = [
    'app_name',
    'favicon_path',
    'meta_title',
    'meta_description',
    'meta_keywords',
    'meta_author',
    'meta_og_image',
    'header_scripts',
    'footer_scripts'
  ];
  protected $casts = [
    'meta_keywords' => 'array',
    'header_scripts' => 'string',
    'footer_scripts' => 'string',
  ];

  public function setAppNameAttribute($value)
  {
    $this->attributes['app_name'] = $value ?? config('app.name');
  }

   public function getFaviconUrlAttribute(): string
    {
        if (!empty($this->favicon_path)) {
            return Storage::disk(media_driver())->url('img/' . $this->favicon_path);
        }

        return asset('img/icon.png');
    }

    public function getMetaOgImageUrlAttribute(): string
    {
        if (!empty($this->meta_og_image)) {
            return Storage::disk(media_driver())->url('img/' . $this->meta_og_image);
        }

        return asset('img/logo2.png');
    }
}
