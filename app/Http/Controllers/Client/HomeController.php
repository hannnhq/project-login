<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
        $user = Auth::user();
        return view('user.home', compact('user'));
    }
    public function showProfile(){
        $user = Auth::user();
        return view('user.update-profile', compact('user'));
    }
    public function updateProfile(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|numeric',
            'dob' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,png|max:2048'
        ],[
            'name.required' => 'Tên không để trống',
            'phone.numeric' => 'Số điện thoại không chứa chữ',
            'avatar.image' => 'Tệp tải lên phải là hình ảnh',
            'avatar.mimes' => 'Chỉ cho phép ảnh định dạng JPG hoặc PNG',
            'avatar.max' => 'Kích thước ảnh tối đa là 2MB',
        ]);

        $user = Auth::user();

        if($request->hasFile('avatar') && $request->file('avatar')->isValid()){
            $file = $request->file('avatar');
            $filename = time(). '.'. $file->getClientOriginalExtension();

            $path = $file->storeAs('avatars', $filename, 'public');
            $user->avatar = $path;
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->dob = $request->dob;
        $user->address = $request->address;
        $user->save();

        return redirect()->route('user.home')->with('success', 'Cập nhật thông tin thành công');
    }
}
