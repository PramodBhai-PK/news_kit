@extends('layouts.portal')

@section('title', $city->display_name . ' न्यूज़ - ' . $city->state->display_name . ' | ताज़ा ख़बरें व ब्रेकिंग अपडेट्स')
@section('meta_description', $city->display_name . ' (' . $city->name . '), ' . $city->state->display_name . ' की ताज़ा और ब्रेकिंग खबरें। नगर निगम, स्थानीय राजनीति, अपराध, मौसम और विकास से जुड़ी हर अपडेट पढ़ें।')

@section('canonical_url', route('portal.city', $city->slug))

@section('extra_seo')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => $city->display_name . ' समाचार - ' . setting('site_name', 'The LallanTop News'),
    'description' => $city->display_name . ' की सभी ताज़ा खबरें',
    'url' => route('portal.city', $city->slug),
    'about' => [
        '@type' => 'City',
        'name' => $city->name_en ?: $city->name,
        'alternateName' => $city->name,
        'containedInPlace' => [
            '@type' => 'AdministrativeArea',
            'name' => $city->state->name_en ?: $city->state->name,
            'alternateName' => $city->state->name
        ]
    ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center text-xs font-semibold text-slate-500 dark:text-slate-400 gap-2 flex-wrap">
        <a href="{{ route('portal.index') }}" class="hover:text-brand-primary transition-colors">{{ __('होम') }}</a>
        <span>/</span>
        <a href="{{ route('portal.state', $city->state->slug) }}" class="hover:text-brand-primary transition-colors">{{ $city->state->display_name }}</a>
        <span>/</span>
        <span class="text-brand-primary dark:text-rose-400 font-bold">{{ $city->display_name }}</span>
    </nav>

    <!-- City Hero Banner -->
    <div class="p-6 sm:p-10 rounded-3xl text-white shadow-xl relative overflow-hidden bg-linear-to-r from-slate-950 via-slate-900 to-indigo-950 border border-slate-800">
        <div class="absolute -right-10 -bottom-10 opacity-10 text-9xl font-black select-none pointer-events-none">
            {{ $city->name_en ?: $city->name }}
        </div>
        
        <div class="relative z-10 max-w-3xl space-y-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-rose-600/30 text-rose-300 border border-rose-500/30 backdrop-blur-md inline-flex items-center gap-1.5">
                    <span>📍</span> {{ __('हाइपरलोकल जिला डेस्क') }}
                </span>
                <a href="{{ route('portal.state', $city->state->slug) }}" class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/10 hover:bg-white/20 text-slate-200 transition-colors">
                    {{ $city->state->display_name }}
                </a>
            </div>

            <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                {{ $city->display_name }} @if($city->name_en)<span class="text-xl sm:text-2xl font-medium text-slate-300 font-sans">({{ $city->name_en }})</span>@endif
            </h1>

            <p class="text-sm sm:text-base text-slate-300 leading-relaxed font-normal">
                {{ $city->display_name }} ({{ $city->state->display_name }}) की ताजा खबरें, स्थानीय समस्याएं, नगर प्रशासन, ट्रैफिक, मौसम और आसपास के गांवों-कस्बों की हर बड़ी खबर।
            </p>

            <!-- Quick Action: Make Default City -->
            <div class="pt-3 flex flex-wrap items-center gap-3">
                @if(session('user_city_slug') === $city->slug)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold">
                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>{{ __('आपका चुना हुआ शहर') }}</span>
                    </span>
                    <a href="{{ route('portal.set-city', 'clear') }}" class="text-xs text-slate-400 hover:text-white underline">
                        {{ __('बदलें या रीसेट करें') }}
                    </a>
                @else
                    <a href="{{ route('portal.set-city', $city->slug) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-primary hover:brightness-110 text-white text-xs font-bold shadow-md transition-all transform hover:-translate-y-0.5">
                        <span>📍</span>
                        <span>{{ $city->display_name }} को अपना मुख्य शहर बनाएं</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Sibling Cities Pills -->
        @if($siblingCities->isNotEmpty())
        <div class="mt-8 pt-6 border-t border-white/10 relative z-10">
            <div class="flex items-center justify-between mb-3 text-xs font-bold text-slate-300">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    <span>{{ $city->state->display_name }} के अन्य जिले व शहर:</span>
                </span>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach($siblingCities as $sib)
                    <a href="{{ route('portal.city', $sib->slug) }}" 
                       class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-1 {{ $sib->id === $city->id ? 'bg-brand-primary text-white shadow-xs font-bold' : 'bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white border border-white/10' }}">
                        <span>{{ $sib->display_name }}</span>
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
                    <span>{{ $city->display_name }} से ताज़ा खबरें</span>
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
                                    @if($art->location_name)
                                        <span class="absolute top-3 left-3 bg-black/70 backdrop-blur-md text-white text-[11px] font-bold px-2.5 py-1 rounded-md flex items-center gap-1">
                                            <span>📍</span> {{ $art->location_name }}
                                        </span>
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
                            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $art->author->name ?? 'लोकल रिपोर्टर' }}</span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                {{ number_format($art->views_count) }}
                            </span>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full p-12 text-center bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-dashed border-slate-300 dark:border-slate-800 text-slate-400 space-y-2">
                        <span class="text-4xl">📍</span>
                        <p class="text-sm font-semibold">{{ $city->display_name }} से संबंधित खबरें जल्द ही लाइव की जाएंगी।</p>
                        <div class="pt-2">
                            <a href="{{ route('portal.state', $city->state->slug) }}" class="inline-flex items-center gap-1 text-xs text-brand-primary font-bold hover:underline">
                                <span>{{ $city->state->display_name }} राज्य की खबरें देखें →</span>
                            </a>
                        </div>
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
            
            <!-- Trending in City/State -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs">
                <h3 class="text-base font-black text-slate-900 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                    <span class="text-amber-500">🔥</span>
                    <span>{{ __('क्षेत्रीय ट्रेंडिंग न्यूज़') }}</span>
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

            <!-- State Link Box -->
            <div class="bg-linear-to-br from-indigo-900 to-slate-900 text-white rounded-2xl p-6 shadow-md space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-300">📍 {{ __('राज्य मुख्य पृष्ठ') }}</span>
                <h4 class="text-lg font-black">{{ $city->state->display_name }} राज्य डेस्क</h4>
                <p class="text-xs text-indigo-100/80 leading-relaxed">
                    पूरे {{ $city->state->display_name }} के सभी जिलों, राजनीति और सरकारी योजनाओं की विस्तृत रिपोर्ट्स पढ़ें।
                </p>
                <a href="{{ route('portal.state', $city->state->slug) }}" class="inline-flex items-center gap-2 text-xs font-black bg-white text-slate-950 px-4 py-2 rounded-xl hover:bg-slate-100 transition-colors shadow-xs">
                    <span>{{ $city->state->display_name }} की खबरें पढ़ें</span>
                    <span>→</span>
                </a>
            </div>

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
