@extends('layouts.portal')

@section('title', setting('site_name', 'The LallanTop News') . ' - ' . __('होम'))

@section('extra_seo')
@php
    $homeSchema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'WebSite',
                '@id' => url('/') . '/#website',
                'url' => url('/'),
                'name' => setting('site_name', 'The LallanTop News'),
                'description' => setting('site_tagline', 'बेबाक, सटीक और सबसे तेज़ ख़बरें'),
                'publisher' => [
                    '@id' => url('/') . '/#organization',
                ],
                'inLanguage' => app()->getLocale() ?: 'hi',
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => [
                        '@type' => 'EntryPoint',
                        'urlTemplate' => url('/search') . '?q={search_term_string}',
                    ],
                    'query-input' => 'required name=search_term_string',
                ],
            ],
            [
                '@type' => 'NewsMediaOrganization',
                '@id' => url('/') . '/#organization',
                'name' => setting('site_name', 'The LallanTop News'),
                'url' => url('/'),
                'logo' => [
                    '@type' => 'ImageObject',
                    '@id' => url('/') . '/#logo',
                    'url' => asset(setting('logo_url', '/images/logo.svg')),
                    'caption' => setting('site_name', 'The LallanTop News'),
                ],
                'sameAs' => array_values(array_filter([
                    setting('facebook_url', 'https://facebook.com'),
                    setting('twitter_url', 'https://twitter.com'),
                    setting('youtube_url', 'https://youtube.com'),
                ])),
                'publishingPrinciples' => url('/'),
            ],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($homeSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

    <!-- 0. TOP LEADERBOARD ADVERTISEMENT (HOMEPAGE HEADER) -->
    @if(isset($headerAd))
        <div class="rounded-2xl overflow-hidden border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs p-3 space-y-1.5">
            <div class="flex items-center justify-between px-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    {{ $headerAd->title ?: __('प्रायोजित विज्ञापन') }}
                </span>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest border border-slate-200 dark:border-slate-700 px-1.5 py-0.5 rounded">AD</span>
            </div>
            @if($headerAd->type === 'image' && $headerAd->image_url)
                <a href="{{ route('portal.ad.click', $headerAd->id) }}" target="_blank" rel="noopener sponsored" class="block rounded-xl overflow-hidden group">
                    <img src="{{ $headerAd->image_url }}" alt="{{ $headerAd->title }}" class="w-full max-h-36 object-cover rounded-xl transition-transform duration-500 group-hover:scale-101">
                </a>
            @elseif($headerAd->type === 'code' && $headerAd->html_code)
                <div class="overflow-hidden rounded-xl bg-slate-50 dark:bg-slate-950 p-2 flex justify-center items-center">
                    {!! $headerAd->html_code !!}
                </div>
            @endif
        </div>
    @endif

    <!-- 1. HERO EDITORIAL GRID: Top Lead Story + 4 Sub-Leads -->
    @if(isset($mainHero))
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            <!-- Main Hero Story (7 cols) -->
            <div class="lg:col-span-7 group relative rounded-3xl overflow-hidden shadow-lg bg-slate-900 min-h-[440px] lg:min-h-[500px] flex flex-col justify-end">
                @if($mainHero->featured_image)
                    <img src="{{ $mainHero->featured_image }}" 
                         alt="{{ $mainHero->title }}" 
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-80 group-hover:opacity-90">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/50 to-transparent"></div>

                <div class="relative z-10 p-6 sm:p-8 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-black text-white shadow-sm" style="background-color: {{ $mainHero->category->color }}">
                            {{ $mainHero->category->display_name }}
                        </span>
                        @if($mainHero->is_breaking)
                            <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-rose-600 text-white flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-white live-pulse"></span>
                                {{ __('ब्रेकिंग') }}
                            </span>
                        @endif
                        <span class="text-xs text-slate-300 font-medium">
                            {{ $mainHero->published_at ? $mainHero->published_at->diffForHumans() : '' }}
                        </span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white leading-snug group-hover:text-amber-300 transition-colors">
                        <a href="{{ route('portal.article', $mainHero->slug) }}">
                            {{ $mainHero->title }}
                        </a>
                    </h2>

                    @if($mainHero->excerpt)
                        <p class="text-sm text-slate-300 line-clamp-2 leading-relaxed">
                            {{ $mainHero->excerpt }}
                        </p>
                    @endif

                    <div class="pt-2 flex items-center justify-between text-xs text-slate-400 border-t border-slate-700/50">
                        <div class="flex items-center gap-2">
                            @if($mainHero->author->avatar)
                                <img src="{{ $mainHero->author->avatar }}" alt="" class="w-6 h-6 rounded-full object-cover">
                            @endif
                            <span class="font-semibold text-slate-200">{{ $mainHero->author->name }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span>{{ $mainHero->reading_time }} {{ __('मिनट पढ़ाई') }}</span>
                            <span>{{ number_format($mainHero->views_count) }} {{ __('व्यूज') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4 Sub-Lead News (5 cols - Multiple Stories on Right) -->
            <div class="lg:col-span-5 flex flex-col justify-between gap-3.5">
                @if(isset($subHeroes))
                    @foreach($subHeroes as $sub)
                        <article class="group bg-white dark:bg-slate-900 rounded-2xl p-3.5 border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-md transition-all flex gap-3.5 news-card-hover flex-1 items-center">
                            @if($sub->featured_image)
                                <div class="w-24 h-20 sm:w-28 sm:h-20 rounded-xl overflow-hidden shrink-0">
                                    <img src="{{ $sub->featured_image }}" alt="{{ $sub->title }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="flex-1 min-w-0 flex flex-col justify-between h-full py-0.5">
                                <div>
                                    <span class="inline-block text-[10px] font-bold uppercase tracking-wider mb-0.5" style="color: {{ $sub->category->color }}">
                                        {{ $sub->category->display_name }}
                                    </span>
                                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white line-clamp-2 group-hover:text-brand-primary transition-colors leading-snug">
                                        <a href="{{ route('portal.article', $sub->slug) }}">{{ $sub->title }}</a>
                                    </h3>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-slate-400 mt-1">
                                    <span>{{ $sub->published_at ? $sub->published_at->diffForHumans() : '' }}</span>
                                    <span>{{ number_format($sub->views_count) }} {{ __('व्यूज') }}</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @endif
            </div>
        </section>
    @endif

    <!-- 1.8 PERSONALIZED HYPERLOCAL CITY SPOTLIGHT -->
    @if(isset($selectedCity) && $selectedCity)
        <section class="bg-gradient-to-r from-rose-950/80 via-slate-900 to-indigo-950 text-white rounded-3xl p-6 sm:p-8 shadow-xl border border-rose-900/40 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/10">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-600 text-white">
                            {{ __('आपका चुना हुआ शहर') }}
                        </span>
                        <span class="text-xs text-rose-300 font-semibold">{{ $selectedCity->state->display_name }}</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black flex items-center gap-2">
                        <span>📍</span>
                        <span>{{ $selectedCity->display_name }} ({{ $selectedCity->name }}) की ताजा खबरें</span>
                    </h2>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('portal.city', $selectedCity->slug) }}" class="text-xs font-bold px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-all flex items-center gap-1">
                        <span>{{ __('सभी खबरें देखें') }}</span>
                        <span>→</span>
                    </a>
                    <button type="button" onclick="document.getElementById('city-modal-trigger').click();" class="text-xs font-bold px-3 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white transition-all">
                        {{ __('शहर बदलें') }}
                    </button>
                </div>
            </div>

            @if(isset($myCityArticles) && $myCityArticles->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($myCityArticles as $cArt)
                        <article class="bg-white/5 hover:bg-white/10 rounded-2xl p-3.5 border border-white/10 transition-all flex flex-col justify-between group">
                            <div>
                                @if($cArt->featured_image)
                                    <div class="h-32 rounded-xl overflow-hidden mb-3">
                                        <img src="{{ $cArt->featured_image }}" alt="{{ $cArt->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                @endif
                                <span class="text-[10px] font-bold uppercase tracking-wider text-rose-400 block mb-1">
                                    {{ $cArt->category->display_name ?? 'न्यूज़' }}
                                </span>
                                <h3 class="text-xs sm:text-sm font-bold text-white group-hover:text-amber-300 line-clamp-2 leading-snug">
                                    <a href="{{ route('portal.article', $cArt->slug) }}">{{ $cArt->title }}</a>
                                </h3>
                            </div>
                            <div class="pt-3 mt-2 border-t border-white/10 flex items-center justify-between text-[11px] text-slate-300">
                                <span>{{ $cArt->published_at ? $cArt->published_at->diffForHumans() : '' }}</span>
                                <span>{{ number_format($cArt->views_count) }} {{ __('व्यूज') }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="p-6 text-center bg-white/5 rounded-2xl text-slate-300 text-xs">
                    <p>{{ $selectedCity->display_name }} से संबंधित नई खबरें जल्द ही यहां प्रकाशित होंगी।</p>
                </div>
            @endif
        </section>
    @else
        <!-- Hyperlocal Prompt Strip if no city chosen yet -->
        <div class="bg-linear-to-r from-rose-500/10 via-amber-500/10 to-indigo-500/10 rounded-2xl p-4 border border-rose-500/20 flex flex-col sm:flex-row items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
                <span class="text-2xl">📍</span>
                <div>
                    <h3 class="text-sm font-black text-slate-900 dark:text-white">{{ __('अपने शहर और जिले की ताज़ा लोकल खबरें पढ़ना चाहते हैं?') }}</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-300">{{ __('उत्तर प्रदेश, बिहार, दिल्ली, राजस्थान, मध्य प्रदेश आदि के 48+ प्रमुख शहरों की लोकल खबरें।') }}</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('city-modal-trigger').click();" class="shrink-0 px-4 py-2 rounded-xl bg-brand-primary hover:brightness-110 text-white text-xs font-bold shadow-xs transition-all flex items-center gap-1.5">
                <span>📍</span>
                <span>{{ __('अपना शहर चुनें') }}</span>
            </button>
        </div>
    @endif

    <!-- 2. TRENDING TOP STORIES SECTION -->
    @if(isset($trendingArticles) && $trendingArticles->isNotEmpty())
        <section class="bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 text-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-800">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full bg-amber-400 live-pulse"></span>
                    <h2 class="text-lg sm:text-xl font-black uppercase tracking-wider flex items-center gap-2">
                        <span>{{ __('ट्रेंडिंग ख़बरें') }}</span>
                    </h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($trendingArticles as $index => $trend)
                    <div class="flex items-start gap-4 group">
                        <span class="text-3xl font-black text-slate-600 group-hover:text-amber-400 transition-colors shrink-0 font-mono">
                            0{{ $index + 1 }}
                        </span>
                        <div class="space-y-1">
                            <span class="inline-block text-[10px] font-bold uppercase tracking-wider" style="color: {{ $trend->category->color }}">
                                {{ $trend->category->display_name }}
                            </span>
                            <h4 class="text-sm font-bold text-slate-100 group-hover:text-amber-300 transition-colors line-clamp-2 leading-snug">
                                <a href="{{ route('portal.article', $trend->slug) }}">{{ $trend->title }}</a>
                            </h4>
                            <span class="block text-[11px] text-slate-400">{{ number_format($trend->views_count) }} {{ __('रीडर्स') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- 2.3 HYPERLOCAL STATE & CITY NEWS HUB -->
    @if(isset($portalStates) && $portalStates->isNotEmpty() && isset($hyperlocalArticles) && $hyperlocalArticles->isNotEmpty())
        <section class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-6">
            <!-- Hub Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                        <span class="text-xs font-extrabold uppercase tracking-wider text-rose-600 dark:text-rose-400">
                            {{ __('हाइपरलोकल न्यूज़ नेटवर्क') }}
                        </span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ __('राज्य व शहर न्यूज़ हब') }}
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('उत्तर प्रदेश, बिहार, दिल्ली-NCR, मध्य प्रदेश समेत देश के हर राज्य व जिले की ज़मीनी रिपोर्टिंग।') }}
                    </p>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <button type="button" onclick="document.getElementById('city-modal-trigger').click();" 
                            class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors flex items-center gap-1.5 border border-slate-300/80 dark:border-slate-700">
                        <span>📍</span>
                        <span>{{ __('सभी 48+ शहर देखें') }}</span>
                    </button>
                </div>
            </div>

            <!-- State Tabs Bar -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                <span class="text-xs font-bold text-slate-400 shrink-0 mr-1">{{ __('राज्य:') }}</span>
                @foreach($portalStates as $stIndex => $st)
                    <a href="{{ route('portal.state', $st->slug) }}" 
                       class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ $stIndex === 0 ? 'bg-brand-primary text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                        <span>{{ $st->display_name }}</span>
                        <span class="text-[10px] opacity-75">({{ $st->cities->count() }})</span>
                    </a>
                @endforeach
            </div>

            <!-- Hyperlocal Articles Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($hyperlocalArticles->take(8) as $hArt)
                    <article class="bg-slate-50 dark:bg-slate-800/60 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 hover:border-brand-primary/40 transition-all flex flex-col justify-between group">
                        <div>
                            @if($hArt->featured_image)
                                <div class="h-40 overflow-hidden relative">
                                    <a href="{{ route('portal.article', $hArt->slug) }}">
                                        <img src="{{ $hArt->featured_image }}" alt="{{ $hArt->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </a>
                                    @if($hArt->city)
                                        <a href="{{ route('portal.city', $hArt->city->slug) }}" 
                                           class="absolute top-2.5 left-2.5 bg-black/75 hover:bg-brand-primary text-white text-[10px] font-bold px-2 py-0.5 rounded-md backdrop-blur-xs transition-colors flex items-center gap-1">
                                            <span>📍</span> {{ $hArt->city->display_name }}
                                        </a>
                                    @elseif($hArt->state)
                                        <a href="{{ route('portal.state', $hArt->state->slug) }}" 
                                           class="absolute top-2.5 left-2.5 bg-black/75 hover:bg-brand-primary text-white text-[10px] font-bold px-2 py-0.5 rounded-md backdrop-blur-xs transition-colors flex items-center gap-1">
                                            <span>📍</span> {{ $hArt->state->display_name }}
                                        </a>
                                    @endif
                                </div>
                            @endif

                            <div class="p-4 space-y-1.5">
                                <div class="flex items-center gap-1.5 text-[11px] text-slate-400">
                                    <span class="font-semibold text-brand-primary dark:text-rose-400">{{ $hArt->category->display_name ?? 'न्यूज़' }}</span>
                                    <span>•</span>
                                    <span>{{ $hArt->published_at ? $hArt->published_at->diffForHumans() : '' }}</span>
                                </div>

                                <h3 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white line-clamp-2 group-hover:text-brand-primary transition-colors leading-snug">
                                    <a href="{{ route('portal.article', $hArt->slug) }}">{{ $hArt->title }}</a>
                                </h3>
                            </div>
                        </div>

                        <div class="px-4 pb-3 pt-2 border-t border-slate-200/60 dark:border-slate-800 flex items-center justify-between text-[11px] text-slate-400">
                            <span class="truncate max-w-[120px]">{{ $hArt->location_name ?: ($hArt->city ? $hArt->city->display_name : ($hArt->state ? $hArt->state->display_name : '')) }}</span>
                            <span>{{ number_format($hArt->views_count) }} {{ __('व्यूज') }}</span>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Hub Footer Bar -->
            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs border-t border-slate-100 dark:border-slate-800">
                <span class="text-slate-500 font-medium">{{ __('अपने पसंदीदा राज्य या जिले की खबरें खोजने के लिए राज्य पर क्लिक करें।') }}</span>
                <div class="flex items-center gap-3">
                    @foreach($portalStates->take(4) as $topSt)
                        <a href="{{ route('portal.state', $topSt->slug) }}" class="font-bold text-slate-700 dark:text-slate-300 hover:text-brand-primary">
                            {{ $topSt->display_name }} →
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- 2.5 IN-FEED PROMINENT SPONSORED BANNER -->
    @if(isset($inFeedAd))
        <div class="rounded-3xl overflow-hidden border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs p-4 space-y-2">
            <div class="flex items-center justify-between px-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    {{ $inFeedAd->title ?: __('प्रायोजित विज्ञापन') }}
                </span>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest border border-slate-200 dark:border-slate-700 px-1.5 py-0.5 rounded">AD</span>
            </div>
            @if($inFeedAd->type === 'image' && $inFeedAd->image_url)
                <a href="{{ route('portal.ad.click', $inFeedAd->id) }}" target="_blank" rel="noopener sponsored" class="block rounded-2xl overflow-hidden group">
                    <img src="{{ $inFeedAd->image_url }}" alt="{{ $inFeedAd->title }}" class="w-full max-h-48 object-cover rounded-2xl transition-transform duration-500 group-hover:scale-101">
                </a>
            @elseif($inFeedAd->type === 'code' && $inFeedAd->html_code)
                <div class="overflow-hidden rounded-2xl bg-slate-50 dark:bg-slate-950 p-2 flex justify-center items-center">
                    {!! $inFeedAd->html_code !!}
                </div>
            @endif
        </div>
    @endif

    <!-- 2.8 FEATURED VIDEO BULLETIN GALLERY (YOUTUBE & DIRECT MP4 VIDEOS) -->
    @if(isset($videoArticles) && $videoArticles->isNotEmpty())
        <section class="bg-slate-950 text-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-800 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-lg shadow-rose-600/30">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                    </span>
                    <div>
                        <h2 class="text-lg sm:text-xl font-black uppercase tracking-wider flex items-center gap-2">
                            <span>{{ __('वीडियो बुलेटिन व विशेष कवरेज') }}</span>
                            <span class="w-2 h-2 rounded-full bg-rose-500 live-pulse"></span>
                        </h2>
                        <span class="text-xs text-slate-400 font-medium">{{ __('YouTube एवं डायरेक्ट एक्सक्लूसिव वीडियो रिपोर्ट') }}</span>
                    </div>
                </div>
                <span class="text-xs text-amber-400 font-bold bg-amber-400/10 px-3 py-1 rounded-full border border-amber-400/20 w-fit">
                    {{ __('डिजिटल वीडियो डेस्क') }}
                </span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                <!-- Main Featured Video (7 cols) -->
                @php $mainVideo = $videoArticles->first(); @endphp
                <div class="lg:col-span-7 group relative rounded-2xl overflow-hidden bg-slate-900 shadow-xl border border-slate-800/80 flex flex-col justify-end min-h-[340px] sm:min-h-[400px]">
                    @if($mainVideo->featured_image)
                        <img src="{{ $mainVideo->featured_image }}" alt="{{ $mainVideo->title }}" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:scale-105 group-hover:opacity-90 transition-all duration-700">
                    @else
                        <div class="absolute inset-0 bg-slate-900 flex items-center justify-center">
                            <span class="text-slate-600">Video Thumbnail</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent"></div>

                    <!-- Glowing Play Button Overlay -->
                    <a href="{{ route('portal.article', $mainVideo->slug) }}" class="absolute inset-0 flex items-center justify-center z-10" aria-label="Play video">
                        <span class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-rose-600/90 text-white flex items-center justify-center shadow-2xl transition-all duration-300 group-hover:scale-115 group-hover:bg-rose-600 ring-8 ring-rose-600/30">
                            <svg class="w-8 h-8 fill-current ml-1" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                        </span>
                    </a>

                    <div class="relative z-20 p-6 space-y-2.5">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black text-white shadow-sm" style="background-color: {{ $mainVideo->category->color }}">
                                {{ $mainVideo->category->display_name }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/20 text-white">
                                {{ $mainVideo->isYoutubeVideo() ? 'YouTube' : 'Video' }}
                            </span>
                        </div>
                        <h3 class="text-lg sm:text-2xl font-black text-white leading-snug group-hover:text-amber-300 transition-colors">
                            <a href="{{ route('portal.article', $mainVideo->slug) }}">{{ $mainVideo->title }}</a>
                        </h3>
                        <div class="flex items-center justify-between text-xs text-slate-400 pt-2 border-t border-slate-800">
                            <span>{{ $mainVideo->author->name }}</span>
                            <span>{{ number_format($mainVideo->views_count) }} {{ __('व्यूज') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Sub Video Stories Grid (5 cols) -->
                <div class="lg:col-span-5 flex flex-col gap-3.5">
                    @foreach($videoArticles->skip(1) as $vStory)
                        <article class="group bg-slate-900/90 hover:bg-slate-900/100 rounded-2xl p-3 sm:p-3.5 border border-slate-800/80 hover:border-slate-700 shadow-sm transition-all flex gap-3.5 items-center flex-1">
                            <div class="w-28 sm:w-32 h-20 sm:h-22 rounded-xl overflow-hidden relative shrink-0 bg-slate-800 border border-slate-800">
                                @if($vStory->featured_image)
                                    <img src="{{ $vStory->featured_image }}" alt="{{ $vStory->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" loading="lazy">
                                @else
                                    <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                <a href="{{ route('portal.article', $vStory->slug) }}" class="absolute inset-0 flex items-center justify-center bg-black/40 group-hover:bg-black/20 transition-all">
                                    <span class="w-8 h-8 rounded-full bg-rose-600 text-white flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                                        <svg class="w-3.5 h-3.5 fill-current {{ ($isRtl ?? false) ? 'mr-0.5' : 'ml-0.5' }}" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                    </span>
                                </a>
                            </div>

                            <div class="flex-1 min-w-0 flex flex-col justify-between h-full py-0.5">
                                <div>
                                    <span class="inline-block text-[10px] font-bold uppercase tracking-wider text-rose-400 mb-0.5">
                                        {{ $vStory->isYoutubeVideo() ? 'YouTube' : 'Video' }} • {{ $vStory->category->display_name }}
                                    </span>
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-100 line-clamp-2 group-hover:text-amber-300 transition-colors leading-snug">
                                        <a href="{{ route('portal.article', $vStory->slug) }}">{{ $vStory->title }}</a>
                                    </h4>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-slate-400 mt-1">
                                    <span>{{ $vStory->published_at ? $vStory->published_at->diffForHumans() : '' }}</span>
                                    <span>{{ number_format($vStory->views_count) }} {{ __('व्यूज') }}</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- 3. MAIN CONTENT AREA: LATEST STORIES + RICH SIDEBAR -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Latest News Feed (8 cols) -->
        <section class="lg:col-span-8 space-y-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                <h2 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded bg-brand-primary"></span>
                    <span>{{ __('ताज़ा समाचार') }}</span>
                </h2>
                <span class="text-xs text-slate-500 font-semibold">{{ $latestArticles->total() }} {{ __('ख़बरें उपलब्ध') }}</span>
            </div>

            <!-- News Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($latestArticles as $art)
                    <article class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-xl transition-all flex flex-col justify-between news-card-hover group">
                        <div>
                            @if($art->featured_image)
                                <div class="h-48 overflow-hidden relative">
                                    <img src="{{ $art->featured_image }}" alt="{{ $art->title }}" class="w-full h-full object-cover">
                                    <span class="absolute top-3 {{ ($isRtl ?? false) ? 'right-3' : 'left-3' }} px-2.5 py-0.5 rounded-full text-[11px] font-bold text-white shadow-sm" style="background-color: {{ $art->category->color }}">
                                        {{ $art->category->display_name }}
                                    </span>
                                    @if($art->location_badge)
                                        <a href="{{ $art->city ? route('portal.city', $art->city->slug) : ($art->state ? route('portal.state', $art->state->slug) : '#') }}" 
                                           class="absolute top-3 {{ ($isRtl ?? false) ? 'left-3' : 'right-3' }} px-2 py-0.5 rounded-md text-[10px] font-bold bg-black/75 hover:bg-brand-primary text-white flex items-center gap-1 backdrop-blur-xs transition-colors">
                                            <span>📍</span> {{ $art->location_badge }}
                                        </a>
                                    @endif
                                    @if($art->hasVideo())
                                        <span class="absolute bottom-3 {{ ($isRtl ?? false) ? 'left-3' : 'right-3' }} px-2 py-0.5 rounded-lg text-[10px] font-extrabold bg-black/80 text-rose-400 flex items-center gap-1 backdrop-blur-xs">
                                            <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                            Video
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <div class="p-5 space-y-2">
                                <h3 class="font-bold text-base text-slate-900 dark:text-white line-clamp-2 group-hover:text-brand-primary transition-colors leading-snug">
                                    <a href="{{ route('portal.article', $art->slug) }}">{{ $art->title }}</a>
                                </h3>

                                @if($art->excerpt)
                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                        {{ $art->excerpt }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-5 pb-5 pt-2 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                            <div class="flex items-center gap-2">
                                @if($art->author->avatar)
                                    <img src="{{ $art->author->avatar }}" alt="" class="w-5 h-5 rounded-full object-cover">
                                @endif
                                <span class="font-medium text-slate-700 dark:text-slate-300 truncate max-w-[100px]">{{ $art->author->name }}</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <!-- Bookmark Button -->
                                <button class="btn-bookmark hover:text-brand-primary transition-colors" data-article-id="{{ $art->id }}" title="{{ __('बुकमार्क करें') }}">
                                    <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                </button>
                                <!-- Share Button (Native Share on Mobile / Copy Link on Desktop) -->
                                <button class="btn-native-share hover:text-brand-primary transition-colors" data-title="{{ $art->title }}" data-url="{{ route('portal.article', $art->slug) }}" title="{{ __('शेयर करें') }}">
                                    <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-6">
                {{ $latestArticles->links() }}
            </div>
        </section>

        <!-- Right Editorial Sidebar (4 cols) -->
        <aside class="lg:col-span-4 space-y-8">
            <!-- 1. Live Weather & Date Card -->
            <div id="weather-widget" class="bg-gradient-to-br from-blue-700 via-indigo-800 to-slate-900 text-white p-6 rounded-3xl shadow-xl relative overflow-hidden border border-blue-600/30">
                <div class="relative z-10 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-200 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 live-pulse"></span>
                            {{ __('मौसम व समय') }}
                        </span>
                        
                        <!-- City Switcher -->
                        <select id="weather-city-select" class="text-xs font-bold bg-white/20 hover:bg-white/30 text-white border-none rounded-xl px-2.5 py-1 outline-none cursor-pointer">
                            <option value="delhi" class="text-slate-900">{{ __('नई दिल्ली') }}</option>
                            <option value="mumbai" class="text-slate-900">{{ __('मुंबई') }}</option>
                            <option value="lucknow" class="text-slate-900">{{ __('लखनऊ') }}</option>
                            <option value="patna" class="text-slate-900">{{ __('पटना') }}</option>
                            <option value="bhopal" class="text-slate-900">{{ __('भोपाल') }}</option>
                            <option value="jaipur" class="text-slate-900">{{ __('जयपुर') }}</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-between py-1">
                        <div>
                            <span id="weather-temp" class="text-4xl sm:text-5xl font-black tracking-tight">28°C</span>
                            <span id="weather-desc" class="block text-xs text-blue-100 font-semibold mt-1">साफ मौसम</span>
                        </div>
                        <div id="weather-icon" class="text-5xl sm:text-6xl drop-shadow-md">☀️</div>
                    </div>

                    <div class="text-[11px] text-blue-200 pt-3 border-t border-white/20 flex items-center justify-between font-medium">
                        <span id="weather-wind">10 km/h</span>
                        <span class="bg-white/10 px-2 py-0.5 rounded-full">{{ __('लाइव अपडेट') }}</span>
                    </div>

                    <!-- Live Clock and Calendar -->
                    <div class="bg-black/30 backdrop-blur-xs rounded-2xl p-2.5 text-center text-xs font-mono font-bold text-amber-300 border border-white/10">
                        <span id="weather-live-time">{{ __('लोड हो रहा है...') }}</span>
                    </div>
                </div>
            </div>

            <!-- 1.5 Newsletter Subscribe Sidebar Card -->
            <div class="p-6 rounded-3xl bg-gradient-to-br from-slate-900 via-slate-950 to-rose-950/40 text-white border border-slate-800 shadow-xl space-y-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-brand-primary text-white flex items-center justify-center shrink-0 shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-amber-400">{{ __('दैनिक न्यूज़लेटर') }}</h4>
                        <p class="text-[11px] text-slate-300">{{ __('शीर्ष 10 ख़बरें सीधे अपने इनबॉक्स में पाएं') }}</p>
                    </div>
                </div>
                <form action="{{ route('portal.newsletter.subscribe') }}" method="POST" class="newsletter-subscribe-form space-y-2 pt-1">
                    @csrf
                    <input type="email" name="email" required placeholder="{{ __('अपना ईमेल दर्ज करें...') }}" 
                           class="w-full bg-slate-800 text-xs text-white rounded-xl px-3.5 py-2.5 border border-slate-700 focus:border-brand-primary outline-none">
                    <button type="submit" class="w-full bg-brand-primary hover:brightness-110 text-white font-bold text-xs py-2.5 rounded-xl transition-all shadow-md">
                        {{ __('मुफ्त सब्सक्राइब करें') }}
                    </button>
                </form>
            </div>

            <!-- 1.6 WhatsApp Channel Connect Card with Live QR Code -->
            @include('components.whatsapp-channel-card')

            <!-- HOMEPAGE SIDEBAR ADVERTISEMENTS (MULTIPLE UNITS) -->
            @if(isset($homepageSidebarAds) && $homepageSidebarAds->isNotEmpty())
                <div class="space-y-4">
                    @foreach($homepageSidebarAds as $sAd)
                        <div class="rounded-3xl overflow-hidden border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs p-3.5 space-y-2">
                            <div class="flex items-center justify-between px-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    {{ $sAd->title ?: __('विज्ञापन') }}
                                </span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest border border-slate-200 dark:border-slate-700 px-1.5 py-0.5 rounded">AD</span>
                            </div>
                            @if($sAd->type === 'image' && $sAd->image_url)
                                <a href="{{ route('portal.ad.click', $sAd->id) }}" target="_blank" rel="noopener sponsored" class="block rounded-2xl overflow-hidden group">
                                    <img src="{{ $sAd->image_url }}" alt="{{ $sAd->title }}" class="w-full h-auto object-cover rounded-2xl transition-transform duration-500 group-hover:scale-102">
                                </a>
                            @elseif($sAd->type === 'code' && $sAd->html_code)
                                <div class="overflow-hidden rounded-2xl bg-slate-50 dark:bg-slate-950 p-2 flex justify-center items-center">
                                    {!! $sAd->html_code !!}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- 2. Categories with Counter Badges -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs">
                <h3 class="font-black text-sm uppercase tracking-wider text-slate-900 dark:text-white mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                    {{ __('प्रमुख श्रेणियां') }}
                </h3>
                <div class="space-y-2.5">
                    @foreach($categories as $cat)
                        <a href="{{ route('portal.category', $cat->slug) }}" class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
                            <span class="flex items-center gap-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-brand-primary">
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $cat->color }}"></span>
                                {{ $cat->display_name }}
                            </span>
                            <span class="text-[11px] font-semibold text-slate-400 group-hover:text-brand-primary">
                                {{ $cat->articles_count }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- 3. Popular Tags Cloud -->
            @if(isset($popularTags) && $popularTags->isNotEmpty())
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs">
                    <h3 class="font-black text-sm uppercase tracking-wider text-slate-900 dark:text-white mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                        {{ __('ट्रेंडिंग टॉपिक्स') }}
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($popularTags as $tag)
                            <a href="{{ route('portal.tag', $tag->slug) }}" class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-brand-primary hover:text-white transition-all">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 4. Editorial Mission Card -->
            <div class="p-6 rounded-3xl border border-rose-200 dark:border-rose-950/60 bg-rose-50/60 dark:bg-rose-950/20 space-y-3">
                <div class="flex items-center gap-2 text-brand-primary font-bold text-xs uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-brand-primary"></span>
                    <span>{{ __('हमारा संकल्प') }}</span>
                </div>
                <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                    {{ setting('site_name') }} का ध्येय है बिना किसी दबाव या पक्षपात के जनता तक सत्य और ज़मीनी हक़ीक़त पहुँचाना।
                </p>
            </div>
        </aside>
    </div>

</div>
@endsection
