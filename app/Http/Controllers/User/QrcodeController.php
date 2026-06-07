<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckIfBlocked;
use SimpleSoftwareIO\QrCode\Generator;

class QrcodeController extends Controller
{
    public function __construct(){
      $this->middleware(['auth',CheckIfBlocked::class]);
    }

    public function __invoke()
    {   
        $user = request()->user();
        $qrCodeGenerator = app(Generator::class);

        $qrCode = $qrCodeGenerator
            ->margin(2)
            ->format('png')
            ->size(512)
            ->merge('/public/img/icon.png')
            ->backgroundColor	(250, 128, 114)
            ->color(0, 0, 0,80)
            ->generate(route('profile.home',[
              'user'=>$user->username
            ]));

        return response($qrCode)
              ->header('Content-Type', 'image/png');
    }
    
    
}
