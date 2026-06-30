<?php

namespace App\Actions;

use App\Models\Hashtag;
use App\Models\Post;

class AttachPostTagsAction
{
  public function attachTag(Post $post, string $hashtag)
  {
    $tags = explode(',', $hashtag);

    foreach ($tags as $tag) {
      $tag = strip_tags(trim($tag));

      if ($tag) {
        $hashtag = Hashtag::firstOrCreate(['name' => $tag]);

        if ($hashtag->status === \App\Enums\TagStatus::ACTIVE) {
          $post->hashtags()->attach($hashtag->id);
        }
      }
    }
  }
}
