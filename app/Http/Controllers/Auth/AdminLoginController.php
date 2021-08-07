<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.adminLogin');
    }

    public function login(Request $request) {

        $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $creds = $request->only('email','password');

        if(Auth::guard('admin')->attempt($creds)){
            return redirect()->route('admin.dashboard');
        }else{
            return redirect()->route('admin.login')->with('fail','Incorrect credentials');
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect()->route('home');
    }
}
