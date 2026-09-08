<?php

namespace App\Http\Controllers\Admin\Scraping;

use App\Actions\ForceCreateAllScrapSources;
use App\Enums\ScrapFrequency;
use App\Http\Controllers\Controller;
use App\Http\Requests\App\Admin\ScrapSettingRequest;
use App\Models\Setting;
use Illuminate\Support\Carbon;


class ScrapSettingController extends Controller
{
    public function __construct(protected ForceCreateAllScrapSources $forceCreate){}
    public function index()
    {
      $settings = Setting::pluck('value', 'key')->toArray();
      return view('admin.scraping.settings', compact('settings'));
    }

    public function scrapUpdate(ScrapSettingRequest $request)
    {
       foreach ($request->validated() as $key => $value) {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value ?? '']
        );
    }
     toastr()->success('scrap settings updated successfuly',['timeOut' => 1000]);
     return redirect()->back();
    }

    public function forceRunAll()
    {
        $sources = $this->forceCreate->execute();

        toastr()->info($sources['message'], ['timeOut' => 2000]);

        return back();
    }
    public function cronRun($token)
    {
        $tokenExists = Setting::where('crawl_token', $token);

        if (!$tokenExists) {
        return response()->json([
            'message' => 'Unauthorized access.'
        ], 403);
      }

        $autoScrapEnabled = Setting::get('auto_scrap_enabled', false);

        if($autoScrapEnabled){

          $frequency = Setting::get('crawl_frequency');
          $frequencyEnum = ScrapFrequency::tryFrom($frequency) ?? ScrapFrequency::TWINTY_FOUR_HOURS;
          $nextCrawlAt = $frequencyEnum->nextCrawlDateTime();
            
            Setting::updateOrCreate(
            ['key' => 'last_crawl_at'],
            ['value' => Carbon::now()->toDateTimeString()]
            );
  
          Setting::updateOrCreate(
          ['key' => 'next_crawl_at'],
          ['value' => $nextCrawlAt->toDateTimeString()]
           );
        }

         $sources = $this->forceCreate->execute();

        return response()->json([
            'success' => true,
            'message' => $sources['message']
        ], 200);
    }
    public function regenerateToken()
    {
        Setting::updateOrCreate(
            ['key' => 'crawl_token'],
            ['value' => bin2hex(random_bytes(32)) ]
        );
    
      toastr()->success('token generated successfuly',['timeOut' => 1000]);
     return redirect()->back();
    }

    
}
