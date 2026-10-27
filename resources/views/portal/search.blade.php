@extends('layouts.portal')

@section('title', __('खोजें') . ': "' . $query . '" - ' . setting('site_name', 'The LallanTop News'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    <!-- Search Header -->
    <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('खोजें') }}</span>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">
            "{{ $query }}"
        </h1>
        <p class="text-xs text-slate-500">{{ $articles->total() }} {{ __('ख़बरें उपलब्ध') }}</p>

        <form action="{{ route('portal.search') }}" method="GET" class="max-w-xl">
            <div class="relative">
                <input type="text" name="q" value="{{ $query }}" placeholder="{{ __('खबरें खोजें...') }}"
                       class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-sm rounded-xl px-4 py-3 border border-slate-200 dark:border-slate-700 focus:border-brand-primary outline-none">
                <button type="submit" class="absolute {{ ($isRtl ?? false) ? 'left-2' : 'right-2' }} top-2 bg-brand-primary text-white text-xs font-bold px-4 py-2 rounded-lg hover:brightness-110">
                    {{ __('खोजें') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Search Results Grid -->
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
                        @if($art->excerpt)
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
                                {{ $art->excerpt }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="px-5 pb-5 pt-2 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $art->author->name }}</span>
                    <span>{{ number_format($art->views_count) }} {{ __('व्यूज') }}</span>
                </div>
            </article>
        @empty
            <div class="col-span-full p-12 text-center text-slate-400 space-y-2">
                <p class="text-base font-bold text-slate-600 dark:text-slate-300">"{{ $query }}" - कोई खबर नहीं मिली</p>
            </div>
        @endforelse
    </div>

    <div class="pt-6">
        {{ $articles->links() }}
    </div>

</div>
@endsection
