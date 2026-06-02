<?php

namespace App\Http\Controllers\Admin\Ads;

use App\Enums\Adplacements\AdPosition;
use App\Enums\Adplacements\AdStatus;
use App\Enums\Adplacements\AdType;
use App\Helpers\DeleteFile;
use App\Http\Controllers\Controller;
use App\Http\Requests\App\Admin\AdRequest;
use App\Models\AdPlacement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
  public function __construct()
  {
    $this->middleware('permission:ad.view')->only('index');
    $this->middleware('permission:ad.create')->only('store');
    $this->middleware('permission:ad.update')->only('update', 'toggleStatus');
    $this->middleware('permission:ad.delete')->only('destroy');
  }
  public function index()
  {
    $ads = AdPlacement::query()->latest()->paginate(10);
    $adPayloads = $ads->getCollection()->mapWithKeys(function (AdPlacement $ad) {
      return [$ad->id => $this->adPayload($ad)];
    });
    return view('admin.ads.index', compact('ads', 'adPayloads'));
  }

  public function store(AdRequest $request)
  {
    $uploadedImagePath = null;

    try {
      $ad = DB::transaction(function () use ($request, &$uploadedImagePath) {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
          $uploadedImagePath = $request->file('image')->store('ads', 'public');
          $validated['image_path'] = $uploadedImagePath;
        }

        unset($validated['image']);

        return AdPlacement::create($validated);
      });
    } catch (\Throwable $exception) {
      if ($uploadedImagePath) {
        DeleteFile::existImage($uploadedImagePath);
      }

      throw $exception;
    }

    if ($request->expectsJson()) {
      return response()->json([
        'message' => 'Ad created successfully.',
        'ad' => $this->adPayload($ad),
      ], 201);
    }

    return redirect()->route('admin.ads.index')->with('success', 'Ad created successfully.');
  }

  public function update(AdRequest $request, AdPlacement $ad)
  {
    $uploadedImagePath = null;
    $oldImagePath = $ad->image_path;

    try {
      DB::transaction(function () use ($request, $ad, &$uploadedImagePath) {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
          $uploadedImagePath = $request->file('image')->store('ads', 'public');
          $validated['image_path'] = $uploadedImagePath;
        } elseif ($request->boolean('remove_image')) {
          $validated['image_path'] = null;
        }

        unset($validated['image']);
        unset($validated['remove_image']);

        $ad->update($validated);
      });
    } catch (\Throwable $exception) {
      if ($uploadedImagePath) {
        DeleteFile::existImage($uploadedImagePath);
      }

      throw $exception;
    }

    if (($uploadedImagePath || $request->boolean('remove_image')) && $oldImagePath) {
      DeleteFile::existImage($oldImagePath);
    }

    if ($request->expectsJson()) {
      return response()->json([
        'message' => 'Ad updated successfully.',
        'ad' => $this->adPayload($ad->fresh()),
      ]);
    }

    return redirect()->route('admin.ads.index')->with('success', 'Ad updated successfully.');
  }

  public function toggleStatus(AdPlacement $ad)
  {
    $status = AdStatus::tryFrom($ad->getRawOriginal('status'));

    $ad->update([
      'status' => $status->value === AdStatus::ACTIVE->value ? AdStatus::DISABLED->value : AdStatus::ACTIVE->value,
    ]);

    if (request()->expectsJson()) {
      return response()->json([
        'message' => 'Ad status updated successfully.',
        'ad' => $this->adPayload($ad->fresh()),
      ]);
    }

    return back()->with('success', 'Ad status updated successfully.');
  }

  public function destroy(AdPlacement $ad)
  {
    if ($ad->image_path) {
      DeleteFile::existImage($ad->image_path);
    }

    $ad->delete();

    if (request()->expectsJson()) {
      return response()->json([
        'message' => 'Ad deleted successfully.',
      ]);
    }

    return back()->with('success', 'Ad deleted successfully.');
  }

  private function adPayload(AdPlacement $ad): array
  {
    return [
      'id' => $ad->id,
      'ad_name' => $ad->ad_name,
      'ad_type' => $ad->getRawOriginal('ad_type'),
      'ad_type_label' => AdType::tryFrom($ad->getRawOriginal('ad_type'))?->label(),
      'ad_position' => $ad->getRawOriginal('ad_position'),
      'ad_position_label' => AdPosition::tryFrom($ad->getRawOriginal('ad_position'))?->label(),
      'ad_dimension' => $ad->ad_dimension,
      'ad_code' => $ad->ad_code,
      'image_url' => $ad->image_url,
      'link_url' => $ad->link_url,
      'status' => $ad->getRawOriginal('status'),
      'status_label' => AdStatus::tryFrom($ad->getRawOriginal('status'))?->label(),
    ];
  }
}
