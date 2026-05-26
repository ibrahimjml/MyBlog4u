<?php

namespace App\Http\Controllers\Admin\Optimization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MaintenanceController extends Controller
{
  public function __construct()
  {
    $this->middleware('can:maintenance.view')->only('maintenance_page');
    $this->middleware('can:maintenance.update')->only('run_artisans');
  }
  public function maintenance_page()
  {
    $cacheMeta = $this->computeCacheSize();
    $maintenanceActions = $this->getMaintenanceActions($cacheMeta);

    return view('admin.optimization.maintenance', compact('cacheMeta', 'maintenanceActions'));
  }

  public function run_artisans(Request $request)
  {
    $action = $request->input('action');
    $allowed = [
      'clear_all_cms_cache',
      'refresh_views',
      'clear_config',
      'clear_routes',
      'clear_logs',
    ];

    if (! in_array($action, $allowed)) {
      return response()->json(['status' => 'error', 'message' => 'Invalid action'], 400);
    }

    try {
      return match ($action) {
        'clear_all_cms_cache' => $this->clearAllCmsCache(),
        'refresh_views' => $this->refreshViews(),
        'clear_config' => $this->clearConfig(),
        'clear_routes' => $this->clearRoutes(),
        'clear_logs' => $this->clearLogs(),
        default => response()->json(['status' => 'error', 'message' => 'Invalid action'], 400),
      };
    } catch (\Exception $e) {
      return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
  }

  private function computeCacheSize()
  {
    // compute approximate cache size (storage/framework/cache, storage/framework/views, bootstrap/cache)
    $paths = [
      storage_path('framework/cache'),
      storage_path('framework/views'),
      base_path('bootstrap/cache'),
    ];

    $total = 0;
    foreach ($paths as $p) {
      if (File::isDirectory($p)) {
        $files = File::allFiles($p);
        foreach ($files as $f) {
          $total += $f->getSize();
        }
      }
    }

    $mb = $total / 1024 / 1024;
    $formatted = number_format($mb, 2, ',', '.');
    $cacheMeta = 'Current Size: ' . $formatted . ' MB';

    return $cacheMeta;
  }

  private function getMaintenanceActions(string $cacheMeta): array
  {
    return [
      [
        'key' => 'clear_all_cms_cache',
        'type' => 'Clear all CMS cache',
        'description' => "Clear CMS caching: database caching, static blocks. Run this command when you don't see the changes after updating data.",
        'meta' => $cacheMeta,
        'icon' => 'fas fa-database',
        'button' => 'Clear cache',
        'color' => 'bg-red-500 hover:bg-red-600 focus:ring-red-200',
      ],
      [
        'key' => 'refresh_views',
        'type' => 'Refresh compiled views',
        'description' => 'Clear compiled views to make views up to date.',
        'meta' => null,
        'icon' => 'fas fa-layer-group',
        'button' => 'Refresh views',
        'color' => 'bg-blue-500 hover:bg-blue-600 focus:ring-blue-200',
      ],
      [
        'key' => 'clear_config',
        'type' => 'Clear config cache',
        'description' => 'You might need to refresh the config caching when you change something on production environment.',
        'meta' => null,
        'icon' => 'fas fa-cogs',
        'button' => 'Clear config',
        'color' => 'bg-indigo-500 hover:bg-indigo-600 focus:ring-indigo-200',
      ],
      [
        'key' => 'clear_routes',
        'type' => 'Clear route cache',
        'description' => 'Clear cache routing.',
        'meta' => null,
        'icon' => 'fas fa-route',
        'button' => 'Clear routes',
        'color' => 'bg-emerald-500 hover:bg-emerald-600 focus:ring-emerald-200',
      ],
      [
        'key' => 'clear_logs',
        'type' => 'Clear log',
        'description' => 'Clear system log files.',
        'meta' => null,
        'icon' => 'fas fa-file-alt',
        'button' => 'Clear logs',
        'color' => 'bg-blueGray-700 hover:bg-blueGray-800 focus:ring-blueGray-200',
      ],
    ];
  }
  private function clearAllCmsCache()
  {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    return response()->json(['status' => 'success', 'message' => 'Cleared application cache, views, config and routes.']);
  }
  private function refreshViews()
  {
    Artisan::call('view:clear');
    return response()->json(['status' => 'success', 'message' => 'Cleared compiled views.']);
  }
  private function clearConfig()
  {
    Artisan::call('config:clear');
    return response()->json(['status' => 'success', 'message' => 'Cleared config cache.']);
  }
  private function clearRoutes()
  {
    Artisan::call('route:clear');
    return response()->json(['status' => 'success', 'message' => 'Cleared route cache.']);
  }
  private function clearLogs()
  {
    $logPath = storage_path('logs');
    if (File::isDirectory($logPath)) {
      $files = File::files($logPath);
      foreach ($files as $f) {
        File::delete($f->getPathname());
      }
    }
    return response()->json(['status' => 'success', 'message' => 'Cleared log files.']);
  }
}
