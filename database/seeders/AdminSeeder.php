<?php

namespace Database\Seeders;

use App\Enums\NotificationType;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {

    $modules = [
      'User'              => ['Access', 'user.view', 'user.create', 'user.delete', 'user.block', 'user.role', 'user.updateImage', 'user.deleteSocial', 'user.update', 'user.edit'],
      'Posts'             => ['post.view', 'post.update', 'post.feature', 'post.delete'],
      'Post Moderation'   => ['postmoderation.view', 'postmoderation.update'],
      'Tags'              => ['tag.create', 'tag.view', 'tag.update', 'tag.delete', 'tag.feature'],
      'Categories'        => ['category.create', 'category.view', 'category.update', 'category.delete', 'category.feature'],
      'Comments'          => ['comment.edit', 'comment.delete'],
      'Post Reports'      => ['postreport.view', 'postreport.delete', 'postreport.status'],
      'Profile Reports'   => ['profilereport.view',  'profilereport.delete', 'profilereport.status'],
      'Comment Reports'   => ['commentreport.view', 'commentreport.delete', 'commentreport.status'],
      'Roles'             => ['role.create', 'role.view', 'role.update', 'role.delete'],
      'Permissions'       => ['permission.create',  'permission.view',  'permission.update', 'permission.delete'],
      'Slides'            => ['slide.create',  'slide.view',  'slide.update', 'slide.delete'],
      'Notifications'     => ['notifications.view', 'notifications.update'],
      'Ads'               => ['ad.create', 'ad.view', 'ad.update', 'ad.delete'],
      'Api Rate Limit'    => ['limit.view','limit.create', 'limit.update', 'limit.delete'],
      'Seo Tools'         => ['seo.view', 'seo.update'],
      'SMTP'              => ['smtp.view', 'smtp.update'],
      'Auth and Security' => ['authsecurity.view', 'authsecurity.update'],
      'Maintenance'       => ['maintenance.view', 'maintenance.update'],
      'Custom Pages'      => ['custompage.view', 'custompage.create', 'custompage.update', 'custompage.delete'],
      'Backups'           => ['backup.view', 'backup.download', 'backup.delete'],
    ];

    foreach ($modules as $name => $permssions) {
      foreach ($permssions as $permission) {
        Permission::firstOrCreate(
          ['name' => $permission],
           [
            'module' => $name,
            'slug' => Str::slug($permission)
          ]
        );
      }
    }

    foreach (\App\Enums\UserRole::cases() as $role) {
      Role::firstOrCreate(['name' => $role->value]);
    }
    $adminrole = Role::where('name', \App\Enums\UserRole::ADMIN->value)->first();
    $permissions = Permission::pluck('id');
    $adminrole->permissions()->sync($permissions);

    $admin =  User::create([
      'name' => 'admin',
      'username' => 'admin123',
      'email' => env('ADMIN_EMAIL'),
      'password' => Hash::make(env('ADMIN_PASS')),
      'is_admin' => 1,
      'avatar' => 'default.jpg',
      'cover_photo' => 'sunset.jpg',
      'created_at' => Carbon::now(),
      'email_verified_at' => now()
    ]);
    $admin->profile()->create([
      'is_public' => true,
    ]);
    $admin->roles()->syncWithoutDetaching([$adminrole->id]);
    
    foreach(NotificationType::cases() as $type){
      $admin->adminNotificationSettings()->create([
        'type' => $type->value,
        'is_enabled' => true
      ]);
    }
  }
}
