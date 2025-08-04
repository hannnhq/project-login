<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FAQRCode\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;


class TwoFAController extends Controller
{
    public function setup(Request $request){
        $userId = session('2fa:admin:id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login.form')->withErrors(['message' => 'Không tìm thấy tài khoản.']);
        }

        $google2fa = new Google2FA();
        // Nếu chưa có secret thì tạo mới
        if (!$user->google2fa_secret && !$user->is_google2fa_enabled) {
            $secret = $google2fa->generateSecretKey();
            $user->google2fa_secret = $secret;
            $user->save();
        } else {
            $secret = $user->google2fa_secret;
        }

        $user->save();
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        $writer = new Writer(new ImageRenderer(new RendererStyle(200), new SvgImageBackEnd()));
        $qrImage = 'data:image/svg+xml;base64,' . base64_encode($writer->writeString($qrCodeUrl));

        return view('auth.2faSetup', ['qrCodeUrl' => $qrImage, 'secret' => $secret, 'email' => $user->email]);
    }
    public function verifySetup(Request $request){
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $userId = session('2fa:admin:id');
        $user = User::find($userId);
        $user->is_google2fa_enabled = true;

        if (!$user) {
            return back()->withErrors(['message' => 'Không tìm thấy người dùng']);
        }

        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($user->google2fa_secret, $request->otp)) {
            // Đánh dấu 2FA đã được thiết lập
            $user->google2fa_enabled = true;
            $user->save();

            Auth::login($user);
            session()->forget('2fa:admin:id');

            return redirect()->route('admin.dashboard')->with('success', '2FA đã được thiết lập thành công!');
        } else {
            return back()->withErrors(['otp' => 'Mã xác thực không đúng, vui lòng thử lại']);
        }
    }
}
