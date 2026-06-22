<?php

namespace App\Http\Controllers\Admin\Optimization;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SeoController extends Controller
{
  use ImageUploadTrait;

  public function __construct()
  {
    $this->middleware('permission:seo.view')->only('index');
    $this->middleware('permission:seo.update')->only('update');
  }
  public function index()
  {
    $seoSettings = SeoSetting::first();
    $metaKeywords = $seoSettings ? implode(', ', $seoSettings->meta_keywords ?? []) : '';
    return view('admin.optimization.seo', compact('seoSettings', 'metaKeywords'));
  }
  public function update(Request $request)
  {
    $seoSettings = SeoSetting::first();
    if (!$seoSettings) {
      $seoSettings = new SeoSetting();
    }

    $seoSettings->app_name = $request->input('app_name');

    if ($request->boolean('delete_favicon') && $seoSettings->favicon_path) {
      $this->deleteSeoImage($seoSettings->favicon_path);
      $seoSettings->favicon_path = null;
    }

    if ($request->hasFile('favicon_path')) {

        $oldFavicon = $seoSettings->getRawOriginal('favicon_path');
        if ($oldFavicon) $this->deleteSeoImage($oldFavicon);

        $imagePath = $this->uploadFavicon($request->file('favicon_path'));
        $seoSettings->favicon_path = $imagePath;
    }

    $seoSettings->meta_title = $request->input('meta_title');
    $seoSettings->meta_description = $request->input('meta_description');
    $seoSettings->meta_keywords = array_filter(array_map('trim', explode(',', $request->input('meta_keywords'))));
    $seoSettings->meta_author = $request->input('meta_author');

    if ($request->boolean('delete_meta_og_image') && $seoSettings->meta_og_image) {
      $this->deleteSeoImage($seoSettings->meta_og_image);
      $seoSettings->meta_og_image = null;
    }

    if ($request->hasFile('meta_og_image')) {
        $oldOgImage = $seoSettings->getRawOriginal('meta_og_image');
        if ($oldOgImage) $this->deleteSeoImage($oldOgImage);

        $imagePath = $this->uploadMetaOgImage($request->file('meta_og_image'), $seoSettings->app_name);
        $seoSettings->meta_og_image = $imagePath;
    }

    $seoSettings->header_scripts = $request->input('header_scripts');
    $seoSettings->footer_scripts = $request->input('footer_scripts');
    $seoSettings->save();

    return redirect()->route('admin.seo.index')->with('success', 'SEO settings updated successfully.');
  }

  private function deleteSeoImage(string $imagePath)
  {
    $candidate = 'img/' . $imagePath;

    if (Storage::disk(media_driver())->exists($candidate)) {
      Storage::disk(media_driver())->delete($candidate);
    }
  }
}
