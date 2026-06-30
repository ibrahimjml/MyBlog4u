<?php

namespace App\Actions;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\PostModeration;
use Illuminate\Support\Facades\Gate;

class ResolvePostStatusAction
{
  public function handle(PostStatus $status)
  {
    return match ($status) {
      PostStatus::DRAFT => PostStatus::DRAFT,

      PostStatus::PUBLISHED => (PostModeration::rules()->enable_auto_approve
        || Gate::allows('bypassModeration', Post::class))
      ? PostStatus::PUBLISHED
      : PostStatus::PENDING,

      default => PostStatus::PENDING,
    };
  }
}
