@extends('layouts.portal')

@section('title', $article->title . ' - ' . setting('site_name', 'The LallanTop News'))
@section('meta_description', $article->excerpt ?: Str::limit(strip_tags($article->content), 150))
@section('og_type', 'article')
@section('og_image', $article->featured_image_url ?: asset(setting('logo_url', '/images/logo.svg')))
@section('canonical_url', route('portal.article', $article->slug))

@section('extra_seo')
@php
    $articleGraph = [
        [
            '@type' => 'NewsArticle',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('portal.article', $article->slug),
            ],
            'headline' => $article->title,
            'description' => $article->excerpt ?: Str::limit(strip_tags($article->content), 160),
            'image' => array_values(array_filter([$article->featured_image_url])),
            'datePublished' => ($article->published_at ?? $article->created_at)->toIso8601String(),
            'dateModified' => $article->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $article->author->name ?? setting('site_name'),
                'jobTitle' => $article->author->role ?? 'Journalist',
            ],
            'publisher' => [
                '@type' => 'NewsMediaOrganization',
                'name' => setting('site_name', 'The LallanTop News'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset(setting('logo_url', '/images/logo.svg')),
                ],
            ],
            'articleSection' => $article->category->name ?? 'General',
            'inLanguage' => app()->getLocale() ?: 'hi',
            'contentLocation' => $article->city ? [
                '@type' => 'City',
                'name' => $article->city->name,
                'alternateName' => $article->city->name_hi,
                'containedInPlace' => [
                    '@type' => 'AdministrativeArea',
                    'name' => $article->state?->name,
                    'alternateName' => $article->state?->name_hi
                ]
            ] : ($article->state ? [
                '@type' => 'AdministrativeArea',
                'name' => $article->state->name,
                'alternateName' => $article->state->name_hi
            ] : null),
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => __('होम'),
                    'item' => url('/'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => $article->category->display_name ?? $article->category->name,
                    'item' => route('portal.category', $article->category->slug),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $article->title,
                    'item' => route('portal.article', $article->slug),
                ],
            ],
        ],
    ];

    if ($article->hasVideo()) {
        $articleGraph[] = [
            '@type' => 'VideoObject',
            'name' => $article->title,
            'description' => $article->excerpt ?: Str::limit(strip_tags($article->content), 150),
            'thumbnailUrl' => array_values(array_filter([$article->featured_image_url ?: asset(setting('logo_url', '/images/logo.svg'))])),
            'uploadDate' => ($article->published_at ?? $article->created_at)->toIso8601String(),
            'embedUrl' => $article->isYoutubeVideo() ? $article->youtube_embed_url : $article->video_url,
        ];
    }

    $articleSchema = [
        '@context' => 'https://schema.org',
        '@graph' => $articleGraph,
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">

    <!-- Sticky Floating Left Share Rail (Visible on XL screens) -->
    <aside class="hidden xl:flex flex-col gap-2.5 fixed left-4 2xl:left-10 top-1/3 z-30 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md p-2 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xl" aria-label="Quick Share Rail">
        <span class="text-[9px] font-black uppercase text-center text-slate-400 py-0.5 tracking-wider">{{ __('शेयर') }}</span>
        <!-- WhatsApp -->
        <a href="https://api.whatsapp.com/send?text={{ urlencode('🔴 *' . $article->title . "*\n\n" . url()->current()) }}" 
           target="_blank" rel="noopener noreferrer" 
           class="w-9 h-9 rounded-xl bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366] hover:text-white flex items-center justify-center transition-all shadow-2xs group" title="WhatsApp">
            <svg class="w-4 h-4 fill-current transition-transform group-hover:scale-110" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.81 13.47 3.81 11.91C3.81 7.37 7.5 3.67 12.05 3.67Z"/></svg>
        </a>
        <!-- Facebook -->
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
           target="_blank" rel="noopener noreferrer" 
           class="w-9 h-9 rounded-xl bg-[#1877F2]/10 text-[#1877F2] hover:bg-[#1877F2] hover:text-white flex items-center justify-center transition-all shadow-2xs group" title="Facebook">
            <svg class="w-4 h-4 fill-current transition-transform group-hover:scale-110" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
        </a>
        <!-- X (Twitter) -->
        <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(url()->current()) }}" 
           target="_blank" rel="noopener noreferrer" 
           class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 hover:bg-black hover:text-white flex items-center justify-center transition-all shadow-2xs group" title="X (Twitter)">
            <svg class="w-4 h-4 fill-current transition-transform group-hover:scale-110" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </a>
        <!-- Telegram -->
        <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}" 
           target="_blank" rel="noopener noreferrer" 
           class="w-9 h-9 rounded-xl bg-[#229ED9]/10 text-[#229ED9] hover:bg-[#229ED9] hover:text-white flex items-center justify-center transition-all shadow-2xs group" title="Telegram">
            <svg class="w-4 h-4 fill-current transition-transform group-hover:scale-110" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.46c.536-.195 1.006.128.832.941z"/></svg>
        </a>
        <!-- Instagram / News Card Maker -->
        <button type="button" 
                class="open-news-poster-btn w-9 h-9 rounded-xl bg-gradient-to-tr from-purple-600 via-pink-600 to-amber-500 text-white flex items-center justify-center transition-all shadow-2xs group cursor-pointer" 
                title="{{ __('इंस्टाग्राम स्टोरी व पोस्ट कार्ड') }}"
                data-title="{{ $article->title }}"
                data-category="{{ $article->category->display_name }}"
                data-category-color="{{ $article->category->color }}"
                data-location="{{ $article->location_badge ?? '' }}"
                data-author="{{ $article->author->name }}"
                data-author-avatar="{{ $article->author->avatar ?: '' }}"
                data-image="{{ $article->featured_image_url }}"
                data-date="{{ $article->published_at ? $article->published_at->format('d M, Y') : now()->format('d M, Y') }}"
                data-url="{{ url()->current() }}">
            <svg class="w-4 h-4 fill-current transition-transform group-hover:scale-110" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
        </button>
        <!-- Copy Link -->
        <button type="button" 
                class="btn-copy-link w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-brand-primary hover:text-white flex items-center justify-center transition-all shadow-2xs group" 
                data-url="{{ url()->current() }}" 
                title="{{ __('लिंक कॉपी करें') }}">
            <svg class="w-4 h-4 fill-none stroke-current transition-transform group-hover:scale-110" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        </button>
        <!-- Bookmark -->
        <button type="button" 
                class="btn-bookmark w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 hover:bg-amber-500 hover:text-white flex items-center justify-center transition-all shadow-2xs group" 
                data-article-id="{{ $article->id }}" 
                title="{{ __('बुकमार्क में सहेजें') }}">
            <svg class="w-4 h-4 fill-none stroke-current transition-transform group-hover:scale-110" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
        </button>
    </aside>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- ========================================== -->
        <!-- LEFT COLUMN: Main News Content (8 cols)    -->
        <!-- ========================================== -->
        <main class="lg:col-span-8 space-y-8">
            
            <!-- Article Header -->
            <header class="space-y-4">
                <!-- Category & Badges -->
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('portal.category', $article->category->slug) }}" 
                       class="px-3.5 py-1 rounded-full text-xs font-black text-white shadow-sm inline-block"
                       style="background-color: {{ $article->category->color }}">
                        {{ $article->category->display_name }}
                    </a>

                    @if($article->hasLocation())
                        @if($article->city)
                            <a href="{{ route('portal.city', $article->city->slug) }}" 
                               class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 hover:bg-rose-600 hover:text-white transition-colors inline-flex items-center gap-1 shadow-2xs">
                                <span>📍</span>
                                <span>{{ $article->city->display_name }} ({{ $article->state?->display_name }})</span>
                            </a>
                        @elseif($article->state)
                            <a href="{{ route('portal.state', $article->state->slug) }}" 
                               class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 hover:bg-rose-600 hover:text-white transition-colors inline-flex items-center gap-1 shadow-2xs">
                                <span>📍</span>
                                <span>{{ $article->state->display_name }}</span>
                            </a>
                        @endif

                        @if($article->location_name)
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 rounded-md">
                                {{ $article->location_name }}
                            </span>
                        @endif
                    @endif

                    @if($article->is_breaking)
                        <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-rose-600 text-white flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-white live-pulse"></span>
                            {{ __('ब्रेकिंग न्यूज़') }}
                        </span>
                    @endif
                </div>

                <!-- Headline -->
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 dark:text-white leading-tight tracking-tight">
                    {{ $article->title }}
                </h1>

                <!-- Short Excerpt / Sub-headline -->
                @if($article->excerpt)
                    <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 font-medium leading-relaxed">
                        {{ $article->excerpt }}
                    </p>
                @endif

                <!-- Author, Date & Reading Stats Bar -->
                <div class="flex flex-wrap items-center justify-between gap-4 py-4 border-y border-slate-200 dark:border-slate-800 text-xs text-slate-500 dark:text-slate-400">
                    <div class="flex items-center gap-3">
                        <img src="{{ $article->author->avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" 
                             alt="{{ $article->author->name }}" 
                             class="w-10 h-10 rounded-full object-cover ring-2 ring-brand-primary">
                        <div>
                            <span class="block font-bold text-slate-900 dark:text-white text-sm leading-tight">{{ $article->author->name }}</span>
                            <span class="block text-[11px] text-slate-500 uppercase font-semibold">{{ $article->author->role }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 text-xs font-medium">
                        <span>{{ $article->published_at ? $article->published_at->format('d M, Y | h:i A') : 'अभी प्रकाशित' }}</span>
                        <span>•</span>
                        <span>{{ $article->reading_time }} {{ __('मिनट पढ़ाई') }}</span>
                        <span>•</span>
                        <span class="flex items-center gap-1 font-bold text-slate-700 dark:text-slate-300">
                            <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            {{ number_format($article->views_count) }} {{ __('व्यूज') }}
                        </span>
                    </div>
                </div>
            </header>

            <!-- Audio Narration Player Box -->
            <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-slate-900 to-slate-950 text-white shadow-lg border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-primary text-white flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                    </div>
                    <div>
                        <span class="block font-bold text-sm text-white" id="audio-status-text">{{ __('ऑडियो स्टोरी सुनें') }}</span>
                        <span class="block text-xs text-slate-400">{{ __('आवाज़ के साथ पूरी ख़बर सुनें') }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Animated Sound Wave Equalizer -->
                    <div id="audio-wave" class="hidden flex items-end gap-1 h-5 px-2">
                        <span class="w-1 bg-amber-400 rounded-full sound-wave-bar"></span>
                        <span class="w-1 bg-brand-primary rounded-full sound-wave-bar"></span>
                        <span class="w-1 bg-amber-400 rounded-full sound-wave-bar"></span>
                        <span class="w-1 bg-brand-primary rounded-full sound-wave-bar"></span>
                    </div>

                    <button id="audio-play-btn" class="bg-brand-primary hover:brightness-110 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition-all shadow-md flex items-center gap-2">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                        <span>{{ __('ख़बर सुनें (Audio)') }}</span>
                    </button>
                </div>
            </div>

            <!-- Media Coverage: Video or Featured Image -->
            @if($article->hasVideo())
                <div class="space-y-2">
                    <div class="rounded-3xl overflow-hidden shadow-2xl bg-black border border-slate-800">
                        @if($article->isYoutubeVideo())
                            <div class="relative w-full aspect-video">
                                <iframe 
                                    class="absolute inset-0 w-full h-full rounded-3xl"
                                    src="{{ $article->youtube_embed_url }}" 
                                    title="{{ $article->title }}" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                        @elseif($article->isDirectVideo())
                            <div class="relative w-full aspect-video bg-black flex items-center justify-center">
                                <video 
                                    controls 
                                    playsinline
                                    preload="metadata"
                                    poster="{{ $article->featured_image }}" 
                                    class="w-full h-full object-contain rounded-3xl">
                                    <source src="{{ $article->video_url }}" type="video/mp4">
                                    <source src="{{ $article->video_url }}" type="video/webm">
                                    HTML5 video is not supported by your browser.
                                </video>
                            </div>
                        @endif
                        <div class="px-4 py-2.5 bg-slate-950/90 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-300">
                            <span class="flex items-center gap-2 font-bold text-rose-500">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                                <span>{{ $article->isYoutubeVideo() ? __('यूट्यूब वीडियो विशेष कवरेज') : __('एक्सक्लूसिव डिजिटल वीडियो रिपोर्ट') }}</span>
                            </span>
                            <span class="text-[11px] text-slate-400 bg-slate-800 px-2 py-0.5 rounded font-mono">HD Quality</span>
                        </div>
                    </div>
                    @if($article->image_caption)
                        <p class="text-xs text-slate-500 dark:text-slate-400 italic text-center">
                            {{ $article->image_caption }}
                        </p>
                    @endif
                </div>
            @elseif($article->featured_image)
                <div class="space-y-2">
                    <div class="rounded-3xl overflow-hidden shadow-xl max-h-[460px]">
                        <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                    </div>
                    @if($article->image_caption)
                        <p class="text-xs text-slate-500 dark:text-slate-400 italic text-center">
                            {{ $article->image_caption }}
                        </p>
                    @endif
                </div>
            @endif

            <!-- Article Content Body -->
            <article id="article-content-body" class="article-prose text-slate-800 dark:text-slate-200">
                {!! $article->content !!}
            </article>

            <!-- Tags Cloud -->
            @if($article->tags->isNotEmpty())
                <div class="pt-6 border-t border-slate-200 dark:border-slate-800 flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 {{ ($isRtl ?? false) ? 'ml-2' : 'mr-2' }}">Tags:</span>
                    @foreach($article->tags as $t)
                        <a href="{{ route('portal.tag', $t->slug) }}" class="text-xs font-semibold px-3 py-1 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-brand-primary hover:text-white transition-colors">
                            #{{ $t->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Share & Bookmark Action Bar (Complete Multi-Platform Social Suite) -->
            @php
                $shareUrl = url()->current();
                $shareTitle = $article->title;
                $encodedUrl = urlencode($shareUrl);
                $encodedTitle = urlencode($shareTitle);
                $waText = urlencode("🔴 *" . $shareTitle . "*\n\n" . $shareUrl);
                $fbUrl = "https://www.facebook.com/sharer/sharer.php?u=" . $encodedUrl;
                $twUrl = "https://twitter.com/intent/tweet?text=" . $encodedTitle . "&url=" . $encodedUrl;
                $tgUrl = "https://t.me/share/url?url=" . $encodedUrl . "&text=" . $encodedTitle;
                $liUrl = "https://www.linkedin.com/sharing/share-offsite/?url=" . $encodedUrl;
                $waUrl = "https://api.whatsapp.com/send?text=" . $waText;
            @endphp
            <div class="p-5 sm:p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-md space-y-4">
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="p-1.5 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-brand-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        </span>
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-800 dark:text-slate-200">
                            {{ __('शेयर करें:') }} <span class="text-slate-400 font-normal">({{ __('सोशल मीडिया पर खबर साझा करें') }})</span>
                        </h4>
                    </div>

                    <!-- Bookmark Button -->
                    <button class="btn-bookmark inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:text-brand-primary text-xs font-bold transition-all" data-article-id="{{ $article->id }}" title="{{ __('बुकमार्क में सहेजें') }}">
                        <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        <span class="hidden sm:inline">{{ __('बुकमार्क') }}</span>
                    </button>
                </div>

                <!-- 1-Click Multi-Platform Sharing Buttons Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2.5">
                    <!-- 1. WhatsApp -->
                    <a href="{{ $waUrl }}" 
                       target="_blank" rel="noopener noreferrer" 
                       aria-label="Share on WhatsApp"
                       class="flex flex-col sm:flex-row items-center justify-center gap-2 p-2.5 rounded-2xl bg-[#25D366]/10 hover:bg-[#25D366] text-[#25D366] hover:text-white border border-[#25D366]/30 transition-all font-bold text-xs group shadow-2xs">
                        <svg class="w-4 h-4 fill-current shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.81 13.47 3.81 11.91C3.81 7.37 7.5 3.67 12.05 3.67Z"/></svg>
                        <span>WhatsApp</span>
                    </a>

                    <!-- 2. Facebook -->
                    <a href="{{ $fbUrl }}" 
                       target="_blank" rel="noopener noreferrer" 
                       aria-label="Share on Facebook"
                       class="flex flex-col sm:flex-row items-center justify-center gap-2 p-2.5 rounded-2xl bg-[#1877F2]/10 hover:bg-[#1877F2] text-[#1877F2] hover:text-white border border-[#1877F2]/30 transition-all font-bold text-xs group shadow-2xs">
                        <svg class="w-4 h-4 fill-current shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        <span>Facebook</span>
                    </a>

                    <!-- 3. X (Twitter) -->
                    <a href="{{ $twUrl }}" 
                       target="_blank" rel="noopener noreferrer" 
                       aria-label="Share on X"
                       class="flex flex-col sm:flex-row items-center justify-center gap-2 p-2.5 rounded-2xl bg-slate-900/10 dark:bg-slate-800 hover:bg-black dark:hover:bg-slate-950 text-slate-900 dark:text-slate-100 hover:text-white border border-slate-300 dark:border-slate-700 transition-all font-bold text-xs group shadow-2xs">
                        <svg class="w-4 h-4 fill-current shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        <span>X (Twitter)</span>
                    </a>

                    <!-- 4. Telegram -->
                    <a href="{{ $tgUrl }}" 
                       target="_blank" rel="noopener noreferrer" 
                       aria-label="Share on Telegram"
                       class="flex flex-col sm:flex-row items-center justify-center gap-2 p-2.5 rounded-2xl bg-[#229ED9]/10 hover:bg-[#229ED9] text-[#229ED9] hover:text-white border border-[#229ED9]/30 transition-all font-bold text-xs group shadow-2xs">
                        <svg class="w-4 h-4 fill-current shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.46c.536-.195 1.006.128.832.941z"/></svg>
                        <span>Telegram</span>
                    </a>

                    <!-- 5. LinkedIn -->
                    <a href="{{ $liUrl }}" 
                       target="_blank" rel="noopener noreferrer" 
                       aria-label="Share on LinkedIn"
                       class="flex flex-col sm:flex-row items-center justify-center gap-2 p-2.5 rounded-2xl bg-[#0A66C2]/10 hover:bg-[#0A66C2] text-[#0A66C2] hover:text-white border border-[#0A66C2]/30 transition-all font-bold text-xs group shadow-2xs">
                        <svg class="w-4 h-4 fill-current shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        <span>LinkedIn</span>
                    </a>

                    <!-- 6. Instagram & News Card Maker (Automatic Graphic Card Studio 1080p) -->
                    <button type="button" 
                            class="open-news-poster-btn flex flex-col sm:flex-row items-center justify-center gap-1.5 p-2.5 rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-amber-500 hover:brightness-110 text-white font-bold text-xs shadow-md shadow-pink-600/20 transition-all cursor-pointer group"
                            title="{{ __('सोशल मीडिया न्यूज़ कार्ड बनाएं') }}"
                            data-title="{{ $article->title }}"
                            data-category="{{ $article->category->display_name }}"
                            data-category-color="{{ $article->category->color }}"
                            data-location="{{ $article->location_badge ?? '' }}"
                            data-author="{{ $article->author->name }}"
                            data-author-avatar="{{ $article->author->avatar ?: '' }}"
                            data-image="{{ $article->featured_image_url }}"
                            data-date="{{ $article->published_at ? $article->published_at->format('d M, Y') : now()->format('d M, Y') }}"
                            data-url="{{ url()->current() }}">
                        <svg class="w-4 h-4 fill-current shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        <span>{{ __('📸 न्यूज़ कार्ड बनाएं') }}</span>
                    </button>

                    <!-- 7. Copy Link & Native Share Dual Action -->
                    <div class="col-span-2 sm:col-span-2 md:col-span-1 flex items-center gap-1.5">
                        <!-- Native Share Button for Mobile -->
                        <button type="button" 
                                class="btn-native-share flex-1 flex items-center justify-center p-2.5 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 hover:bg-indigo-600 hover:text-white text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 transition-all font-bold text-xs" 
                                data-title="{{ $article->title }}" 
                                data-url="{{ $shareUrl }}"
                                title="{{ __('अधिक शेयर विकल्प') }}">
                            <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        </button>

                        <!-- Copy Link Button -->
                        <button type="button" 
                                class="btn-copy-link flex-1 flex items-center justify-center p-2.5 rounded-2xl bg-slate-100 dark:bg-slate-800 hover:bg-brand-primary hover:text-white text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition-all font-bold text-xs" 
                                data-url="{{ $shareUrl }}" 
                                title="{{ __('लिंक कॉपी करें') }}">
                            <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Related Stories 4-Card Grid -->
            @if(isset($relatedArticles) && $relatedArticles->isNotEmpty())
                <section class="space-y-6 pt-8 border-t-2 border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-between">
                        <h3 class="font-black text-xl sm:text-2xl text-slate-900 dark:text-white flex items-center gap-2.5">
                            <span class="w-3.5 h-3.5 rounded bg-brand-primary"></span>
                            <span>{{ __('संबंधित व अन्य महत्वपूर्ण ख़बरें') }}</span>
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        @foreach($relatedArticles as $rel)
                            <article class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-xl transition-all flex flex-col justify-between news-card-hover group">
                                <div>
                                    @if($rel->featured_image)
                                        <div class="h-44 overflow-hidden relative">
                                            <img src="{{ $rel->featured_image }}" alt="{{ $rel->title }}" class="w-full h-full object-cover">
                                            <span class="absolute top-2.5 {{ ($isRtl ?? false) ? 'right-2.5' : 'left-2.5' }} px-2.5 py-0.5 rounded-full text-[10px] font-bold text-white shadow-sm" style="background-color: {{ $rel->category->color }}">
                                                {{ $rel->category->display_name }}
                                            </span>
                                        </div>
                                    @endif

                                    <div class="p-4 space-y-1.5">
                                        <h4 class="font-bold text-sm text-slate-900 dark:text-white line-clamp-2 group-hover:text-brand-primary transition-colors leading-snug">
                                            <a href="{{ route('portal.article', $rel->slug) }}">{{ $rel->title }}</a>
                                        </h4>
                                        @if($rel->excerpt)
                                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
                                                {{ $rel->excerpt }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="px-4 pb-4 pt-2 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400">
                                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $rel->author->name }}</span>
                                    <div class="flex items-center gap-2">
                                        <span>{{ $rel->published_at ? $rel->published_at->diffForHumans() : '' }}</span>
                                        <span>•</span>
                                        <span>{{ number_format($rel->views_count) }} {{ __('व्यूज') }}</span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Reader Comments Section -->
            <section class="pt-8 border-t border-slate-200 dark:border-slate-800 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-black text-xl text-slate-900 dark:text-white">
                        {{ __('पाठकों की टिप्पणियां') }} ({{ $article->approvedComments->count() }})
                    </h3>
                </div>

                <!-- Post Comment Form -->
                <form action="{{ route('portal.comment.store', $article->slug) }}" method="POST" class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                    @csrf
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ __('अपनी टिप्पणी दें') }}</h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">{{ __('आपका नाम *') }}</label>
                            <input type="text" name="user_name" required placeholder="User Name..." 
                                   class="w-full bg-slate-50 dark:bg-slate-800 text-sm rounded-xl px-3.5 py-2.5 border border-slate-200 dark:border-slate-700 focus:border-brand-primary outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">{{ __('ईमेल एड्रेस *') }}</label>
                            <input type="email" name="user_email" required placeholder="user@example.com" 
                                   class="w-full bg-slate-50 dark:bg-slate-800 text-sm rounded-xl px-3.5 py-2.5 border border-slate-200 dark:border-slate-700 focus:border-brand-primary outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">{{ __('आपकी टिप्पणी *') }}</label>
                        <textarea name="comment" rows="3" required placeholder="{{ __('इस खबर पर अपने विचार रखें...') }}" 
                                  class="w-full bg-slate-50 dark:bg-slate-800 text-sm rounded-xl p-3.5 border border-slate-200 dark:border-slate-700 focus:border-brand-primary outline-none"></textarea>
                    </div>

                    <button type="submit" class="bg-brand-primary hover:brightness-110 text-white font-bold text-xs px-6 py-2.5 rounded-xl transition-all shadow-md">
                        {{ __('टिप्पणी प्रकाशित करें') }}
                    </button>
                </form>

                <!-- Comments List -->
                <div class="space-y-4">
                    @forelse($article->approvedComments as $comm)
                        <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-sm text-slate-900 dark:text-white">{{ $comm->user_name }}</span>
                                <span class="text-xs text-slate-400">{{ $comm->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                                {{ $comm->comment }}
                            </p>
                        </div>
                    @empty
                        <p class="text-center text-xs text-slate-400 py-4">{{ __('अभी तक कोई टिप्पणी नहीं है। सबसे पहले टिप्पणी आप करें!') }}</p>
                    @endforelse
                </div>
            </section>

        </main>

        <!-- ============================================================== -->
        <!-- RIGHT COLUMN: MULTIPLE ADVERTISEMENTS & SIDEBAR (4 cols)        -->
        <!-- ============================================================== -->
        <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-16">

            <!-- 1. Custom Article-Specific Advertisement (Set by Reporter) -->
            @if($article->custom_ad_type === 'image' && $article->custom_ad_image)
                <div class="rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-md p-3 space-y-2">
                    <div class="flex items-center justify-between px-1">
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            {{ $article->custom_ad_title ?: __('प्रायोजित विज्ञापन') }}
                        </span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest border border-slate-200 dark:border-slate-700 px-1.5 py-0.5 rounded">AD</span>
                    </div>

                    <a href="{{ route('portal.article.ad.click', $article->id) }}" target="_blank" rel="noopener sponsored" class="block rounded-2xl overflow-hidden group">
                        <img src="{{ $article->custom_ad_image }}" alt="{{ $article->custom_ad_title }}" class="w-full h-auto object-cover rounded-2xl transition-transform duration-500 group-hover:scale-102">
                    </a>
                </div>
            @elseif($article->custom_ad_type === 'code' && $article->custom_ad_code)
                <div class="rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-md p-3 space-y-2">
                    <div class="flex items-center justify-between px-1">
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">
                            {{ $article->custom_ad_title ?: __('प्रायोजित विज्ञापन') }}
                        </span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest border border-slate-200 dark:border-slate-700 px-1.5 py-0.5 rounded">AD</span>
                    </div>

                    <div class="overflow-hidden rounded-2xl bg-slate-50 dark:bg-slate-950 p-2 flex justify-center">
                        {!! $article->custom_ad_code !!}
                    </div>
                </div>
            @endif

            <!-- 2. Multiple Global Sidebar Advertisements (Custom Image & Google AdSense) -->
            @if(isset($sidebarAds) && $sidebarAds->isNotEmpty())
                @foreach($sidebarAds as $ad)
                    <div class="rounded-3xl overflow-hidden border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm hover:shadow-md transition-shadow p-3.5 space-y-2">
                        <div class="flex items-center justify-between px-1">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                {{ $ad->title ?: __('विज्ञापन') }}
                            </span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest border border-slate-200 dark:border-slate-700 px-1.5 py-0.5 rounded">AD</span>
                        </div>

                        @if($ad->type === 'image' && $ad->image_url)
                            <a href="{{ route('portal.ad.click', $ad->id) }}" target="_blank" rel="noopener sponsored" class="block rounded-2xl overflow-hidden group">
                                <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="w-full h-auto object-cover rounded-2xl transition-transform duration-500 group-hover:scale-102">
                            </a>
                        @elseif($ad->type === 'code' && $ad->html_code)
                            <div class="overflow-hidden rounded-2xl bg-slate-50 dark:bg-slate-950 p-2 flex justify-center min-h-[120px] items-center">
                                {!! $ad->html_code !!}
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif

            <!-- 2.5 WhatsApp Channel Connect Card with Live QR Code -->
            @include('components.whatsapp-channel-card')

            <!-- 3. Trending Stories Widget in Sidebar -->
            @if(isset($trendingArticles) && $trendingArticles->isNotEmpty())
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-4">
                    <h4 class="font-black text-sm uppercase tracking-wider text-slate-900 dark:text-white pb-2 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span>{{ __('ट्रेंडिंग ख़बरें') }}</span>
                    </h4>

                    <div class="space-y-3">
                        @foreach($trendingArticles->take(4) as $idx => $trend)
                            <a href="{{ route('portal.article', $trend->slug) }}" class="flex items-start gap-3 group">
                                <span class="text-lg font-black text-slate-400 group-hover:text-brand-primary font-mono shrink-0">
                                    0{{ $idx + 1 }}
                                </span>
                                <div class="min-w-0">
                                    <h5 class="text-xs font-bold text-slate-800 dark:text-slate-200 line-clamp-2 group-hover:text-brand-primary transition-colors leading-snug">
                                        {{ $trend->title }}
                                    </h5>
                                    <span class="text-[10px] text-slate-400 mt-0.5 block">{{ number_format($trend->views_count) }} {{ __('रीडर्स') }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 4. Author Details Card -->
            <div class="bg-gradient-to-br from-slate-900 to-slate-950 text-white rounded-3xl p-5 border border-slate-800 shadow-md space-y-3">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400">{{ __('लेखक परिचय') }}</span>
                <div class="flex items-center gap-3">
                    <img src="{{ $article->author->avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" alt="" class="w-12 h-12 rounded-full object-cover ring-2 ring-brand-primary">
                    <div>
                        <h4 class="font-black text-sm text-white">{{ $article->author->name }}</h4>
                        <span class="text-xs text-slate-400">{{ $article->author->role }}</span>
                    </div>
                </div>
                @if($article->author->bio)
                    <p class="text-xs text-slate-300 leading-relaxed">
                        {{ $article->author->bio }}
                    </p>
                @endif
            </div>

        </aside>

    </div>
</div>
@endsection
