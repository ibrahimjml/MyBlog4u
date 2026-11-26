<?php

namespace App\Http\Controllers\User;

use App\Contracts\Services\TrustedDeviceService as TrustedDeviceContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrustedDeviceController extends Controller
{
  public function __construct(protected TrustedDeviceContract $trustedDevices)
    {
        $this->middleware('auth');
    }
     public function index(Request $request)
    {
        return response()->json([
            'devices' => $this->trustedDevices->listFor($request->user()),
        ]);
    }
    public function destroy(Request $request, int $device)
    {
        $this->trustedDevices->forgetOne($request->user(), $device, $request);
 
        return response()->json(['success' => true, 'message' => 'Device removed.']);
    }
 
    public function destroyAll(Request $request)
    {
        $this->trustedDevices->forget($request->user());
 
        return response()->json(['success' => true, 'message' => 'All trusted devices removed.']);
    }
}
