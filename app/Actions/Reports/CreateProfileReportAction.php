<?php

namespace App\Actions\Reports;

use App\DTOs\ReportsDTO;
use App\Enums\ReportStatus;
use App\Models\ProfileReport;
use App\Models\User;

class CreateProfileReportAction
{
  public function report(ReportsDTO $dto, User $user): bool
  {
    $exists = ProfileReport::where('reporter_id', $dto->userId)
      ->where('profile_id', $user->id)
      ->exists();
    if ($exists)
      return false;

    ProfileReport::create([
      'reporter_id' => $dto->userId,
      'profile_id' => $user->id,
      'reason' => $dto->reason,
      'status' => ReportStatus::Pending,
      'other' => $dto->other
    ]);

    return true;
  }
}
