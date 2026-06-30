<?php

namespace App\Observers;


use App\Actions\DeleteEmbeddedPostAction;
use App\Actions\DeletePostImageAction;
use App\Actions\DeletePostNotificationsAction;
use App\Models\Post;
use App\Services\ClearCacheService;
use Illuminate\Support\Str;
use App\Traits\SluggableTrait;

class PostObserver
{
    use SluggableTrait;
    public function creating(Post $post)
    {
            $slug = Str::slug($post->title);
            $post->slug = $this->create($slug);
    }
    public function created(Post $post)
    {
       app(ClearCacheService::class)->clearPostCaches($post);
    }
    public function updating(Post $post)
    {
      if($post->isDirty('title')){
        $slug = Str::slug($post->title);
        $post->slug = $this->create($slug);
      }
    }
    public function updated(Post $post)
    {
       app(ClearCacheService::class)->clearPostCaches($post);
    }
    public function deleting(Post $post)
    {
        app(DeletePostImageAction::class)->execute($post);

        app(DeletePostNotificationsAction::class)->execute($post);
  
        app(DeleteEmbeddedPostAction::class)->execute($post);
   }

   public function deleted(Post $post)
   { 
       app(ClearCacheService::class)->clearPostCaches($post);
   }
    
}
