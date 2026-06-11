<?php

namespace App\Http\Controllers\Admin\ApiRateLimit;

use App\Enums\ApiLimits\ApiLimitStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\App\Admin\CreateApiLimitRequest;
use App\Models\ApiRateLimit;

class ApiRateLimitController extends Controller
{
  public function __construct()
  {
    $this->middleware('permission:limit.view')->only('index');
    $this->middleware('permission:limit.create')->only('store');
    $this->middleware('permission:limit.update')->only(['update', 'toggleStatus']);
    $this->middleware('permission:limit.delete')->only('destroy');
  }
  public function index()
  {
    $limits = ApiRateLimit::query()->latest()->paginate(10);
    $limitPayloads = $limits->getCollection()->mapWithKeys(function ($limit) {
      return [$limit->id => $this->apiLimitPayload($limit)];
    });
    return view('admin.api_limits.index', compact('limits', 'limitPayloads'));
  }
  public function store(CreateApiLimitRequest $request)
  {
    $data = $request->validated();
    $limit = ApiRateLimit::create($data);
    return response()->json([
      'message' => 'API limit created successfully',
      'limit' => $this->apiLimitPayload($limit),
    ], 201);
  }
  public function update(CreateApiLimitRequest $request, ApiRateLimit $limit)
  {
    try {
      $limit->update($request->validated());
      return response()->json([
        'message' => 'API limit created successfully',
      ], 201);
    } catch (\Throwable $e) {
      throw $e;
    }
  }
  public function toggleStatus(ApiRateLimit $limit)
  {
    $status = ApiLimitStatus::tryFrom($limit->getRawOriginal('status'));

    $limit->update([
      'status' => $status->value === ApiLimitStatus::ACTIVE->value
        ? ApiLimitStatus::DISABLED->value
        : ApiLimitStatus::ACTIVE->value,
    ]);
    toastr()->success("API limit changed to {$limit->status->label()}.", ['TimeOut' => 1000]);
    return back();

  }
  public function destroy(ApiRateLimit $limit)
  {
    try {
      $limit->delete();
      return response()->json([
        'message' => 'API limit deleted successfully',
      ], 201);
    } catch (\Throwable $e) {
      throw $e;
    }
  }
  private function apiLimitPayload($limit)
  {
    return [
      'id' => $limit->id,
      'route_name' => $limit->route_name,
      'max_attempts' => $limit->max_attempts,
      'time_window' => $limit->time_window,
      'description' => $limit->description,
      'status' => $limit->getRawOriginal('status'),
      'method' => $limit->getRawOriginal('method'),
    ];
  }
}
