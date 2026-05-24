<?php

namespace App\DTOs;

use App\Enums\ReportReason;
use App\Http\Requests\App\ReportRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class ReportsDTO
{
    public function __construct(
      public readonly int $userId,
      public readonly ReportReason $reason,
      public readonly ?string $other = null

    ){}
     public static function fromRequest(ReportRequest $request): self
    {
        return new self(
            userId: auth()->id(),
            reason: ReportReason::from($request->validated('report_reason')),
            other: $request->validated('report_reason') === ReportReason::Other->value ? $request->validated('other') : null
        );
    }
    public function toArray()
    {
      return [
        'user_id' => $this->userId,
        'reason' => $this->reason,
        'other' => $this->other
      ];
    }
}
