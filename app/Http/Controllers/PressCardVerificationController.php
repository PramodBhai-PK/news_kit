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
        $siteName = setting('site_name', 'NewsKit Portal');
        $tagline = setting('site_tagline', 'Truthful, Independent & Fast News');

        return view('portal.press-verify', compact('user', 'isValid', 'siteName', 'tagline'));
    }
}
