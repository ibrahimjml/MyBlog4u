<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Services\BackupDiskResolver;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class BackupsController extends Controller
{
  public function __construct(protected BackupDiskResolver $backupDisk)
  {
    $this->middleware('permission:backup.view')->only('backup_view');
    $this->middleware('permission:backup.download')->only('backup_download');
    $this->middleware('permission:backup.delete')->only('backup_destroy');
  }

  public function backup_view()
  {
    $disk = $this->backupDisk->resolve();
    return view('admin.settings.backup', [
      'files' => $disk->files()
      ]);
  }
  public function backup_download($file)
  {
    $disk = $this->backupDisk->resolve();

    abort_unless($disk->exists($file), 404);

    $encrypted = base64_decode($disk->get($file));

    try {
      $sql = Crypt::decryptString($encrypted);
    } catch (\Throwable $e) {
      logger()->error('Backup decrypt failed', [
        'message' => $e->getMessage(),
      ]);
    }

    $filename = preg_replace('/\.sql\.enc$/', '.sql', basename($file));

    return response()->streamDownload(
      function () use ($sql) {
        echo $sql;
      },
      $filename,
      [
        'Content-Type' => 'application/sql',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
      ]
    );
  }
  public function backup_destroy($file)
  {
    $disk = $this->backupDisk->resolve();

    abort_unless($disk->exists($file), 404);
    $disk->delete($file);

    toastr()->success('Backup deleted', ['timeOut' => 1000]);
    return redirect()->back();
  }
}
