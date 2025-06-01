<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Setting\Setting;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // public function authenticated(Request $request)
    // {
    //     $location = geoip($ip = $request->ip());
    //     $timezone = $location->timezone;
    //     $user = Auth::user();
    //     $user->timezone = $timezone;
    //     $user->save();
    //     Auth::logoutOtherDevices($request->input('password'));

    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
    //         // dd('dd');
    //         $request->session()->regenerate();

    //         return redirect()->intended('/');
    //     }
    //     // dd('dd1');

    //     return back()->withErrors([
    //         'email' => 'The provided credentials do not match our records.',
    //     ]);
    // }

    // public function authenticated(Request $request)
    // {
    //     $location = geoip($ip = $request->ip());
    //     $timezone = $location->timezone;
    //     $user = Auth::user();
    //     $user->timezone = $timezone;
    //     $user->save();
    //     Auth::logoutOtherDevices($request->input('password'));

    //     return redirect()->intended('/');
    // }


    protected function authenticated(Request $request, $user)
    {
        if ($user->google2fa_secret) {
            $request->session()->put('2fa:user:id', $user->id);
            $request->session()->put('2fa:user:auth:id', Auth::id());

            return redirect()->route('2fa.verifyForm');
        }

        return redirect()->intended($this->redirectPath());
    }
}
