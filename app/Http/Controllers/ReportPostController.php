<?php

namespace App\Http\Controllers;

use App\Actions\Reports\CreatePostReportAction;
use App\DTOs\ReportsDTO;
use App\Http\Middleware\CheckIfBlocked;
use App\Http\Requests\App\ReportRequest;
use App\Models\Post;
use Illuminate\Http\Request;


class ReportPostController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth', 'verified', CheckIfBlocked::class]);
  }
  public function report_post(Post $post, ReportRequest $request, CreatePostReportAction $reportPost)
  {
    $this->authorize('report', $post);
    if ($post->status !== \App\Enums\PostStatus::PUBLISHED) {
      return back();
    }
    $dto = ReportsDTO::fromRequest($request);

    if (!$reportPost->report($dto, $post)) {
      toastr()->error('You already reported this post', ['timeOut' => 1000]);
      return back();
    }
    toastr()->success('post report success', ['timeOut' => 1000]);

    return back();
  }
}
