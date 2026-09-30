<?php 

use App\Http\Controllers\Admin\System\AuditLogController;
use Illuminate\Support\Facades\Route;



// platform settings
Route::prefix('system')->name('system.')->group(function () {
  // audit logs 
  Route::controller(AuditLogController::class)->group(function () {
    Route::get('/audit-logs', 'index')->middleware('permission:audit-logs.view')->name('audit-logs.index');
    Route::delete('/audit-logs/{auditLog}', 'destroy')->middleware('permission:audit-logs.delete')->name('audit-logs.destroy');
  });
});