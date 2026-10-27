<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PressCardVerificationController extends Controller
{
    /**
     * Public QR Code Verification Landing Page for Police & Officials
     */
    public function verify(string $code)
    {
        $user = User::where('press_id_code', $code)->firstOrFail();

        // Ensure default press card properties are populated
        $user->ensurePressCardInitialized();

        $isValid = $user->hasValidPressCard();
        $siteName = setting('site_name', 'The LallanTop News');
        $tagline = setting('site_tagline', 'बेबाक, सटीक और सबसे तेज़ ख़बरें');

        return view('portal.press-verify', compact('user', 'isValid', 'siteName', 'tagline'));
    }
}
