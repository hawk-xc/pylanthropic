<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        if(Auth::check()) {
            return redirect()->route('adm.index');
        }
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);


        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('adm.index');
        } else {
            return redirect()->route('adm.login')->with([
                'status'  => 'warning',
                'message' => 'Tidak bisa Login karena Status Akun, mohon hubungi Admin jika mengalami kendala'
            ]);
        }
    }

    
    
    public function logout()
    {
        Auth::logout();
        return redirect()->route('adm.login')->with([
            'status'  => 'success',
            'message' => 'Berhasil Logout'
        ]);
    }

}
