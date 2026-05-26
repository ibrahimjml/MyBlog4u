<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use  Illuminate\Support\Facades\Route;

Route::prefix('health')
       ->middleware('can:makeAdminActions')
       ->group(function(){

  Route::get('/check',function(){

    try{
       DB::connection()->getPdo();
       $database = 'fine';

    }catch(\Exception $e){
      $database = 'not-fine';
    };

     try{
        // check redis cache
        Cache::put('check', 'ok', 10);
        $result = Cache::get('check');
        $cache = ($result === 'ok') ? 'fine' : 'not-fine';
     } catch(\Exception $e){
       $cache = 'not-fine';
     }

     $status = ($database === 'fine' && $cache === 'fine') ? 'fine' : 'not-fine';
     return response()->json([
      'status' => $status,
      'time' => now(),
      'services' => [
           'database' => $database,
           'cache' => $cache
      ],
        'app' => [
           'name' => config('app.name'),
           'debug' => config('app.debug')
        ],
      ], $status === 'fine' ? 200 : 503);
  });
       
       Route::get('/clear-session', function() {
           Artisan::call('session:clear');
           return "Session cleared!";
       });
      
       Route::get('/optimize', function() {
           Artisan::call('config:cache');
           Artisan::call('route:cache');
           Artisan::call('view:cache');
           
           return "Application optimized ";
       });
       Route::get('/clear-view', function() {
            Artisan::call('view:clear');
           return "View cache cleared!";
         });
        Route::get('/clear-cache', function() {
             Artisan::call('cache:clear');
            return "Application cache cleared!";
          });
});