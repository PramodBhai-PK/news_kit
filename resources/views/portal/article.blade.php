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
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

            <!-- Share & Bookmark Action Bar -->
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('शेयर करें:') }}</span>
                    
                    <!-- WhatsApp Share -->
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' ' . url()->current()) }}" 
                       target="_blank" rel="noopener" aria-label="Share on WhatsApp"
                       class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-all">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.81 13.47 3.81 11.91C3.81 7.37 7.5 3.67 12.05 3.67Z"/></svg>
                    </a>

                    <!-- Twitter / X Share -->
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(url()->current()) }}" 
                       target="_blank" rel="noopener" aria-label="Share on X"
                       class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 hover:bg-slate-900 hover:text-white flex items-center justify-center transition-all">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>

                    <!-- Copy Link -->
                    <button class="btn-copy-link w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-brand-primary hover:text-white flex items-center justify-center transition-all" title="{{ __('लिंक कॉपी करें') }}">
                        <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </button>

                    <!-- Automatic Graphic Card Maker Button -->
                    <button type="button" 
                            class="open-news-poster-btn inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-xs font-bold shadow-md hover:shadow-lg transition-all cursor-pointer"
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
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>{{ __('📸 न्यूज़ कार्ड बनाएं') }}</span>
                    </button>
                </div>

                <div>
                    <!-- Bookmark Button -->
                    <button class="btn-bookmark inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:text-brand-primary text-xs font-bold transition-all" data-article-id="{{ $article->id }}">
                        <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        <span>{{ __('बुकमार्क में सहेजें') }}</span>
                    </button>
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
