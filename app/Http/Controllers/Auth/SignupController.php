<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
            'role' => $role
        ]);
        // Gửi mail
        $user->sendEmailVerificationNotification();

        session(['verification_user_id', $user->id]);
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
        if($user->role === 'admin'){
            return redirect()->route('login.admin')->with('message','Đăng ký tài khoản admin thành công');
        }else{
            return redirect()->route('login.user')->with('message','Đăng ký tài khoản thành công');
        }
    }

    // gửi lại email
    public function send(Request $request){
        $userId = session('verification_user_id');
        if(!$userId){
            return back()->withErrors(['message'=>'Không tìm thấy người dùng']);
        }
        $user = User::find($userId);

        if(!$user){
            return back()->withErrors(['mesage'=>'Người dùng không tồn tại']);
        }
        if($user->hasVerifiedEmail()){
            return redirect()->route('login')->with('message','Tài khoản đã được xác minh');
        }

        $user->sendEmailVerificationNotification();
        return back()->with('message','Email xác minh đã được gửi');
    }
}
