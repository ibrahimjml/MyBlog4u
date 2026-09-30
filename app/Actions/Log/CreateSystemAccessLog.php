<?php

namespace App\Actions\Log;

use App\Models\User;
use App\Services\AuditLog\AuditLogService;

class CreateSystemAccessLog
{
     public function __construct(
         protected AuditLogService $auditLogService
     ) {}
     public function logAccess(User $user): void
    {
        if (! ($user->is_admin || $user->hasPermission('Access'))) {
            return;
        }

        $this->auditLogService->log(
               module: 'System',
               action: 'Logged in',
               referenceUser: 0,
               referenceId: $user->getKey(),
               referenceName: $user->name,
               type: 'info',
         );
    }


}