<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class BackupsController extends Controller
{
  public function __construct()
  {
    $this->middleware('permission:backup.view')->only('backup_view');
    $this->middleware('permission:backup.download')->only('backup_download');
    $this->middleware('permission:backup.delete')->only('backup_destroy');
  }

  public function backup_view()
  {
    return view('admin.settings.backup', ['files' => Storage::disk('backups')->files()]);
  }
  public function backup_download($file)
  {
    $disk = Storage::disk('backups');

    if (! $disk->exists($file)) {
      abort(404);
    }

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
    $disk = Storage::disk('backups');

    if (! $disk->exists($file)) {
      abort(404);
    }
    $disk->delete($file);

    toastr()->success('Backup deleted', ['timeOut' => 1000]);
    return redirect()->back();
  }
}
