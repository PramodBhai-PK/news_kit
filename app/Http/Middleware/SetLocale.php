<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported locales and their RTL attributes
     * Default locale is 'en' (English), tailored for international commercial release (e.g. CodeCanyon)
     */
    public const SUPPORTED_LOCALES = [
        'en' => ['name' => 'English', 'native' => 'English', 'dir' => 'ltr', 'flag' => '🌐'],
        'hi' => ['name' => 'हिंदी', 'native' => 'हिंदी', 'dir' => 'ltr', 'flag' => '🇮🇳'],
        'ur' => ['name' => 'Urdu', 'native' => 'اردو', 'dir' => 'rtl', 'flag' => '🇵🇰'],
        'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'dir' => 'rtl', 'flag' => '🇦🇪'],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $defaultLocale = config('app.locale', 'en');
        $locale = $request->query('lang', Session::get('locale', $defaultLocale));

        if (!array_key_exists($locale, self::SUPPORTED_LOCALES)) {
            $locale = $defaultLocale;
        }

        Session::put('locale', $locale);
        App::setLocale($locale);

        $isRtl = in_array($locale, ['ur', 'ar'], true);
        $dir = $isRtl ? 'rtl' : 'ltr';

        View::share('currentLocale', $locale);
        View::share('isRtl', $isRtl);
        View::share('dir', $dir);
        View::share('supportedLocales', self::SUPPORTED_LOCALES);

        return $next($request);
    }
}
