<?php

namespace App\Http\Controllers\Admin\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\Admin\AnalyticsSettingRequest;
use App\Models\Setting;
use App\Rules\AnalyticsCredentialRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Analytics\Exceptions\InvalidConfiguration;

class AnalyticsController extends Controller
{
    public function __construct(){
      $this->middleware('permission:analytics.view')->only('index');
      $this->middleware('permission:analytics.update')->only(['jsonUploadFile','updateAnalytics']);
    }
    public function index()
    {
      $settings = Setting::pluck('value', 'key')->toArray();
      return view('admin.analytics.index',compact('settings'));
    }

    public function jsonUploadFile(Request $request)
    {
      $request->validate([
            'json' => ['required', 'file', 'mimes:json', 'max:10'],
        ]);

        $content = $request->file('json')->getContent();

        $response = response()->json([
           'content' => $content
        ],200);
        if (! Str::isJson($content)) {
            return $response->array_merge([
                  'message' => 'This file is not a valid JSON file.'
                ]);
        }

        $validator = Validator::make(['content' => $content], [
            'content' => ['required', 'string', new AnalyticsCredentialRule()],
        ]);

        if ($validator->fails()) {
            $response->array_merge([
              'message' => $validator->messages()->first('content')
            ]);
        }

        return $response;
    }

    public function updateAnalytics(AnalyticsSettingRequest $request)
    {
       foreach ($request->validated() as $key => $value) {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value ?? '',
            ]
        );
    }
       $filaPath = config('analytics.service_account_credentials_json');

    if (! is_dir(dirname($filaPath))) {
        mkdir(dirname($filaPath), 0755, true);
    }

    if (! file_exists($filaPath)) {
        file_put_contents($filaPath, $request->serviceAccountCredentials());
    }

    if (! file_exists($filaPath)) {
        throw new InvalidConfiguration('The credentials file could not be written.');
    }
    toastr()->success("Analytics updated", ['TimeOut' => 1000]);
    return back();
    }
}
