<?php

namespace App\Http\Controllers;


use App\Http\Requests\App\RegisterRequest;
use App\Models\CustomPage;
use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Support\Str;
use App\Mail\ForgotPassword;
use App\Services\User\RegisterUserService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
  public function registerpage()
  {
    abort_unless_require_registration();
    $terms = CustomPage::firstWhere('slug','terms-of-service');
    $privacy = CustomPage::firstWhere('slug','privacy-policy');
    return view('auth.register',['terms' => $terms,'privacy' => $privacy]);
    
  }

  public function register(RegisterRequest $request,RegisterUserService $service)
  {
    abort_unless_require_registration();
    $result = $service->register($request->validated());
    toastr()->info($result['success'],['timeOut'=>7000]);
    return back();
  }

  public function loginpage()
  {
    
    return view('auth.login');
  }


  public function login(Request $request)
  {
    $fields = $request->validate([
      "login" => 'required|string',
      "password" => 'required',
    ]);  

    $loginType = filter_var($fields['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    if (! auth()->validate([
        $loginType => $fields['login'],
        'password' => $fields['password'],
    ])) {
        toastr()->error('Wrong credentials', ['timeOut' => 1000]);
        return back()->withInput($request->only('login'));
    }

    $user = User::where($loginType, $fields['login'])->first();

    if ($user->is_blocked) {
        return back();
    }
    // activation check
    if($user->hasRole(\App\Enums\UserRole::USER->value) && 
    (! $user->activation || ! $user->activation->completed))
     {
      toastr()->info('Your account has not yet been activated, please be passiont.', ['timeOut' => 7000]);
      return back();
   }

    if ($user->has_two_factor_enabled) {
        auth()->login($user);
        session(['2fa:user:id' => $user->id,'2fa:passed'=>false]);
        return redirect()->route('2fa.confirmation');
    }
    session(['2fa:passed' => true]);
    auth()->login($user);

    toastr()->success('Logged in successfully', ['timeOut' => 1000]);
    return $user->is_admin
        ? redirect('/admin/panel')
        : redirect('/dashboard');
  }

  public function forgot()
  {
    return view('auth.forgot');
  }

  public function reset($token)
  {

    $resetToken = DB::table('password_reset_tokens')->where('token', $token)->first();
    if ($resetToken) {
      $user = User::where('email', $resetToken->email)->first();
      if (!empty($user)) {
      
        return view('auth.reset',['token' => $token]);
      }
    } else {
      abort(404);
    }
  }


  public function forgotpass(Request $request)
  {

    $fields = $request->validate([
      "email" => ["required", "email", "min:5", "max:50"],
      "g-recaptcha-response" => [new Recaptcha()]
    ]);
    $fields['email'] = htmlspecialchars(strip_tags($fields['email']));

    $user = User::where('email', '=', $fields['email'])->first();
    if (!empty($user)) {

      $token = Str::random(40);
      DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $user->email],
        ['token' => $token, 'created_at' => now()]
      );
      $user->save();

      Mail::to($user->email)->queue(new ForgotPassword($user,$token));
      
      toastr()->success('please check your email',['timeOut'=>2000]);
      return back();
    } else {

      toastr()->error('sorry,email not found ',['timeOut'=>1000]);
      return back();
    }
  }

  public function reset_pass($token, Request $request)
  {

    $resetToken = DB::table('password_reset_tokens')->where('token', $token)->first();

    if ($resetToken) {
      $user = User::where('email', $resetToken->email)->first();
      if (!empty($user)) {
        $fields = $request->validate([
          "password" => ["required","min:8", "max:32", "confirmed"],
        ]);

        $user->password = bcrypt($fields['password']);

        $user->save();
        DB::table('password_reset_tokens')->where('token', $token)->delete();
        toastr()->success('password reset success',['timeOut'=>1000]);
        return redirect()->route('login');
      }
    } else {

      abort(404);
    }
  }

  public function logout()
  {
    auth()->logout();
    session()->forget([
        '2fa:user:id',
        '2fa:passed',
    ]);
    session()->invalidate();
    session()->regenerateToken();
    toastr()->success('Logged out ',['timeOut'=>1000]);
    return redirect('/');
  }

  public function index()
    {
      return view('auth.confirmpassword');
    }

    public function confirm(Request $request)
    {
      
        if (! Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors([
                'password' => ['The provided password does not match our records.']
            ]);
        }
     
        $request->session()->passwordConfirmed();
     
        return redirect()->intended();
      }

      public function verify_notice()
      {
        $user = auth()->user();
        if ($user->hasVerifiedEmail()) {
          return redirect()->route('blog');
        }
        return view('auth.verifyemail',['message'=>session('message')]);
      }
  
      public function verify_email(EmailVerificationRequest $request)
      {
        $request->fulfill();
        return redirect('/');
      }
      public function verify_notification(Request $request) 
      {
        $user = $request->user();

        if (! $user) {
          return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
          return redirect('/')->with('message', 'Your email is already verified.');
        }

        try {
          $user->sendEmailVerificationNotification();
        } catch (\Throwable $exception) {
          Log::error('Unable to send verification email.', [
            'user_id' => $user->id,
            'message' => $exception->getMessage(),
          ]);

          return back()->with('message', 'Unable to send verification email. Please check your mail settings.');
        }
      
        return back()->with('message', 'Verification link sent!');
      }
}
