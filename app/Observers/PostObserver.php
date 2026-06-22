<?php

namespace App\Observers;


use App\Models\Post;
use App\Notifications\FollowingPostCreatedNotification;
use App\Services\ClearCacheService;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
      // clear all post cache and comment   
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
        // clear all post cache and comment   
       app(ClearCacheService::class)->clearPostCaches($post);
    }
    public function deleting(Post $post)
    {
        // Delete old image when post deleted
        if (!empty($post->image_path)) {
            $oldimage = 'uploads/' . $post->image_path;
            Log::info('Trying to delete old image: ' . $oldimage);
    
            if (Storage::disk(media_driver())->exists($oldimage)) {
              Storage::disk(media_driver())->delete($oldimage);
                Log::info('image deleted : '.$post->image_path);
            } else {
                Log::warning('old image not found: ');
            }
        }

        // auto delete post notification 
        DatabaseNotification::where('type',FollowingPostCreatedNotification::class)
        ->whereJsonContains('data->postedby_id',$post->user->id)
        ->whereJsonContains('data->post_id',$post->id)
        ->delete();


        // delete images inside tinyMCE
        $html = $post->description;
        if (!empty($html)) {
          libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML($html);
    
        $images = $dom->getElementsByTagName('img');
    
        foreach ($images as $img) {
            $src = $img->getAttribute('src');
            Log::info('Found TinyMCE image src: ' . $src);

            $path = ltrim(parse_url($src, PHP_URL_PATH), '/');

            if(media_driver() === 'public'){
              $path = str_replace('/storage/', 'public/', parse_url($src, PHP_URL_PATH));
            }

            if (Storage::disk(media_driver())->exists($path)) {
                Storage::disk(media_driver())->delete($path);
                Log::info('Found TinyMCE image src: ' . $src .'deleted');
            }
            Log::warning('Not Found TinyMCE image');
        }
    
        libxml_clear_errors();
      }
   }

   public function deleted(Post $post)
   {
      // clear all post cache and comment   
       app(ClearCacheService::class)->clearPostCaches($post);
   }
    
}
