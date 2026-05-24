<?php

namespace App\Http\Controllers;

use App\DTOs\ReportsDTO;
use App\Http\Middleware\CheckIfBlocked;
use App\Http\Requests\App\ReportRequest;
use App\Models\User;
use App\Services\ReportsService;
use Illuminate\Http\Request;

class ReportProfileController extends Controller
{
      public function __construct(protected ReportsService $service){
      $this->middleware(['auth', 'verified', CheckIfBlocked::class]);
  }
    public function report_profile(User $user,ReportRequest $request)
    {
         $dto = ReportsDTO::fromRequest($request);

     if (! $this->service->reportProfile($user, $dto)) {
        toastr()->error('You already reported this profile', ['timeOut' => 1000]);
        return back();
    }
      toastr()->success('profile report success',['timeOut'=>1000]);
      
    return back();
    }
}
