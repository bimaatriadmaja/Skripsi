<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // pastikan mengimpor Request class
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirect users after login.
     *
     * @return string
     */
    protected function redirectTo()
    {
        if (Auth::user()->utype === 'ADM') {
            return '/admin';
        } else {
            return '/app';
        }
    }

    /**
     * Override the credentials check to handle failed login attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function credentials(Request $request) // pastikan parameter menggunakan Illuminate\Http\Request
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Show the login form after failed login attempt.
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only('email', 'remember')) // Keep the email input in the form
            ->withErrors(['email' => 'Email atau password yang Anda masukkan salah.']);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
