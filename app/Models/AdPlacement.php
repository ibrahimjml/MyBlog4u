<?php

namespace App\Models;

use App\Enums\Adplacements\AdPosition;
use App\Enums\Adplacements\AdStatus;
use App\Enums\Adplacements\AdType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AdPlacement extends Model
{
  use HasFactory;
  protected $fillable = [
    'ad_name',
    'ad_type',
    'image_path',
    'link_url',
    'ad_position',
    'ad_dimension',
    'ad_code',
    'status',
  ];
  protected $casts = [
    'status' => AdStatus::class,
    'ad_type' => AdType::class,
    'ad_position' => AdPosition::class,
  ];
  public function scopeActive($query)
  {
    return $query->where('status', AdStatus::ACTIVE->value);
  }
  public function getImageUrlAttribute()
  {
    return $this->image_path ? Storage::disk(media_driver())->url($this->image_path) : null;
  }
}
