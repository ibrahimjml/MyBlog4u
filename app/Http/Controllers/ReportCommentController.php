<?php

namespace App\Http\Controllers;

use App\Actions\Reports\CreateCommentReportAction;
use App\DTOs\ReportsDTO;
use App\Http\Middleware\CheckIfBlocked;
use App\Http\Requests\App\ReportRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class ReportCommentController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth', 'verified', CheckIfBlocked::class]);
  }
  public function report_comment(Comment $comment, ReportRequest $request, CreateCommentReportAction $reportComment)
  {
    $this->authorize('report', $comment);
    $dto = ReportsDTO::fromRequest($request);

    if (!$reportComment->report($dto, $comment)) {
      toastr()->error('You already reported this comment', ['timeOut' => 1000]);
      return back();
    }
    toastr()->success('comment report success', ['timeOut' => 1000]);

    return back();
  }
}
