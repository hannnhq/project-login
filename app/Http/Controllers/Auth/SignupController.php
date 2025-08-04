<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserSignup;
use App\Http\Controllers\Controller;
use App\Listeners\SendEmailVerification;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SignupController extends Controller
{
    public function indexUser(){
        return view('user.signup');
    }
    public function indexAdmin(){
        return view('admin.admin-signup');
    }

    public function storeUser(Request $request){
        return $this->handleLogin($request, 'user');
    }

    public function storeAdmin(Request $request){
        return $this->handleLogin($request, 'admin');
    }

    private function handleLogin(Request $request, string $role){
        // Validate dữ liệu
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[@$!%*?&]).+$/',
            'cpassword' => 'required|same:password',
            'address' => 'nullable|string',
        ],[
            'name.required' => 'Họ tên là trường bắt buộc',
            'email.required' => 'Email là trường bắt buộc',
            'password.required' => 'Mật khẩu là trường bắt buộc',
            'email' => 'Email phải đúng định dạng',
            'password.regex' => 'Mật khẩu phải chứa ít nhất 8 kí tự và phải chứa ít nhất 1 chữ hoa, 1 chữ thường, 1 kí tự số, 1 kí tự đặc biệt',
            'cpassword.required' => 'Nhập lại mật khẩu không được để trống',
            'cpassword.same' => 'Nhập lại mật khẩu không khớp',
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
            'is_active' => 1,
            'role' => $role
        ]);
        Log::info('Đang đăng ký với role: ' . $role);
        // Gửi mail
        event(new UserSignup($user));

        Auth::login($user);

        session(['signup_role' => $role]);

        return redirect()->route('verify.email')->with('message','Hãy kiểm tra email');

    }

    // Xác minh email
    public function verifyEmail(Request $request, $id, $hash){
        $user = User::findOrFail($id);

        if(!Auth::check()){
            Auth::login($user);
        }
        // Xác minh hash
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403); // hash không đúng
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified(); // cập nhật email_verified_at
        }

        if((session('signup_role')) === 'admin'){
            session()->forget('signup_role');
            return redirect()->route('login')->with('message','Đăng ký tài khoản admin thành công');
        }else{
            session()->forget('signup_role');
            return redirect()->route('login')->with('message','Đăng ký tài khoản thành công');
        }
    }

    // gửi lại email
    public function send(Request $request){
        $user = $request->user();
        if(!$user){
            return back()->withErrors(['message'=>'Không tìm thấy người dùng']);
        }

        if($user->hasVerifiedEmail()){
            $role = session('signup_role') ?? $user->role;
            if($role === 'admin'){
                return redirect()->route('login.admin')->with('message','Tài khoản đã được xác minh');
            }
            return redirect()->route('login')->with('message','Tài khoản đã được xác minh');
        }

        $user->notify(new VerifyEmailNotification());
        return back()->with('message','Email xác minh đã được gửi lại')->with('resent',true);
    }
}
