<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckIfBlocked;
use App\Models\Post;

class PinController extends Controller
{
      public function __construct(){
      $this->middleware(['auth','verified',CheckIfBlocked::class]);
    }
    public function togglePin(Post $post){
      $this->authorize('update', $post);

       if ($post->is_pinned) {
        $post->is_pinned = false;
        $post->pinned_at = null;
        $post->save();

        return response()->json([
            'status' => 'unpinned',
            'post_id' => $post->id
        ], 200);
    }
      $pinnedCount = Post::published()
            ->where('user_id', auth()->id())
            ->where('is_pinned', true)
            ->count();

        if ($pinnedCount >= 3) {
          return response()->json([
            'status' => 'limit_reached',
            'message' => 'You can only pin up to 3 posts.'
        ], 422);
        }
      $post->is_pinned = true;
      $post->pinned_at = now();
      $post->save();

    return response()->json([
          'status' => 'pinned',
          'post_id' => $post->id
        ], 200);
    }
}
