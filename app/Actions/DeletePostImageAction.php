<?php

namespace App\Actions;

use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeletePostImageAction
{
    public function execute(Post $post)
    {
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
    }
}
