<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function fromlogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        $status = Auth::attempt(['name' => $username, 'password' => $password]); // kiểm tra username và password
        if($status)
        {
            $user = Auth::user();
            
            $urlRedirect = "/conversation";
            /*Trường hợp yêu cầu quyền*/
            /*
                if($user->is_admin)
                {
                    $urlRedirect = "/admin";
                }
            */
            /*Trường hợp yêu cầu quyền*/
            return redirect($urlRedirect);
        }
        return back()->with('msg','Tài khoản hoặc mật khẩu không chính xác!');
    }

    public function fromRegister()
    {
        return view('auth.resgister');
    }

    public function register(Request $request)
    {
        
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
        ]);
        // Lưu user vào database
        User::create([
            'name' => $request->username,
            'password' => Hash::make($request->password), // Mã hóa mật khẩu
        ]);

        return redirect()->back()->with('success', 'Đăng ký thành công!');
    }
}
