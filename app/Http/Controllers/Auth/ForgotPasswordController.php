<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showFormForgot(Request $request){
        return view('auth.forgotpass');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ],[
            'email.required' => 'Email không để trống',
            'email.exists' => 'Email không tồn tại'
        ]);

        // Gửi mail đặt lại mk
        $status = Password::sendResetLink($request->only('email'));

        if($status === Password::RESET_LINK_SENT){
            return back()->with('success', 'Gửi link đặt lại mật khẩu đến email thành công');
        }
        return back()->withErrors(['email'=> 'Có lỗi ']);
    }

    public function showFormReset(Request $request, $token){
        return view('auth.reset-password',[
            'token' => $token,
            'email' => $request->query('email')
        ]);
    }

    public function reset(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex: /[A-Z]/',
                'regex: /[a-z]/',
                'regex: /[0-9]/',
                'regex: /[!@#$%^&*?]/',
            ],
        ],[
            'password.regex' => 'Mật khẩu phải chưa ít nhất 1 chữ in hoa, 1 chữ in thường, 1 số, 1 kí tự đặc biệt'
        ]);
        //Kiểm tra token reset password
        $status = Password::reset(
            // Lấy đúng 4 field từ form
            $request->only('email','password','password_confirmation','token'),
            function($user,$password){
                 // Đặt lại mật khẩu đã hash và nhớ đăng nhập mới
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if($status == Password::PASSWORD_RESET){
            return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công');
        }
    }
}
