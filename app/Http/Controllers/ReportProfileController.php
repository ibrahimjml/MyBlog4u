<?php

namespace App\Http\Controllers;

use App\Actions\Reports\CreateProfileReportAction;
use App\DTOs\ReportsDTO;
use App\Http\Middleware\CheckIfBlocked;
use App\Http\Requests\App\ReportRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ReportProfileController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth', 'verified', CheckIfBlocked::class]);
  }
  public function report_profile(User $user, ReportRequest $request, CreateProfileReportAction $reportProfile)
  {
    $dto = ReportsDTO::fromRequest($request);

    if (!$reportProfile->report($dto, $user)) {
      toastr()->error('You already reported this profile', ['timeOut' => 1000]);
      return back();
    }
    toastr()->success('profile report success', ['timeOut' => 1000]);

    return back();
  }
}
