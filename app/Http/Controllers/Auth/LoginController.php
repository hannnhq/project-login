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
    public function index(Request $request){
        $email = $request->old('email'); // Lấy lại email đã nhập nếu có

        if ($email) {
            $lockKey = 'login_lock_' . $email;

            if (Cache::has($lockKey)) {
                $lockedUntil = Cache::get($lockKey); // timestampt
                $secondsRemaining = $lockedUntil - now()->timestamp;

                if ($secondsRemaining > 0) {
                    session()->flash('lock_time', "Vui lòng chờ $secondsRemaining giây rồi thử lại.");
                } else {
                    Cache::forget($lockKey); // hết hạn thì xóa luôn
                }
            }
        }
        return view('auth.login');
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

        if($user->is_active === 0){
            return back()->withErrors(['message' => 'Tài khoản của bạn đã bị khoá']);
        }
        // Kiểm tra người dùng đã xác thực email
        if (is_null($user->email_verified_at)) {
            return back()->withErrors(['message' => 'Tài khoản chưa xác thực email. Vui lòng kiểm tra email để xác nhận tài khoản.'])->withInput();
        }

        // tạo cache key
        $attemptsKey = 'login_attempts_'. $email; // Đếm số lần sai
        $lockKey = 'login_lock_'. $email; // Khóa tạm thời
        $attempts = Cache::get($attemptsKey, 0); // Lấy số lần sai, mặc định là 0

        // kiểm tra có đang bị khoá
        if (Cache::has($lockKey)) {
            $lockedUntil = Cache::get($lockKey); // timestamp
            $secondsRemaining = $lockedUntil - now()->timestamp;

            if ($secondsRemaining > 0) {
                return back()->withErrors([
                    'lock_time' => 'Vui lòng chờ 60 giây rồi thử lại.',
                ])->withInput();
            } else {
                Cache::forget($lockKey);
            }
        }

        if(!Hash::check($request->password, $user->password)){
            // tăng số lần sai
            $attempts+=1;
            Cache::put($attemptsKey, $attempts, now()->addMinutes(15)); // giữ attempts trong 15'

            if($attempts === 3){
                $expiresAt = now()->addSeconds(60);
                Cache::put($lockKey,$expiresAt->timestamp, 60); // khoá sau 3 lần
            }
            if($attempts >= 6){
                Cache::put($lockKey, now()->addMinutes(30)->timestamp, 1800); // khoá 30'
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
                session(['2fa:admin:id' => $user->id]);

            if (!$user->google2fa_secret) {
                // Chưa thiết lập 2FA → yêu cầu setup
                return redirect()->route('2fa.setup');
            }
            session(['2fa:admin:id'=>$user->id]);
            return redirect()->route('2fa.form');
        }else{
            return redirect()->route('user.home')->with('success','Đăng nhập thành công');
        }
    }

    public function show2FAForm(){
        return view('auth.2faLogin');
    }

    public function verify(Request $request){
        $request->validate([
            'otp' => 'required|digits:6'
        ],[
            'otp.required' =>'Mã xác thực không để trống',
            'otp.digits' =>'Mã xác thực gồm 6 số',
        ]);

        $userId = session('2fa:admin:id');
        $user = User::find($userId);

        if(!$user){
            return back()->withErrors(['message' => 'Không tìm thấy người dùng']);
        }

        $google2fa = new Google2FA();

        if($google2fa->verifyKey($user->google2fa_secret, $request->otp)){
            Auth::login($user);
            session()->forget('2fa:admin:id');
            return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập admin thành công');
        }else{
            return back()->withErrors(['otp'=>'Mã xác thực không đúng']);
        }
    }
}
