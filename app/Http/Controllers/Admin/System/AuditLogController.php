<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        $auditLogs = AuditLog::query()->with('user')->latest()->paginate(10);
        return view('admin.system.auditlogs.index', compact('auditLogs'));
    }

    public function destroy(AuditLog $auditLog)
    {
        $auditLog->delete();
        toastr()->success('Audit log deleted successfully.',['timeOut' => 1000]);
        return redirect()->route('admin.system.audit-logs.index');
    }
}
