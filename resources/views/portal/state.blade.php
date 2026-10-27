@extends('layouts.portal')

@section('title', $state->display_name . ' न्यूज़ - ताज़ा ख़बरें और अपडेट्स | ' . setting('site_name', 'The LallanTop News'))
@section('meta_description', $state->display_name . ' (' . $state->name . ') के सभी जिलों की ताज़ा और ब्रेकिंग खबरें, राजनीतिक हलचल, अपराध, विकास और स्थानीय समाचार पढ़ें।')

@section('canonical_url', route('portal.state', $state->slug))

@section('extra_seo')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => $state->display_name . ' समाचार - ' . setting('site_name', 'The LallanTop News'),
    'description' => $state->display_name . ' के सभी जिलों की ताज़ा खबरें',
    'url' => route('portal.state', $state->slug),
    'about' => [
        '@type' => 'AdministrativeArea',
        'name' => $state->name_en ?: $state->name,
        'alternateName' => $state->name
    ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center text-xs font-semibold text-slate-500 dark:text-slate-400 gap-2">
        <a href="{{ route('portal.index') }}" class="hover:text-brand-primary transition-colors">{{ __('होम') }}</a>
        <span>/</span>
        <span class="text-slate-400 dark:text-slate-500">{{ __('राज्य न्यूज़') }}</span>
        <span>/</span>
        <span class="text-brand-primary dark:text-rose-400 font-bold">{{ $state->display_name }}</span>
    </nav>

    <!-- State Hero Banner -->
    <div class="p-6 sm:p-10 rounded-3xl text-white shadow-xl relative overflow-hidden bg-linear-to-r from-slate-900 via-indigo-950 to-slate-900 border border-slate-800">
        <div class="absolute -right-10 -bottom-10 opacity-10 text-9xl font-black select-none pointer-events-none">
            {{ $state->name_en ?: $state->name }}
        </div>
        
        <div class="relative z-10 max-w-3xl space-y-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-rose-600/30 text-rose-300 border border-rose-500/30 backdrop-blur-md inline-flex items-center gap-1.5">
                    <span>📍</span> {{ __('राज्य विशेष कवरेज') }}
                </span>
                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/10 text-slate-200">
                    {{ $state->cities->count() }} {{ __('प्रमुख जिले व शहर') }}
                </span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                {{ $state->display_name }} @if($state->name_en)<span class="text-xl sm:text-2xl font-medium text-slate-300 font-sans">({{ $state->name_en }})</span>@endif
            </h1>

            <p class="text-sm sm:text-base text-slate-300 leading-relaxed font-normal">
                {{ $state->display_name }} के राजनीति, विकास, अपराध, शिक्षा, रोजगार और जनहित से जुड़े हर बड़े मुद्दे की ज़मीनी रिपोर्टिंग।
            </p>
        </div>

        <!-- City Filter Quick Pills -->
        @if($state->cities->isNotEmpty())
        <div class="mt-8 pt-6 border-t border-white/10 relative z-10">
            <div class="flex items-center justify-between mb-3 text-xs font-bold text-slate-300">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    {{ __('प्रमुख शहर व जिले चुनें:') }}
                </span>
            </div>
            <div class="flex flex-wrap gap-2">
                <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-brand-primary text-white shadow-xs">
                    {{ __('सभी जिले') }}
                </span>
                @foreach($state->cities as $ct)
                    <a href="{{ route('portal.city', $ct->slug) }}" 
                       class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white border border-white/10 hover:border-white/30 transition-all flex items-center gap-1">
                        <span>•</span>
                        <span>{{ $ct->display_name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Main Content Layout (Articles + Sidebar) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Articles List (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                <h2 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-brand-primary"></span>
                    <span>{{ $state->display_name }} की ताज़ा ख़बरें</span>
                </h2>
                <span class="text-xs text-slate-500 font-semibold">
                    {{ $articles->total() }} {{ __('खबरें उपलब्ध') }}
                </span>
            </div>

            <!-- Articles Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @forelse($articles as $art)
                    <article class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-xl transition-all flex flex-col justify-between group">
                        <div>
                            @if($art->featured_image)
                                <div class="h-48 overflow-hidden relative">
                                    <a href="{{ route('portal.article', $art->slug) }}">
                                        <img src="{{ $art->featured_image }}" alt="{{ $art->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </a>
                                    @if($art->city)
                                        <a href="{{ route('portal.city', $art->city->slug) }}" 
                                           class="absolute top-3 left-3 bg-black/70 hover:bg-brand-primary backdrop-blur-md text-white text-[11px] font-bold px-2.5 py-1 rounded-md transition-colors flex items-center gap-1">
                                            <span>📍</span> {{ $art->city->display_name }}
                                        </a>
                                    @endif
                                </div>
                            @endif

                            <div class="p-5 space-y-2">
                                <div class="flex items-center gap-2 text-xs text-slate-400">
                                    <span class="font-semibold text-brand-primary dark:text-rose-400">{{ $art->category->display_name ?? 'न्यूज़' }}</span>
                                    <span>•</span>
                                    <span>{{ $art->published_at ? $art->published_at->format('d M, Y') : '' }}</span>
                                </div>

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

                        <div class="px-5 pb-5 pt-3 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $art->author->name ?? 'डेस्क' }}</span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                {{ number_format($art->views_count) }}
                            </span>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full p-12 text-center bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-dashed border-slate-300 dark:border-slate-800 text-slate-400 space-y-2">
                        <span class="text-4xl">📰</span>
                        <p class="text-sm font-semibold">{{ $state->display_name }} से संबंधित खबरें जल्द ही प्रकाशित की जाएंगी।</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="pt-6">
                {{ $articles->links() }}
            </div>
        </div>

        <!-- Right: Sidebar (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Trending in State -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs">
                <h3 class="text-base font-black text-slate-900 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                    <span class="text-amber-500">🔥</span>
                    <span>{{ __('ट्रेंडिंग न्यूज़') }}</span>
                </h3>
                <div class="divide-y divide-slate-100 dark:divide-slate-800/80 mt-2">
                    @forelse($trendingArticles as $idx => $trend)
                        <a href="{{ route('portal.article', $trend->slug) }}" class="py-3 flex items-start gap-3 group">
                            <span class="text-2xl font-black text-slate-300 dark:text-slate-700 group-hover:text-brand-primary transition-colors shrink-0 leading-none">
                                0{{ $idx + 1 }}
                            </span>
                            <div class="space-y-1">
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-brand-primary transition-colors line-clamp-2">
                                    {{ $trend->title }}
                                </h4>
                                <span class="text-[11px] text-slate-400 font-medium">
                                    {{ number_format($trend->views_count) }} {{ __('व्यूज') }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-slate-400 py-3">{{ __('कोई ट्रेंडिंग खबर नहीं है।') }}</p>
                    @endforelse
                </div>
            </div>

            <!-- Other States Navigation Box -->
            @if(isset($portalStates))
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs">
                <h3 class="text-base font-black text-slate-900 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                    <span>🗺️</span>
                    <span>{{ __('अन्य राज्य चुनें') }}</span>
                </h3>
                <div class="grid grid-cols-2 gap-2 mt-3">
                    @foreach($portalStates as $st)
                        <a href="{{ route('portal.state', $st->slug) }}" 
                           class="px-3 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-between {{ $st->id === $state->id ? 'bg-brand-primary text-white shadow-xs' : 'bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/80' }}">
                            <span>{{ $st->display_name }}</span>
                            <span class="text-[10px] opacity-70">→</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Advertisement Slot if available -->
            @if(isset($sidebarAds) && $sidebarAds->isNotEmpty())
                <div class="space-y-4">
                    @foreach($sidebarAds->take(2) as $ad)
                        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800">
                            @if($ad->type === 'image' && $ad->image_url)
                                <a href="{{ route('portal.ad.click', $ad->id) }}" target="_blank" rel="noopener">
                                    <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="w-full h-auto object-cover">
                                </a>
                            @elseif($ad->type === 'code' && $ad->ad_code)
                                {!! $ad->ad_code !!}
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

    </div>

</div>
@endsection
