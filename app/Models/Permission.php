<?php

namespace App\Models;

use App\Builders\PermissionBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Permission extends Model
{
    use HasFactory;
     protected $fillable = ['name','slug','module','description'];

      public function newEloquentBuilder($query)
      {
          return new PermissionBuilder($query);
      }
      protected static function booted()
    {
        $clearCaches = fn() => Cache::tags(['user_permissions','has_any_role'])->flush();
        static::creating(function ($permission) {
            $permission->slug = Str::slug($permission->name);
        });
        static::created($clearCaches);
        static::updated($clearCaches);
        static::deleted($clearCaches);
    }
     public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
   
    public function users()
    {
      return $this->belongsToMany(User::class,'permission_user');
    }
}
