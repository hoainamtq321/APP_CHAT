<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function index(){
        return view('infomation');
    }

    public function updateInfo(Request $request)
    {
        
        $request->validate([
            'full_name' => 'required|string|max:200',
            'img' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Tìm người dùng
        $users = User::findOrFail(Auth::user()->user_id);  
        // Cập nhật dữ liệu
        $users->full_name = $request->full_name;

            // Xử lý ảnh nếu có ảnh mới
        if ($request->hasFile('img')) {
            // Lấy tên ảnh cũ
            $oldImage = $users->img;

            // Xoá ảnh cũ nếu tồn tại
            if ($oldImage) {
                $oldImagePath = public_path('/img/' . $oldImage);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            // Lưu ảnh mới
            $newImageName = 'img/'.time() . '_' . $request->file('img')->getClientOriginalName();
            $request->file('img')->move(public_path('/img/'), $newImageName);

            // Gán tên ảnh mới vào database
            $users->img = $newImageName;
        }
 
        $users->save();
        return back()->with('success', 'Sản phẩm đã được cập nhật!');
        
    }
}
