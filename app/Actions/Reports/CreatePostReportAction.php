<?php

namespace App\Actions\Reports;

use App\DTOs\ReportsDTO;
use App\Enums\ReportStatus;
use App\Models\Post;
use App\Models\PostReport;

class CreatePostReportAction
{
  public function report(ReportsDTO $dto, Post $post): bool
  {
    $exists = PostReport::where('user_id', $dto->userId)
      ->where('post_id', $post->id)
      ->exists();
    if ($exists) return false;

    PostReport::create(array_merge($dto->toArray(), [
      'post_id' => $post->id,
      'status' => ReportStatus::Pending,
    ]));

    return true;
  }
}
