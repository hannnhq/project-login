<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SignupController extends Controller
{
    public function index(){
        return view('auth.signup');
    }
    public function store(Request $request){
        // Validate dữ liệu
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[@$!%*?&]).+$/',
            'address' => 'nullable|string',
        ],[
            'name.required' => 'Họ tên là trường bắt buộc',
            'email.required' => 'Email là trường bắt buộc',
            'password.required' => 'Mật khẩu là trường bắt buộc',
            'email' => 'Email phải đúng định dạng',
            'password.regex' => 'Mật khẩu phải chứa ít nhất 8 kí tự và phải chứa ít nhất 1 chữ hoa, 1 chữ thường, 1 kí tự số, 1 kí tự đặc biệt',
        ]);
        // kiểm tra email tòn tại
        $emailExits = User::where('email', $request->email)->exists();

        if($emailExits){
            return back()->withErrors(['email'=>'Email đã tồn tại'])->withInput();
        }
        // Tạo mới
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'address' => $request->address,
        ]);
        // Gửi mail
        $user->sendEmailVerificationNotification();

        return redirect()->route('verify.email')->with('message','Hãy kiểm tra email');

    }

    // Xác minh email
    public function verifyEmail(Request $request, $id, $hash){
        $user = User::findOrFail($id);

    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403); // hash không đúng
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified(); // cập nhật email_verified_at
    }
    return redirect()->route('login')->with('message','Đăng ký thành công');
    }

    // gửi lại email
    public function send(Request $request){
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message','Email xác minh đã được gửi');
    }
}
