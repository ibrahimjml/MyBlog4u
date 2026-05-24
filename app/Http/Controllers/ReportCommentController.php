<?php

namespace App\Http\Controllers;

use App\DTOs\ReportsDTO;
use App\Http\Middleware\CheckIfBlocked;
use App\Http\Requests\App\ReportRequest;
use App\Models\Comment;
use App\Services\ReportsService;
use Illuminate\Http\Request;

class ReportCommentController extends Controller
{
      public function __construct(protected ReportsService $service)
      {
      $this->middleware(['auth', 'verified', CheckIfBlocked::class]);
      }
        public function report_comment(Comment $comment,ReportRequest $request)
       {
          $this->authorize('report',$comment);
          $dto = ReportsDTO::fromRequest($request);
      
           if (! $this->service->reportComment($comment, $dto)) {
              toastr()->error('You already reported this comment', ['timeOut' => 1000]);
              return back();
          }
            toastr()->success('comment report success',['timeOut'=>1000]);
            
            return back();
         }
}
