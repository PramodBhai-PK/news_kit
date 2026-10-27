<!DOCTYPE html>
<html lang="{{ $currentLocale ?? app()->getLocale() }}" dir="{{ $dir ?? 'ltr' }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', setting('site_name', 'The LallanTop News')) - {{ setting('site_tagline', 'बेबाक, सटीक और सबसे तेज़ ख़बरें') }}</title>
    
    <meta name="description" content="@yield('meta_description', setting('meta_description', 'देश और दुनिया की ताज़ा ख़बरें सबसे पहले।'))">
    <link rel="icon" type="image/svg+xml" href="{{ setting('favicon_url', '/images/favicon.svg') }}">

    <!-- Google News, Google Discover & Search Engine Directives -->
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot-news" content="index, follow">

    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    <!-- Multi-Language SEO (Hreflang Tags for International Targeting) -->
    <link rel="alternate" hreflang="hi" href="{{ request()->fullUrlWithQuery(['lang' => 'hi']) }}">
    <link rel="alternate" hreflang="en" href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}">
    <link rel="alternate" hreflang="ur" href="{{ request()->fullUrlWithQuery(['lang' => 'ur']) }}">
    <link rel="alternate" hreflang="ar" href="{{ request()->fullUrlWithQuery(['lang' => 'ar']) }}">
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">

    <!-- RSS & News Feed Discovery for Feed Readers / Aggregators -->
    <link rel="alternate" type="application/rss+xml" title="{{ setting('site_name', 'The LallanTop News') }} RSS Feed" href="{{ route('portal.feed') }}">

    <!-- Open Graph (Facebook, WhatsApp, LinkedIn, Telegram) -->
    <meta property="og:site_name" content="{{ setting('site_name', 'The LallanTop News') }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', setting('site_name', 'The LallanTop News'))">
    <meta property="og:description" content="@yield('meta_description', setting('meta_description', 'देश और दुनिया की ताज़ा ख़बरें सबसे पहले।'))">
    <meta property="og:url" content="@yield('canonical_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset(setting('logo_url', '/images/logo.svg')))">
    <meta property="og:image:alt" content="@yield('title', setting('site_name'))">
    <meta property="og:locale" content="{{ ($currentLocale ?? 'hi') === 'hi' ? 'hi_IN' : (($currentLocale ?? '') === 'en' ? 'en_US' : (($currentLocale ?? '') === 'ur' ? 'ur_PK' : 'ar_SA')) }}">

    <!-- Twitter / X Cards (Large Image Preview for Viral Sharing) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', setting('site_name', 'The LallanTop News'))">
    <meta name="twitter:description" content="@yield('meta_description', setting('meta_description'))">
    <meta name="twitter:image" content="@yield('og_image', asset(setting('logo_url', '/images/logo.svg')))">

    @yield('extra_seo')

    <!-- Google Fonts for Multi-Language & RTL (Arabic / Urdu / Hindi / English) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Noto+Nastaliq+Urdu:wght@400;500;600;700&family=Noto+Sans+Arabic:wght@400;500;600;700;800&family=Noto+Sans+Devanagari:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

    <!-- Dynamic Branding Customization via CSS Variables & Live Studio Real-Time Support -->
    <style id="portal-dynamic-theme">
        :root {
            --brand-primary: {{ request('preview_primary') ?: setting('primary_color', '#E11D48') }};
            --brand-accent: {{ request('preview_accent') ?: setting('accent_color', '#F59E0B') }};
        }
    </style>
    <script>
        // Real-Time Theme Customizer Event Listener (Parent Admin Studio Bridge)
        window.addEventListener('message', function (e) {
            if (!e.data) return;
            if (e.data.type === 'PORTAL_THEME_UPDATE') {
                if (e.data.primary) document.documentElement.style.setProperty('--brand-primary', e.data.primary);
                if (e.data.accent) document.documentElement.style.setProperty('--brand-accent', e.data.accent);
            } else if (e.data.type === 'PORTAL_THEME_DARKMODE') {
                if (e.data.darkMode) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            }
        });
    </script>

    @vite(['resources/css/app.css', 'resources/ts/app.ts'])
</head>
<body class="min-h-screen flex flex-col transition-colors duration-300">

    <!-- Reading Progress Bar (Fixed on Top) -->
    <div id="reading-progress-bar" class="fixed top-0 {{ ($isRtl ?? false) ? 'right-0' : 'left-0' }} h-1 z-50 transition-all duration-150 brand-gradient" style="width: 0%;"></div>

    <!-- 1. Top Bar: Live Clock, Breaking Ticker & Language/RTL Switcher -->
    <header class="border-b border-slate-200 dark:border-slate-800 bg-slate-900 text-slate-100 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-9 gap-4">
                <!-- Live Clock -->
                <div class="hidden sm:flex items-center gap-2 text-slate-400 font-medium shrink-0">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 live-pulse"></span>
                    <span id="live-clock" class="tracking-wide">{{ __('लोड हो रहा है...') }}</span>
                </div>

                <!-- Breaking News Ticker if enabled -->
                @if(setting('breaking_ticker_enabled', true) && isset($breakingNews) && $breakingNews->isNotEmpty())
                    <div id="news-ticker" class="flex-1 flex items-center overflow-hidden h-7 max-w-3xl">
                        <span class="bg-brand-primary text-white font-extrabold px-2.5 py-0.5 rounded text-[11px] uppercase tracking-wider flex items-center gap-1.5 shrink-0 {{ ($isRtl ?? false) ? 'ml-3' : 'mr-3' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-white live-pulse"></span>
                            {{ setting('breaking_ticker_title') ? __(setting('breaking_ticker_title')) : __('ताज़ा ख़बर') }}
                        </span>
                        <div id="ticker-items" class="flex-1 overflow-hidden relative h-5">
                            @foreach($breakingNews as $index => $item)
                                <a href="{{ route('portal.article', $item->slug) }}" 
                                   class="ticker-item truncate hover:text-amber-400 transition-colors inline-flex items-center gap-2 text-slate-200 font-medium"
                                   style="{{ $index === 0 ? '' : 'display:none;' }}">
                                    <span class="text-amber-400 font-bold">•</span>
                                    {{ $item->title }}
                                </a>
                            @endforeach
                        </div>
                        <div class="hidden md:flex items-center gap-1 {{ ($isRtl ?? false) ? 'mr-2' : 'ml-2' }} shrink-0">
                            <button id="ticker-prev" aria-label="{{ __('पिछली खबर') }}" class="p-1 hover:text-white text-slate-400 hover:bg-slate-800 rounded rtl-flip">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button id="ticker-next" aria-label="{{ __('अगली खबर') }}" class="p-1 hover:text-white text-slate-400 hover:bg-slate-800 rounded rtl-flip">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Hyperlocal City Switcher Trigger Button -->
                <div class="relative shrink-0 flex items-center gap-2">
                    <button id="city-modal-trigger" type="button"
                            class="flex items-center gap-1.5 py-1 px-2.5 rounded-md bg-linear-to-r from-rose-600/25 to-amber-600/25 hover:from-rose-600/35 hover:to-amber-600/35 text-rose-300 hover:text-white transition-all text-xs font-semibold border border-rose-500/30 shadow-xs focus:outline-none">
                        <span class="text-xs">📍</span>
                        <span class="tracking-wide font-bold truncate max-w-[110px] sm:max-w-[140px]">
                            {{ !empty($userCityName) ? $userCityName : __('अपना शहर चुनें') }}
                        </span>
                        @if(!empty($userCitySlug))
                            <a href="{{ route('portal.set-city', 'clear') }}" title="{{ __('शहर हटाएं') }}" onclick="event.stopPropagation();" class="text-rose-400 hover:text-white ml-0.5 p-0.5 rounded hover:bg-rose-500/30">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        @else
                            <svg class="w-3 h-3 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        @endif
                    </button>

                    <!-- Citizen Journalism News Tip Button -->
                    <a href="{{ route('portal.citizen-tip') }}" 
                       class="flex items-center gap-1 py-1 px-2.5 rounded-md bg-rose-600 hover:bg-rose-700 text-white transition-all text-xs font-bold shadow-xs">
                        <span>📢</span>
                        <span class="hidden sm:inline">{{ __('ख़बर भेजें') }}</span>
                    </a>
                </div>

                <!-- Multi-Language & RTL Switcher Dropdown -->
                <div class="relative shrink-0" id="lang-switcher-wrapper">
                    <button id="lang-switcher-btn" type="button" aria-expanded="false"
                            class="flex items-center gap-1.5 py-1 px-2.5 rounded-md bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white transition-colors text-xs font-semibold border border-slate-700/80 shadow-xs focus:outline-none">
                        <span class="text-sm leading-none">{{ $supportedLocales[$currentLocale ?? 'hi']['flag'] ?? '🇮🇳' }}</span>
                        <span class="tracking-wide">{{ $supportedLocales[$currentLocale ?? 'hi']['native'] ?? 'हिंदी' }}</span>
                        @if($isRtl ?? false)
                            <span class="bg-amber-500/20 text-amber-300 text-[9px] font-black px-1 py-0.5 rounded uppercase tracking-wider">RTL</span>
                        @endif
                        <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" id="lang-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="lang-dropdown-menu" 
                         class="hidden absolute {{ ($isRtl ?? false) ? 'left-0' : 'right-0' }} mt-1.5 w-48 rounded-xl bg-slate-900 border border-slate-700 shadow-2xl py-1.5 z-50 text-xs divide-y divide-slate-800/80">
                        <div class="px-3 py-1.5 text-[10px] uppercase font-bold tracking-wider text-slate-400">
                            {{ __('भाषा चुनें') }}
                        </div>
                        <div class="py-1">
                            @foreach($supportedLocales as $locCode => $locMeta)
                                <a href="{{ route('portal.locale', $locCode) }}" 
                                   class="flex items-center justify-between px-3 py-2 hover:bg-slate-800 transition-colors {{ ($currentLocale ?? 'hi') === $locCode ? 'text-brand-primary bg-slate-800/70 font-bold' : 'text-slate-200' }}">
                                    <div class="flex items-center gap-2">
                                        <span class="text-base leading-none">{{ $locMeta['flag'] }}</span>
                                        <span>{{ $locMeta['native'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        @if($locMeta['dir'] === 'rtl')
                                            <span class="text-[9px] font-bold px-1 py-0.5 rounded bg-amber-500/20 text-amber-300">RTL</span>
                                        @endif
                                        @if(($currentLocale ?? 'hi') === $locCode)
                                            <svg class="w-3.5 h-3.5 text-brand-primary" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- 2. Main Branding Header: Logo + Navigation Menus + Search -->
    <div class="glass-header sticky top-0 z-40 border-b border-slate-200/80 dark:border-slate-800/80 transition-all shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5">
            <div class="flex items-center justify-between gap-2 xl:gap-4">
                
                <!-- Logo & Site Name -->
                <div class="flex items-center gap-2.5 shrink-0">
                    <button id="mobile-menu-btn" aria-label="Menu" class="lg:hidden p-2 rounded-lg text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <a href="{{ route('portal.index') }}" class="flex items-center gap-2 group">
                        <img src="{{ setting('logo_url', '/images/logo.svg') }}" 
                             alt="{{ setting('site_name', 'The LallanTop News') }}" 
                             class="h-9 sm:h-10 w-auto object-contain transition-transform group-hover:scale-105">
                        <div>
                            <span class="block text-lg sm:text-xl font-black tracking-tight text-slate-900 dark:text-white group-hover:text-brand-primary transition-colors leading-none">
                                {{ setting('site_name', 'The LallanTop News') }}
                            </span>
                            <span class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400 leading-tight mt-0.5 hidden 2xl:block truncate max-w-[160px]">
                                {{ setting('site_tagline', 'बेबाक, सटीक और सबसे तेज़ ख़बरें') }}
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Center: Category Navigation Menus + State & City Hub Dropdown -->
                <nav class="hidden lg:flex items-center gap-1 text-xs font-bold py-1 flex-1 justify-center relative">
                    <a href="{{ route('portal.index') }}" 
                       class="px-2.5 py-1.5 rounded-lg whitespace-nowrap transition-colors {{ request()->routeIs('portal.index') ? 'bg-brand-primary text-white shadow-xs' : 'text-slate-700 dark:text-slate-300 hover:text-brand-primary hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                        {{ __('होम') }}
                    </a>

                    <!-- State & City Hyperlocal Mega Dropdown -->
                    @if(isset($portalStates) && $portalStates->isNotEmpty())
                    <div class="relative group/state-nav" id="state-city-dropdown-wrapper">
                        <button type="button" id="state-city-dropdown-btn"
                                class="px-2.5 py-1.5 rounded-lg whitespace-nowrap transition-colors flex items-center gap-1.5 {{ request()->routeIs('portal.state') || request()->routeIs('portal.city') ? 'bg-brand-primary text-white shadow-xs' : 'text-slate-700 dark:text-slate-300 hover:text-brand-primary hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                            <span class="text-rose-500">📍</span>
                            <span>{{ __('राज्य व शहर') }}</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover/state-nav:rotate-180 transition-transform duration-200" id="state-city-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu with bridging wrapper -->
                        <div id="state-city-dropdown-menu" 
                             class="hidden group-hover/state-nav:block absolute {{ ($isRtl ?? false) ? 'right-0' : 'left-0' }} top-full pt-1.5 w-84 sm:w-96 z-50 animate-in fade-in slide-in-from-top-1 duration-150">
                            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl p-3.5 text-left">
                                <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-100 dark:border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                    <span>{{ __('राज्य विशेष') }}</span>
                                    <button type="button" onclick="document.getElementById('city-modal-trigger').click();" class="text-brand-primary hover:underline font-bold normal-case">
                                        {{ __('सभी 48+ शहर खोजें →') }}
                                    </button>
                                </div>
                                <div class="space-y-1.5 max-h-84 overflow-y-auto pr-1">
                                    @foreach($portalStates as $st)
                                        <div class="p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/80 transition-colors border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
                                            <a href="{{ route('portal.state', $st->slug) }}" class="font-extrabold text-xs text-slate-900 dark:text-white hover:text-brand-primary flex items-center justify-between">
                                                <span class="flex items-center gap-1.5">
                                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                                    <span>{{ $st->display_name }}</span>
                                                </span>
                                                <span class="text-[10px] text-slate-400 font-semibold">{{ $st->cities->count() }} {{ __('जिले') }} →</span>
                                            </a>
                                            @if($st->cities->isNotEmpty())
                                                <div class="flex flex-wrap gap-1 mt-1.5 pl-3.5">
                                                    @foreach($st->cities->take(5) as $c)
                                                        <a href="{{ route('portal.city', $c->slug) }}" class="text-[10px] text-slate-500 dark:text-slate-400 hover:text-brand-primary hover:underline">
                                                            {{ $c->display_name }}
                                                        </a>
                                                        @if(!$loop->last)<span class="text-[10px] text-slate-300 dark:text-slate-600">•</span>@endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(isset($categories))
                        @foreach($categories as $cat)
                            <a href="{{ route('portal.category', $cat->slug) }}" 
                               class="px-2.5 py-1.5 rounded-lg whitespace-nowrap transition-colors flex items-center gap-1.5 {{ (isset($category) && $category->id === $cat->id) ? 'bg-brand-primary text-white shadow-xs' : 'text-slate-700 dark:text-slate-300 hover:text-brand-primary hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $cat->color }};"></span>
                                {{ $cat->display_name }}
                            </a>
                        @endforeach
                    @endif
                </nav>

                <!-- Right: Compact Instant Search -->
                <div class="flex items-center gap-1.5 shrink-0">
                    <!-- Mobile Search Icon Only Toggle Button -->
                    <button id="mobile-search-toggle" type="button" aria-label="{{ __('खोजें') }}" 
                            class="sm:hidden p-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                    <!-- Desktop / Tablet Search Input Form: Compact by default, smoothly expands on focus -->
                    <div class="hidden sm:block">
                        <form action="{{ route('portal.search') }}" method="GET" class="relative w-36 lg:w-44 xl:w-52 focus-within:w-60 transition-all duration-300">
                            <input type="text" name="q" placeholder="{{ __('खबरें खोजें...') }}" 
                                   value="{{ request('q') }}"
                                   class="w-full bg-slate-100 dark:bg-slate-800/80 text-slate-900 dark:text-slate-100 text-xs rounded-full {{ ($isRtl ?? false) ? 'pr-8 pl-3' : 'pl-8 pr-3' }} py-1.5 border border-transparent focus:border-brand-primary focus:bg-white dark:focus:bg-slate-900 outline-none transition-all shadow-inner">
                            <span class="absolute {{ ($isRtl ?? false) ? 'right-2.5' : 'left-2.5' }} top-2 text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <!-- Mobile Dropdown Search Input Bar -->
        <div id="mobile-search-dropdown" class="hidden sm:hidden border-t border-slate-200/90 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur px-4 py-2.5 shadow-sm">
            <form action="{{ route('portal.search') }}" method="GET" class="relative w-full flex items-center">
                <input type="text" id="mobile-search-input" name="q" placeholder="{{ __('खबरें खोजें (Search stories)...') }}" 
                       value="{{ request('q') }}"
                       class="w-full bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs rounded-full {{ ($isRtl ?? false) ? 'pr-9 pl-9' : 'pl-9 pr-9' }} py-2 border border-slate-200 dark:border-slate-700 focus:border-brand-primary outline-none">
                <span class="absolute {{ ($isRtl ?? false) ? 'right-3' : 'left-3' }} top-2.5 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <button type="button" id="mobile-search-close" aria-label="Close" class="absolute {{ ($isRtl ?? false) ? 'left-2.5' : 'right-2.5' }} top-2 p-0.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Mobile Navigation Drawer (RTL Aware) -->
    <div id="mobile-menu-drawer" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm lg:hidden">
        <div class="fixed inset-y-0 {{ ($isRtl ?? false) ? 'right-0' : 'left-0' }} max-w-xs w-full bg-white dark:bg-slate-900 shadow-2xl p-6 flex flex-col justify-between overflow-y-auto">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
                    <span class="font-bold text-lg text-slate-900 dark:text-white">{{ setting('site_name') }}</span>
                    <button id="mobile-menu-close" aria-label="Close menu" class="p-1 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Language Selector in Mobile Drawer -->
                <div class="mt-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                    <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">{{ __('भाषा चुनें') }}</span>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($supportedLocales as $locCode => $locMeta)
                            <a href="{{ route('portal.locale', $locCode) }}" 
                               class="flex items-center justify-center gap-1.5 py-1.5 px-2 rounded-lg text-xs font-semibold border transition-colors {{ ($currentLocale ?? 'hi') === $locCode ? 'bg-brand-primary text-white border-brand-primary' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-transparent hover:border-slate-300 dark:hover:border-slate-700' }}">
                                <span>{{ $locMeta['flag'] }}</span>
                                <span>{{ $locMeta['native'] }}</span>
                                @if($locMeta['dir'] === 'rtl')
                                    <span class="text-[9px] opacity-75 font-bold">RTL</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <form action="{{ route('portal.search') }}" method="GET" class="mt-4">
                    <input type="text" name="q" placeholder="{{ __('खबरें खोजें...') }}" 
                           class="w-full bg-slate-100 dark:bg-slate-800 text-sm rounded-lg px-3 py-2 border border-slate-300 dark:border-slate-700">
                </form>

                <div class="mt-6 flex flex-col gap-1">
                    <a href="{{ route('portal.index') }}" class="px-3 py-2 rounded-lg font-semibold text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
                        {{ __('होम') }}
                    </a>

                    <!-- City Selection in Mobile Menu -->
                    <button type="button" onclick="document.getElementById('mobile-menu-close').click(); document.getElementById('city-modal-trigger').click();" 
                            class="px-3 py-2 rounded-lg font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 flex items-center justify-between text-left">
                        <span class="flex items-center gap-2">
                            <span>📍</span>
                            <span>{{ !empty($userCityName) ? $userCityName : __('अपना शहर चुनें') }}</span>
                        </span>
                        <span class="text-xs bg-rose-100 dark:bg-rose-900/60 px-2 py-0.5 rounded font-medium">बदलें</span>
                    </button>

                    @if(isset($portalStates) && $portalStates->isNotEmpty())
                        <div class="pt-2 pb-1 px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            {{ __('राज्य व शहर') }}
                        </div>
                        <div class="grid grid-cols-2 gap-1 px-2 mb-2">
                            @foreach($portalStates as $st)
                                <a href="{{ route('portal.state', $st->slug) }}" class="px-2.5 py-1.5 rounded-md text-xs font-medium text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-800 hover:text-brand-primary">
                                    {{ $st->display_name }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <div class="pt-2 pb-1 px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        {{ __('श्रेणियां') }}
                    </div>
                    @if(isset($categories))
                        @foreach($categories as $cat)
                            <a href="{{ route('portal.category', $cat->slug) }}" class="px-3 py-2 rounded-lg font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $cat->color }}"></span>
                                {{ $cat->display_name }}
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="pt-6 border-t border-slate-200 dark:border-slate-800 text-xs text-slate-500">
                <p>{{ setting('copyright_text') ?: __('सभी अधिकार सुरक्षित।') }}</p>
            </div>
        </div>
    </div>

    <!-- Flash Alert Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('city_success'))
            <div class="p-4 rounded-xl bg-linear-to-r from-rose-50 to-orange-50 dark:from-rose-950/60 dark:to-orange-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 flex items-center justify-between gap-3 shadow-sm">
                <div class="flex items-center gap-2.5">
                    <span class="text-lg">📍</span>
                    <span class="text-sm font-bold">{{ session('city_success') }}</span>
                </div>
                <a href="{{ route('portal.set-city', 'clear') }}" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">
                    {{ __('रीसेट करें') }}
                </a>
            </div>
        @endif
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="text-sm font-semibold">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span class="text-sm font-semibold">{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- Main Content Body -->
    <main class="flex-1 py-6">
        @yield('content')
    </main>

    <!-- Ultra-Rich Modern Footer -->
    <footer class="bg-slate-950 text-slate-300 pt-14 pb-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-slate-800/80">
                <!-- Col 1: About & Logo -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ setting('logo_url', '/images/logo.svg') }}" alt="{{ setting('site_name') }}" class="h-10 w-auto">
                        <span class="font-extrabold text-xl text-white">{{ setting('site_name') }}</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        {{ setting('footer_about', 'NewsKit Bharat का सबसे विश्वसनीय और निष्पक्ष डिजिटल न्यूज़ नेटवर्क है।') }}
                    </p>
                    <div class="pt-2 flex items-center gap-3">
                        <a href="{{ setting('social_twitter', 'https://twitter.com') }}" target="_blank" rel="noopener" aria-label="Twitter" class="w-8 h-8 rounded-lg bg-slate-900 hover:bg-brand-primary hover:text-white flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="{{ setting('social_youtube', 'https://youtube.com') }}" target="_blank" rel="noopener" aria-label="YouTube" class="w-8 h-8 rounded-lg bg-slate-900 hover:bg-brand-primary hover:text-white flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                        <a href="{{ setting('social_whatsapp', 'https://whatsapp.com') }}" target="_blank" rel="noopener" aria-label="WhatsApp" class="w-8 h-8 rounded-lg bg-slate-900 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.81 13.47 3.81 11.91C3.81 7.37 7.5 3.67 12.05 3.67Z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Categories -->
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white mb-4">{{ __('प्रमुख श्रेणियां') }}</h4>
                    <ul class="space-y-2 text-xs">
                        @if(isset($categories))
                            @foreach($categories->take(6) as $cat)
                                <li>
                                    <a href="{{ route('portal.category', $cat->slug) }}" class="hover:text-brand-primary transition-colors flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $cat->color }}"></span>
                                        {{ $cat->display_name }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <!-- Col 3: Quick Links & Contact -->
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white mb-4">{{ __('सम्पर्क व नीतियां') }}</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><strong>ईमेल:</strong> {{ setting('contact_email', 'editor@newskit.in') }}</li>
                        <li><strong>फ़ोन:</strong> {{ setting('contact_phone', '+91 98765 43210') }}</li>
                        <li><strong>पता:</strong> {{ setting('office_address', 'नई दिल्ली, भारत') }}</li>
                        <li><a href="{{ route('portal.citizen-tip') }}" class="text-rose-400 hover:underline font-bold flex items-center gap-1.5"><span>📢</span> <span>{{ __('ख़बर भेजें (सिटीज़न रिपोर्टर)') }}</span></a></li>
                        <li class="pt-1"><a href="{{ route('login') }}" class="text-brand-accent hover:underline font-semibold">{{ __('एडमिनिस्ट्रेटर लॉगिन →') }}</a></li>
                    </ul>
                </div>

                <!-- Col 4: Newsletter -->
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white mb-4">{{ __('न्यूज़लेटर सब्सक्राइब') }}</h4>
                    <p class="text-xs text-slate-400 mb-3">{{ __('हर सुबह देश की टॉप 10 बड़ी खबरें सीधे आपके इनबॉक्स में।') }}</p>
                    <form action="{{ route('portal.newsletter.subscribe') }}" method="POST" class="newsletter-subscribe-form flex flex-col gap-2">
                        @csrf
                        <input type="email" name="email" required placeholder="{{ __('अपना ईमेल दर्ज करें...') }}" 
                               class="bg-slate-900 text-xs text-white rounded-lg px-3 py-2.5 border border-slate-800 focus:border-brand-primary outline-none">
                        <button type="submit" class="bg-brand-primary text-white font-bold text-xs py-2.5 rounded-lg hover:brightness-110 transition-all flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>{{ __('सब्सक्राइब करें') }}</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>{{ setting('copyright_text', '© 2026 The LallanTop News Network. ' . __('सभी अधिकार सुरक्षित।')) }}</p>
                <div class="flex items-center gap-6">
                    <span class="text-slate-400">Laravel 13 & TypeScript Powered</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Hyperlocal City Switcher Modal -->
    <div id="city-selector-modal" class="hidden fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-2xl w-full max-h-[85vh] shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            
            <!-- Modal Header -->
            <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-linear-to-r from-slate-900 to-indigo-950 text-white">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">📍</span>
                        <h3 class="text-lg sm:text-xl font-black">{{ __('अपना शहर या जिला चुनें') }}</h3>
                    </div>
                    <p class="text-xs text-slate-300">{{ __('आपके चुने हुए शहर की ताजा खबरें सीधे होमपेज पर दिखाई देंगी।') }}</p>
                </div>
                <button type="button" id="city-modal-close" class="p-1.5 rounded-full text-slate-400 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Instant Search Input & Quick All Option -->
            <div class="p-4 sm:p-6 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 space-y-3">
                <div class="relative">
                    <input type="text" id="city-modal-search" 
                           placeholder="{{ __('शहर या जिला खोजें (उदा: लखनऊ, पटना, कानपुर, Noida, Delhi)...') }}" 
                           class="w-full bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-xs sm:text-sm rounded-xl pl-9 pr-4 py-3 border border-slate-300 dark:border-slate-700 focus:border-brand-primary outline-none shadow-xs">
                    <span class="absolute left-3 top-3.5 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                </div>

                <!-- Popular City Quick Chips -->
                <div class="flex items-center gap-1.5 flex-wrap text-xs">
                    <span class="text-[11px] font-bold text-slate-400">{{ __('शीघ्र चयन:') }}</span>
                    <a href="{{ route('portal.set-city', 'clear') }}" 
                       class="px-2.5 py-1 rounded-lg font-bold transition-colors {{ empty($userCitySlug) ? 'bg-brand-primary text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300' }}">
                        {{ __('🌐 सभी शहर (All)') }}
                    </a>
                    @php
                        $popularCitySlugs = ['lucknow', 'patna', 'new-delhi', 'varanasi', 'kanpur', 'bhopal', 'jaipur', 'mumbai'];
                    @endphp
                    @if(isset($portalStates))
                        @foreach($portalStates as $st)
                            @foreach($st->cities->whereIn('slug', $popularCitySlugs) as $pc)
                                <a href="{{ route('portal.set-city', $pc->slug) }}" 
                                   class="px-2.5 py-1 rounded-lg font-medium transition-colors {{ $userCitySlug === $pc->slug ? 'bg-brand-primary text-white font-bold' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300' }}">
                                    {{ $pc->display_name }}
                                </a>
                            @endforeach
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- States and Districts Hierarchical List -->
            <div class="p-4 sm:p-6 overflow-y-auto space-y-6 flex-1 divide-y divide-slate-100 dark:divide-slate-800" id="city-modal-list">
                @if(isset($portalStates))
                    @foreach($portalStates as $st)
                        <div class="state-group pt-4 first:pt-0 space-y-3" data-state-name="{{ strtolower($st->name . ' ' . $st->name_hi) }}">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('portal.state', $st->slug) }}" class="font-extrabold text-xs sm:text-sm text-slate-900 dark:text-white hover:text-brand-primary flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    <span>{{ $st->display_name }} ({{ $st->name }})</span>
                                </a>
                                <a href="{{ route('portal.state', $st->slug) }}" class="text-[11px] font-semibold text-rose-600 dark:text-rose-400 hover:underline">
                                    {{ __('पूरे राज्य की खबरें →') }}
                                </a>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                                @foreach($st->cities as $ct)
                                    <a href="{{ route('portal.set-city', $ct->slug) }}" 
                                       data-city-text="{{ strtolower($ct->name . ' ' . $ct->name_hi . ' ' . $st->name . ' ' . $st->name_hi) }}"
                                       class="city-chip px-3 py-2 rounded-xl text-xs transition-all flex items-center justify-between border {{ $userCitySlug === $ct->slug ? 'bg-brand-primary text-white border-brand-primary font-bold shadow-xs' : 'bg-white dark:bg-slate-800/80 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-800' }}">
                                        <span class="truncate">{{ $ct->display_name }}</span>
                                        @if($userCitySlug === $ct->slug)
                                            <span class="text-xs">✓</span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
                <div id="city-no-match" class="hidden py-12 text-center text-slate-400">
                    <p class="text-sm font-semibold">{{ __('आपके खोज से मेल खाता कोई शहर नहीं मिला।') }}</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Scripts for Language Switcher & City Selector Modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Language Switcher Dropdown
            const langBtn = document.getElementById('lang-switcher-btn');
            const langMenu = document.getElementById('lang-dropdown-menu');
            const langChevron = document.getElementById('lang-chevron');
            if (langBtn && langMenu) {
                langBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isOpen = !langMenu.classList.contains('hidden');
                    if (isOpen) {
                        langMenu.classList.add('hidden');
                        if (langChevron) langChevron.classList.remove('rotate-180');
                    } else {
                        langMenu.classList.remove('hidden');
                        if (langChevron) langChevron.classList.add('rotate-180');
                    }
                });
                document.addEventListener('click', function(e) {
                    if (!langBtn.contains(e.target) && !langMenu.contains(e.target)) {
                        langMenu.classList.add('hidden');
                        if (langChevron) langChevron.classList.remove('rotate-180');
                    }
                });
            }

            // State & City Mega Dropdown (Hover + Click Support)
            const stateWrapper = document.getElementById('state-city-dropdown-wrapper');
            const stateBtn = document.getElementById('state-city-dropdown-btn');
            const stateMenu = document.getElementById('state-city-dropdown-menu');
            const stateChevron = document.getElementById('state-city-chevron');

            if (stateWrapper && stateMenu) {
                let stateTimeout;

                const showStateMenu = () => {
                    clearTimeout(stateTimeout);
                    stateMenu.classList.remove('hidden');
                    if (stateChevron) stateChevron.classList.add('rotate-180');
                };

                const hideStateMenu = () => {
                    stateTimeout = setTimeout(() => {
                        stateMenu.classList.add('hidden');
                        if (stateChevron) stateChevron.classList.remove('rotate-180');
                    }, 150);
                };

                stateWrapper.addEventListener('mouseenter', showStateMenu);
                stateWrapper.addEventListener('mouseleave', hideStateMenu);

                if (stateBtn) {
                    stateBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const isHidden = stateMenu.classList.contains('hidden');
                        if (isHidden) {
                            showStateMenu();
                        } else {
                            stateMenu.classList.add('hidden');
                            if (stateChevron) stateChevron.classList.remove('rotate-180');
                        }
                    });
                }

                document.addEventListener('click', function(e) {
                    if (!stateWrapper.contains(e.target)) {
                        stateMenu.classList.add('hidden');
                        if (stateChevron) stateChevron.classList.remove('rotate-180');
                    }
                });
            }

            // City Selector Modal
            const cityTrigger = document.getElementById('city-modal-trigger');
            const cityModal = document.getElementById('city-selector-modal');
            const cityClose = document.getElementById('city-modal-close');
            const citySearch = document.getElementById('city-modal-search');
            const cityChips = document.querySelectorAll('.city-chip');
            const stateGroups = document.querySelectorAll('.state-group');
            const noMatch = document.getElementById('city-no-match');

            function openCityModal() {
                if (cityModal) {
                    cityModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    if (citySearch) {
                        setTimeout(() => citySearch.focus(), 100);
                    }
                }
            }

            function closeCityModal() {
                if (cityModal) {
                    cityModal.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            }

            if (cityTrigger) {
                cityTrigger.addEventListener('click', openCityModal);
            }

            if (cityClose) {
                cityClose.addEventListener('click', closeCityModal);
            }

            if (cityModal) {
                cityModal.addEventListener('click', function(e) {
                    if (e.target === cityModal) {
                        closeCityModal();
                    }
                });
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && cityModal && !cityModal.classList.contains('hidden')) {
                    closeCityModal();
                }
            });

            // Real-time City Search Filter
            if (citySearch) {
                citySearch.addEventListener('input', function() {
                    const q = this.value.trim().toLowerCase();
                    let totalVisible = 0;

                    stateGroups.forEach(function(group) {
                        let groupVisible = false;
                        const chips = group.querySelectorAll('.city-chip');

                        chips.forEach(function(chip) {
                            const text = chip.getAttribute('data-city-text') || '';
                            if (!q || text.includes(q)) {
                                chip.style.display = '';
                                groupVisible = true;
                                totalVisible++;
                            } else {
                                chip.style.display = 'none';
                            }
                        });

                        group.style.display = groupVisible ? '' : 'none';
                    });

                    if (noMatch) {
                        noMatch.style.display = totalVisible === 0 ? 'block' : 'none';
                    }
                });
            }
        });
    </script>

    <!-- Automatic News Poster & Graphic Card Studio Modal -->
    @include('components.news-poster-modal')
</body>
</html>
