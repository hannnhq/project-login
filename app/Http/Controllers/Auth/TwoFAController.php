<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use PragmaRX\Google2FA\Google2FA;

class TwoFAController extends Controller
{
    public function setup(Request $request){
        $userId = session('2fa:admin:id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login.form')->withErrors(['message' => 'Không tìm thấy tài khoản.']);
        }

        $google2fa = new Google2FA();

        $secret = $google2fa->generateSecretKey();

        $user->google2fa_secret = Crypt::encryptString($secret);
        $user->save();

        // tạo qr code
        $qrCode = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );
        // Render thành ảnh SVG
        $writer = new Writer(
            new ImageRenderer(
                new RendererStyle(200),
                new SvgImageBackEnd()
            )
            );

        $qrImage = 'data:image/svg+xml;base64,' . base64_encode($writer->writeString($qrCode));
        return view('auth.2faSetup',[
            'qrImage' => $qrImage,
            'secret' => $secret,
        ]);
    }
}
