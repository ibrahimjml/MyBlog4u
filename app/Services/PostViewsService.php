<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostView;

class PostViewsService
{
    public function getViews(Post $post,){
      if(!auth()->check()) return;
      $viewer = auth()->user();
      $poster = $post->user_id;
      if($viewer->id === $poster || $viewer->is_admin) return;

      $createview = PostView::firstOrCreate([
          'post_id' => $post->id,
          'viewer_id' => $viewer->id
      ]);
      if($createview->wasRecentlyCreated)
      {
        $post->increment('views');
      }
    }
}
