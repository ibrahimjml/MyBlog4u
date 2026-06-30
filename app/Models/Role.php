<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Role extends Model
{
    use HasFactory;
     protected $fillable = ['name'];
     public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
      protected static function booted()
    {
        $clearCaches = fn() => Cache::tags(['user_permissions','has_any_role'])->flush();
  
        static::created($clearCaches);
        static::updated($clearCaches);
        static::deleted($clearCaches);
    }
  }

