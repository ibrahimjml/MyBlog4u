<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\Admin\MediaSettingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class MediaSettingController extends Controller
{
    public function __construct()
    {
      $this->middleware('permission:media.view')->only('index');
      $this->middleware('permission:media.update')->only('mediaSettingUpdate');
    }
    public function index()
    {
       $settings = Setting::pluck('value', 'key')->toArray();
       return view('admin.settings.media',compact('settings'));
    }
    public function mediaSettingUpdate(MediaSettingRequest $request)
    {
       foreach ($request->validated() as $key => $value) {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value ?? '',
            ]
        );
    }
     toastr()->success('Media updated successfuly',['timeOut' => 1000]);
     return redirect()->back();
    }
}
