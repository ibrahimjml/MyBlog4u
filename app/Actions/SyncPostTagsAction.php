<?php

namespace App\Actions;

use App\Models\Hashtag;
use App\Models\Post;

class SyncPostTagsAction
{
  public function syncTag(Post $post, ?string $hashtag)
  {
    if (!empty($hashtag)) {
      $hashtags = array_unique(array_filter(array_map('trim', explode(',', $hashtag))));
      $hashtagIds = [];

      foreach ($hashtags as $name) {
        $hashtag = Hashtag::firstOrCreate(['name' => strip_tags(trim($name))]);

        if ($hashtag->status === \App\Enums\TagStatus::ACTIVE) {
          $hashtagIds[] = $hashtag->id;
        }
      }
      $post->hashtags()->sync($hashtagIds);
    } else {
      $post->hashtags()->detach();
    }
  }
}
