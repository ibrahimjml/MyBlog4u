<?php

namespace App\Services\AuditLog;

use App\Events\AuditLogCreatedEvent;
use Illuminate\Http\Request;

class AuditLogService
{
    public function __construct(
        protected Request $request
    ) {}

    /**
     * Log an audit event manually
     */
    public function log(
        string $module,
        string $action,
        ?int $userId = null,
        ?int $referenceUser = null,
        ?int $referenceId = null,
        ?string $referenceName = null,
        ?string $type = null,
        ?string $request = null,
    ): void {
        event(new AuditLogCreatedEvent(
            module: $module,
            action: $action,
            userId: $userId,
            referenceUser: $referenceUser,
            referenceId: $referenceId,
            referenceName: $referenceName,
            type: $type,
            request: $request,
            userAgent: $this->request->userAgent(),
            ipAddress: $this->request->ip(),
        ));
    }

    /**
     * Auto Log for model action (create/update/delete)
     */
    public function logModelAction(
        string $module,
        string $action,
        $model,
        ?string $type = null,
    ): void {
        $this->log(
            module: $module,
            action: $action,
            userId: auth()->id(),
            referenceUser: $model->user_id ?? null,
            referenceId: $model->id,
            referenceName: class_basename($model),
            type: $type ?? strtolower($action),
        );
    }
}