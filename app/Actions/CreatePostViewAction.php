<?php

namespace App\Actions;

use App\Models\Post;
use App\Models\PostView;

class CreatePostViewAction
{
  public function handle(Post $post)
  {
    // guest mode increment loop
    if (!auth()->check()) {
      $post->increment('views');
      return;
    }
    ;
    // auth users increment once
    $viewer = auth()->user();
    $poster = $post->user_id;
    if ($viewer->id === $poster || $viewer->is_admin)
      return;

    $createview = PostView::firstOrCreate([
      'post_id' => $post->id,
      'viewer_id' => $viewer->id
    ]);
    if ($createview->wasRecentlyCreated) {
      $post->increment('views');
    }
  }
}
