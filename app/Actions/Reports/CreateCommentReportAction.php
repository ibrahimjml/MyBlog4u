<?php

namespace App\Actions\Reports;

use App\DTOs\ReportsDTO;
use App\Enums\ReportStatus;
use App\Models\Comment;
use App\Models\CommentReport;

class CreateCommentReportAction
{
  public function report(ReportsDTO $dto, Comment $comment): bool
  {
    $exists = CommentReport::where('reporter_id', $dto->userId)
      ->where('comment_id', $comment->id)
      ->exists();
    if ($exists)
      return false;

    CommentReport::create([
      'reporter_id' => $dto->userId,
      'comment_id' => $comment->id,
      'reason' => $dto->reason,
      'status' => ReportStatus::Pending,
      'other' => $dto->other
    ]);

    return true;
  }
}
