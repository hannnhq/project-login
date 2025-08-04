<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showFormForgot(){
        return view('auth.forgotpass');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ],[
            'email. required' => 'Email không để trống',
            'email.exists' => 'Email không tồn tại'
        ]);

        // Gửi mail đặt lại mk
        $status = Password::sendResetLink($request->only('email'));

        if($status === Password::RESET_LINK_SENT){
            return back()->with('success', __($status));
        }
        return back()->withErrors(['email'=>__($status)]);
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

        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function($user,$password){
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
