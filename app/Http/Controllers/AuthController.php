<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dr.journey.dashboard'); 
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'employee_code' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('employee_code', $request->employee_code)->first();

        if (!$user) {
            return back()->with('error', 'Employee Code not found');
        }

        if ($user->password != md5($request->password)) {
            return back()->with('error', 'Incorrect password');
        }

        Auth::login($user);
        // changed rouete from dashboard to home
        // only for the 600000 user
        // if (Auth::user()->employee_code == '600000') {
        return redirect()->route('home')->with('success', 'Login Successful');
        // } else {
        //     return redirect()->route('dashboard')->with('success', 'Login Successful');
        // }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
