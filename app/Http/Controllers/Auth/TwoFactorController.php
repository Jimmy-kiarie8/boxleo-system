<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    public function showSetupForm()
    {
        $google2fa = new Google2FA();

        // Generate a secret key for the user
        $secret = $google2fa->generateSecretKey();

        // Store the secret key in the user's profile (assuming you have a `google2fa_secret` column)
        $user = Auth::user();
        $user->google2fa_secret = $secret;
        $user->save();

        // Generate the QR code URL
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        // Generate the QR code image
        $renderer = new ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(400),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);

        return view('auth.2fa_enable', ['qrCodeSvg' => $qrCodeSvg, 'secret' => $secret]);
    }

    public function verify(Request $request)
    {
        $google2fa = new Google2FA();

        $user = Auth::user();
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->input('2fa_code'));

        if ($valid) {
            // 2FA code is valid
            return redirect('/')->with('success', '2FA has been enabled.');
        } else {
            // 2FA code is invalid
            return back()->withErrors(['2fa_code' => 'The provided 2FA code is invalid.']);
        }
    }


    public function showChallenge()
    {
        return view('auth.2fa_challenge');
    }

    public function verifyChallenge(Request $request)
    {
        $user = Auth::user();
        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->input('2fa_code'));

        if ($valid) {
            session(['2fa_verified' => true]);

            return redirect()->intended();
        } else {
            return redirect()->back()->withErrors(['2fa_code' => 'The provided 2FA code is invalid.']);
        }
    }
}
