<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - {{ setting('site_name', 'The LallanTop News') }} CMS</title>
    <link rel="icon" type="image/svg+xml" href="{{ setting('favicon_url', '/images/favicon.svg') }}">

    <!-- Google Fonts (Devanagari & Outfit) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Dynamic Branding Customization via CSS Variables -->
    <style>
        :root {
            --brand-primary: {{ setting('primary_color', '#E11D48') }};
            --brand-accent: {{ setting('accent_color', '#F59E0B') }};
        }
    </style>

    @vite(['resources/css/app.css', 'resources/ts/app.ts'])
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex antialiased">

    <!-- 1. Left Sidebar Navigation -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 min-h-screen border-r border-slate-800">
        <!-- Brand identity from single setting -->
        <div class="h-16 px-6 flex items-center gap-3 border-b border-slate-800">
            <img src="{{ setting('logo_url', '/images/logo.svg') }}" alt="{{ setting('site_name') }}" class="h-8 w-auto">
            <div>
                <span class="block font-black text-white text-base tracking-tight leading-tight">{{ setting('site_name', 'NewsKit') }}</span>
                <span class="block text-[10px] font-bold text-amber-400 tracking-wider uppercase">CMS Panel</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 text-sm font-medium overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-brand-primary text-white font-semibold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>डैशबोर्ड (Dashboard)</span>
            </a>

            <div class="pt-4 pb-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider px-3">
                {{ Auth::user()->isReporter() ? 'मेरी रिपोर्टिंग' : 'कंटेंट मैनेजमेंट' }}
            </div>

            <!-- Articles (Scoped to own articles for reporters) -->
            <a href="{{ route('admin.articles.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.articles.*') && !request()->routeIs('admin.articles.create') ? 'bg-brand-primary text-white font-semibold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                <span>{{ Auth::user()->isReporter() ? 'मेरी ख़बरें (My Stories)' : 'सभी ख़बरें (Articles)' }}</span>
            </a>

            <!-- Write New Story -->
            <a href="{{ route('admin.articles.create') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.articles.create') ? 'bg-brand-primary text-white font-semibold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>नई ख़बर लिखें (Add Story)</span>
            </a>

            <!-- Categories (Visible to Admin, Editor, Publisher) -->
            @if(!Auth::user()->isReporter())
                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-brand-primary text-white font-semibold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span>कैटेगरी (Categories)</span>
                </a>
            @endif

            <!-- Citizen News Tips (Accessible to Admin, Editor, Publisher) -->
            @if(!Auth::user()->isReporter())
                <a href="{{ route('admin.citizen-tips.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.citizen-tips.*') ? 'bg-brand-primary text-white font-semibold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <div class="flex-1 flex items-center justify-between">
                        <span>जनता न्यूज़ टिप्स</span>
                        @php
                            $pendingTipsCount = \App\Models\CitizenTip::where('status', 'pending')->count();
                        @endphp
                        @if($pendingTipsCount > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-500 text-white animate-pulse">
                                {{ $pendingTipsCount }}
                            </span>
                        @endif
                    </div>
                </a>
            @endif

            <!-- Comments (Scoped to own articles for reporters) -->
            <a href="{{ route('admin.comments.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.comments.*') ? 'bg-brand-primary text-white font-semibold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span>{{ Auth::user()->isReporter() ? 'मेरी ख़बरों पर टिप्पणियां' : 'टिप्पणियां (Comments)' }}</span>
            </a>

            <!-- Advertisements Manager (Visible to Admin, Editor, Publisher) -->
            @if(!Auth::user()->isReporter())
                <a href="{{ route('admin.advertisements.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.advertisements.*') ? 'bg-brand-primary text-white font-semibold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    <span>विज्ञापन (Ads Manager)</span>
                </a>

                <!-- Newsletter Subscribers -->
                <a href="{{ route('admin.subscribers.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.subscribers.*') ? 'bg-brand-primary text-white font-semibold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>न्यूज़लेटर ग्राहक (Subscribers)</span>
                </a>
            @endif

            <div class="pt-4 pb-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider px-3">
                {{ Auth::user()->isAdmin() || Auth::user()->isEditor() ? 'टीम व कंट्रोल सेंटर' : 'अकाउंट' }}
            </div>

            <!-- Team & Roles Management (Admins & Editors) -->
            @if(Auth::user()->isAdmin() || Auth::user()->isEditor())
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-brand-primary text-white font-semibold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <div class="flex-1">
                        <span class="block">टीम व यूज़र्स</span>
                        <span class="block text-[10px] text-purple-300">Reporters & Editors</span>
                    </div>
                </a>
            @endif

            <!-- Live Theme Customizer & Real-time Studio (Super Admin Only) -->
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.theme.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.theme.*') ? 'bg-brand-primary text-white font-semibold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4 4.5 4.5 0 011.5-3.322l11-11a2.121 2.121 0 013 3L6.828 17.172A4.5 4.5 0 013 21z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7l2 2"/></svg>
                    <div class="flex-1">
                        <span class="block">थीम कलर स्टूडियो</span>
                        <span class="block text-[10px] text-rose-300">Live Preview & Publish</span>
                    </div>
                </a>

                <!-- Automatic News Poster & Graphic Card Studio -->
                <button type="button" onclick="window.openNewsPosterStudio()" 
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-slate-400 hover:text-white hover:bg-slate-800 text-left">
                    <svg class="w-5 h-5 shrink-0 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <div class="flex-1">
                        <span class="block">ऑटो न्यूज़ पोस्टर</span>
                        <span class="block text-[10px] text-purple-300">Graphic Card Maker</span>
                    </div>
                </button>

                <a href="{{ route('admin.settings.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-brand-primary text-white font-semibold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <div class="flex-1">
                        <span class="block">ब्रांडिंग व सेटिंग्स</span>
                        <span class="block text-[10px] text-amber-300">Single-File Hub</span>
                    </div>
                </a>
            @endif

            <!-- Self-Service Profile / Account (Accessible to ALL logged in users) -->
            <a href="{{ route('admin.profile.edit') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.profile.edit') ? 'bg-brand-primary text-white font-semibold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>मेरा अकाउंट (Profile)</span>
            </a>

            <!-- Official Digital Press ID Card (Accessible to ALL logged in staff) -->
            <a href="{{ route('admin.profile.press-card') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.profile.press-card') ? 'bg-brand-primary text-white font-semibold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                <div class="flex-1">
                    <span class="block">डिजिटल प्रेस कार्ड</span>
                    <span class="block text-[10px] text-emerald-300">QR ID Verification</span>
                </div>
            </a>
        </nav>

        <!-- View Live Portal Quick Button -->
        <div class="p-4 border-t border-slate-800">
            <a href="{{ route('portal.index') }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-2 px-3 rounded-lg bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold transition-all">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>लाइव न्यूज़ पोर्टल देखें</span>
            </a>
        </div>
    </aside>

    <!-- 2. Main Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Top Navigation Bar -->
        <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between shadow-xs">
            <div>
                <h1 class="text-lg font-bold text-slate-800">@yield('page_title', 'डैशबोर्ड')</h1>
            </div>

            <div class="flex items-center gap-4">
                <!-- User Profile Quick Badge & Link -->
                <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-slate-50 transition-colors group">
                    <img src="{{ Auth::user()->avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" 
                         alt="{{ Auth::user()->name }}" 
                         class="w-9 h-9 rounded-full object-cover ring-2 ring-brand-primary">
                    <div class="hidden sm:block text-left">
                        <span class="block text-xs font-bold text-slate-900 group-hover:text-brand-primary transition-colors leading-tight">{{ Auth::user()->name }}</span>
                        <span class="inline-block text-[10px] font-bold px-1.5 py-0.2 rounded border {{ Auth::user()->role_badge_class }}">
                            {{ Auth::user()->role_label }}
                        </span>
                    </div>
                </a>

                <!-- Logout Form -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="लॉग आउट करें">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </header>

        <!-- Flash Alert Messages -->
        <div class="p-6 pb-0">
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 mb-6 shadow-xs">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-3 mb-6 shadow-xs">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 mb-6 shadow-xs">
                    <ul class="list-disc list-inside text-sm font-semibold space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Admin Content Area -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <!-- Automatic News Poster & Graphic Card Studio Modal -->
    @include('components.news-poster-modal')

</body>
</html>
