<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;

class LoginController extends Controller
{
    public function showFormUser(){
        return view('user.login');
    }

    public function showFormAdmin(){
        return view('admin.login-admin');
    }

    public function login(Request $request){
        //Validate
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ],[
            'email' => 'Nhập sai định dạng dữ liệu email',
            'email.required' => 'Email là trường bắt buộc',
            'password.required' => 'Mật khẩu là trường bắt buộc',
        ]);
        $email = $request->email;
        $user = User::where('email', $email)->first();

        if(!$user){
            return back()->withErrors(['email' => 'Email không tồn tại, vui lòng kiểm tra lại'])->withInput();
        }
        if(!$user->is_active){
            return back()->withErrors(['message' => 'Tài khoản bị khoá.']);
        }
        // Kiểm tra xem người dùng có đang bị khoá k
        $attemptsKey = 'login_attempts_'. $email;
        $lockKey = 'login_lock_'. $email;
        $attempts = Cache::get($attemptsKey, 0);

        // check bị khoá
        if (Cache::has($lockKey)) {
            $lockedUntil = Cache::get($lockKey); // timestamp
            $secondsRemaining = $lockedUntil - now()->timestamp;

            if ($secondsRemaining > 0) {
                return back()->withErrors([
                    'lock_time' => 'Vui lòng chờ ' . $secondsRemaining . ' giây rồi thử lại.',
                ])->withInput();
            } else {
                if($attempts >= 6){
                    return back()->withErrors(['message'=>'Bạn nhập sai quá nhiều lần, vui lòng đặt lại mật khẩu']);
                }
                Cache::forget($lockKey);
            }
        }

        if(!Hash::check($request->password, $user->password)){
            // tăng số lần sai
            $attempts+=1;
            Cache::put($attemptsKey, $attempts, now()->addMinutes(15)); // giữ attempts trong 15'

            if($attempts === 3){
                $expiresAt = now()->addSeconds(60);
                Cache::put($lockKey,$expiresAt->timestamp, $expiresAt); // khoá sau 3 lần
            }
            if($attempts >= 6){
                return back()->withErrors(['message'=>'Bạn đã nhập sai quá nhiều lần, vui lòng đặt lại mật khẩu']);
            }

            return back()->withErrors(['password'=>'Mật khẩu không chính xác, vui lòng thử lại'])->withInput();
        }
        // Xoá cache khi mk đúng
        Cache::forget($attemptsKey);
        Cache::forget($lockKey);

        Auth::login($user);
        if($user->role === "admin"){
            Auth::logout();
            if (!$user->google2fa_secret) {
                // Chưa thiết lập 2FA → yêu cầu setup
                session(['2fa:admin:id' => $user->id]);
                return redirect()->route('2fa.setup');
            }
            session(['2fa:admin:id'=>$user->id]);
            return redirect()->route('2fa.form');
        }else{
            return redirect()->route('user.dashboard');
        }
    }

    public function show2FAForm(){
        return view('auth.2faLogin');
    }

    public function verify(Request $request){
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $userId = session('2fa:admin:id');
        $user = User::find($userId);

        if(!$user){
            return back()->withErrors(['message' => 'Không tìm thấy người dùng']);
        }

        $google2fa = new Google2FA();
        $secret = Crypt::decryptString($user->google2fa_secret);

        if($google2fa->verifyKey($secret, $request->otp)){
            Auth::login($user);
            session()->forget('2fa:admin:id');
            return redirect()->route('admin.dashboard');
        }else{
            return back()->withErrors(['otp'=>'Mã xác thực không đúng']);
        }
    }
}
