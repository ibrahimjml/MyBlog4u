<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Hashtag;
use App\Enums\TagStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Fluent;
use App\Services\ClearCacheService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Enum;

class TagsController extends Controller
{
      public  function __construct() {
    
    $this->middleware('permission:tag.view')->only('hashtags');
    $this->middleware('permission:tag.create')->only('create');
    $this->middleware('permission:tag.update')->only('edit');
    $this->middleware('permission:tag.delete')->only('delete');
    $this->middleware('permission:tag.feature')->only('toggle');
  }
    public function create(Request $request){
  $fields = $request->validate([
  'name' =>'required|string',
  'status' => ['required', new Enum(TagStatus::class)]
  ]);
 $hashtag = Hashtag::create($fields);
  return response()->json([
    'added'=>true,
    'hashtag' => $hashtag->name
  ]);
}

  public function hashtags(Request $request){

    $hashtags = Hashtag::query()
              ->filterTag(new Fluent($request->only('sort','search','featured')))
              ->paginate(6)
              ->withQueryString();
    return view('admin.hashtags.hashtags',[
      'hashtags' => $hashtags
    ]);
  }

public function edit(Hashtag $hashtag, Request $request){
  try{
      $fields = $request->validate([
    'name' =>'nullable|string',
    'status' => ['required', new Enum(TagStatus::class)]
    ]);
    $hashtag->update([
              'name' => $fields['name'],
              'status' => $fields['status']
            ]);
  $hashtag->posts->each(function ($post) {
    app(ClearCacheService::class)->clearPostCaches($post);
});
    return response()->json([
      'edited'=>true,
      'message' => "Hashtag {$hashtag->name} updated",
      'hashtag' => $hashtag->name
    ]);
  }catch(Exception $e){
    Log::error('update tag failed',[
      'error' => $e->getMessage(),
    ]);
    return;
  }

}

  public function delete(Hashtag $hashtag){
    $name = $hashtag->name;
    $hashtag->delete();
      $hashtag->posts->each(function ($post) {
    app(ClearCacheService::class)->clearPostCaches($post);
});
    return response()->json([
      'deleted' => true,
      'message' => "Hashtag {$name} deleted"
  ]);
  }

  public function toggle(Hashtag $hashtag)
  {
      $hashtag->update(['is_featured'=>!$hashtag->is_featured]);
        $hashtag->posts->each(function ($post) {
    app(ClearCacheService::class)->clearPostCaches($post);
});
      if($hashtag->is_featured){
       toastr()->success('hashtag featured success',['timeOut'=>1000]);
      }else{
      toastr()->success('hashtag unfeatured success',['timeOut'=>1000]);
      }
      return redirect()->back();
  }
}
