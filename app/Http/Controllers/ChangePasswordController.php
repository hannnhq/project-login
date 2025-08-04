<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function showFormChange(){
        return view('user.change-password');
    }

    public function showFormChangeAdmin() {
        return view('admin.change-password');
    }

    public function changePassword(Request $request){
        // Validate
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'min:8',
                'regex: /[A-Z]/',
                'regex: /[a-z]/',
                'regex: /[0-9]/',
                'regex: /[!@#$%^&*?]/',
                'different:current_password',
            ],
            'new_password_confirm' => 'required'
        ],[
            'current_password.required' => 'Mật khẩu là trường bắt buộc',
            'new_password.required' => 'Mật khẩu mới là trường bắt buộc',
            'new_password_confirm.required' => 'Xác nhận mật khẩu mới là trường bắt buộc',
            'new_password.regex' => 'Mật khẩu mới phải có ít nhất 1 chữ in hoa, 1 chữ thường, 1 kí tự số, 1 kí tự đặc biệt',
            'new_password.different' => 'Mật khẩu mới phải khác mật khẩu cũ',
        ]);

        $user = Auth::user();

        if(!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng'])->withInput();
        }
        if($request->new_password !== $request->new_password_confirm){
            return back()->withErrors(['new_password_confirm' => 'Xác nhận mật khẩu không khớp với mật khẩu mới'])->withInput();
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        if($user->role === 'admin'){
            return redirect()->route('admin.dashboard')->with('success', 'Đổi mật khẩu thành công');
        }
        return redirect()->route('user.dashboard')->with('success', 'Đổi mật khẩu thành công');
    }
}
