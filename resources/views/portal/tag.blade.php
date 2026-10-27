@extends('layouts.portal')

@section('title', '#' . $tag->name . ' - ' . setting('site_name', 'The LallanTop News'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    <div class="p-8 rounded-3xl bg-slate-900 text-white shadow-xl flex items-center gap-4">
        <span class="text-3xl text-amber-400 font-mono">#</span>
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('ट्रेंडिंग टॉपिक्स') }}</span>
            <h1 class="text-3xl font-black">{{ $tag->name }}</h1>
        </div>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $art)
            <article class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-xl transition-all flex flex-col justify-between news-card-hover group">
                <div>
                    @if($art->featured_image)
                        <div class="h-48 overflow-hidden relative">
                            <img src="{{ $art->featured_image }}" alt="{{ $art->title }}" class="w-full h-full object-cover">
                            <span class="absolute top-3 {{ ($isRtl ?? false) ? 'right-3' : 'left-3' }} px-2.5 py-0.5 rounded-full text-[11px] font-bold text-white shadow-sm" style="background-color: {{ $art->category->color }}">
                                {{ $art->category->display_name }}
                            </span>
                        </div>
                    @endif

                    <div class="p-5 space-y-2">
                        <span class="text-xs text-slate-400 font-medium">
                            {{ $art->published_at ? $art->published_at->format('d M, Y') : '' }}
                        </span>
                        <h3 class="font-bold text-base text-slate-900 dark:text-white line-clamp-2 group-hover:text-brand-primary transition-colors leading-snug">
                            <a href="{{ route('portal.article', $art->slug) }}">{{ $art->title }}</a>
                        </h3>
                    </div>
                </div>

                <div class="px-5 pb-5 pt-2 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $art->author->name }}</span>
                    <span>{{ number_format($art->views_count) }} {{ __('व्यूज') }}</span>
                </div>
            </article>
        @empty
            <div class="col-span-full p-12 text-center text-slate-400">
                इस टैग से जुड़ी अभी कोई खबर नहीं है।
            </div>
        @endforelse
    </div>

    <div class="pt-6">
        {{ $articles->links() }}
    </div>

</div>
@endsection
