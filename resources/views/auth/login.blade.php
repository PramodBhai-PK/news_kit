<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>एडमिन लॉगिन (CMS Login) - {{ setting('site_name', 'The LallanTop News') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ setting('favicon_url', '/images/favicon.svg') }}">

    <style>
        :root {
            --brand-primary: {{ setting('primary_color', '#E11D48') }};
            --brand-accent: {{ setting('accent_color', '#F59E0B') }};
        }
    </style>

    @vite(['resources/css/app.css', 'resources/ts/app.ts'])
</head>
<body class="min-h-screen bg-slate-950 flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Ambient glowing backgrounds -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-brand-primary/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-brand-accent/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-md w-full bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-8 shadow-2xl relative z-10">
        
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <a href="{{ route('portal.index') }}" class="inline-block transition-transform hover:scale-105 mb-4">
                <img src="{{ setting('logo_url', '/images/logo.svg') }}" alt="{{ setting('site_name') }}" class="h-12 mx-auto">
            </a>
            <h2 class="text-2xl font-black text-white tracking-tight">एडमिन पैनल लॉगिन</h2>
            <p class="text-xs text-slate-400 mt-1">सुरक्षित CMS में प्रवेश के लिए अपने क्रेडेंशियल्स दर्ज करें</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-950/60 border border-rose-800 text-rose-200 text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6 p-4 rounded-xl bg-blue-950/60 border border-blue-800 text-blue-200 text-xs font-semibold">
                {{ session('info') }}
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">ईमेल एड्रेस (Email Address)</label>
                <div class="relative">
                    <input type="email" id="email" name="email" required autofocus
                           value="{{ old('email', 'admin@newskit.com') }}"
                           placeholder="admin@newskit.com"
                           class="w-full bg-slate-950 text-white text-sm rounded-xl px-4 py-3 border border-slate-700 focus:border-brand-primary outline-none transition-all placeholder:text-slate-600">
                    <span class="absolute right-3.5 top-3.5 text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                    </span>
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">पासवर्ड (Password)</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                           value="password123"
                           placeholder="••••••••••••"
                           class="w-full bg-slate-950 text-white text-sm rounded-xl px-4 py-3 border border-slate-700 focus:border-brand-primary outline-none transition-all placeholder:text-slate-600">
                    <span class="absolute right-3.5 top-3.5 text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-slate-300">
                    <input type="checkbox" name="remember" checked class="w-4 h-4 rounded text-brand-primary bg-slate-950 border-slate-700 focus:ring-0">
                    <span>मुझे याद रखें (Remember me)</span>
                </label>
                <span class="text-amber-400 font-medium">डिमों क्रेडेंशियल रेडी</span>
            </div>

            <button type="submit" class="w-full bg-brand-primary hover:brightness-110 text-white font-extrabold py-3.5 rounded-xl transition-all shadow-lg shadow-rose-950/50 flex items-center justify-center gap-2">
                <span>सुरक्षित लॉगिन करें</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <!-- Demo info card -->
        <div class="mt-8 pt-6 border-t border-slate-800/80 bg-slate-950/60 -mx-4 -mb-4 p-4 rounded-2xl border border-slate-800 text-[11px] text-slate-400">
            <div class="flex items-center gap-2 font-bold text-amber-400 mb-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>त्वरित परीक्षण क्रेडेंशियल्स (Pre-filled):</span>
            </div>
            <p><strong>Email:</strong> admin@newskit.com | <strong>Password:</strong> password123</p>
            <p class="mt-2 text-slate-500">बिना लॉगिन के CMS का कोई भी हिस्सा एक्सेस नहीं किया जा सकता।</p>
        </div>

        <!-- Back to site link -->
        <div class="text-center mt-6">
            <a href="{{ route('portal.index') }}" class="text-xs text-slate-400 hover:text-white transition-colors flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>लाइव न्यूज़ पोर्टल पर वापस जाएं</span>
            </a>
        </div>
    </div>

</body>
</html>
