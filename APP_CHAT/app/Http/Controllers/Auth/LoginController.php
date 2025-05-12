<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function formLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:225',
            'password' => 'required|string|max:50',
        ]);

        $username = $request->username;
        $password = $request->password;
        $status = Auth::attempt(['username'=>$username,'password'=>$password]);

        if($status)
        {
            $user = Auth::user();
            $urlRedirect = "/chat";
            return redirect($urlRedirect);
        }
        return back()->with('msg','Tài khoản hoặc mật khẩu sai!');
        
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Đăng xuất

        $request->session()->invalidate(); // Hủy session
        $request->session()->regenerateToken(); // Đặt lại CSRF token

        return redirect('/login'); // Chuyển hướng về trang login
    }
}
