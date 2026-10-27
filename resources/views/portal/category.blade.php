@extends('layouts.portal')

@section('title', $category->display_name . ' - ' . setting('site_name', 'The LallanTop News'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    <!-- Category Banner Header -->
    <div class="p-8 rounded-3xl text-white shadow-xl relative overflow-hidden" style="background: linear-gradient(135deg, {{ $category->color }} 0%, #0F172A 100%);">
        <div class="relative z-10 max-w-2xl space-y-2">
            <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/20 backdrop-blur-md inline-block">
                {{ __('प्रमुख श्रेणियां') }}
            </span>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight">
                {{ $category->display_name }}
            </h1>
            <p class="text-sm text-slate-200 leading-relaxed font-medium">
                {{ $category->description ?: 'इस श्रेणी से जुड़ी सभी ताज़ा ख़बरें और विशेष रिपोर्ट्स।' }}
            </p>
        </div>
    </div>

    <!-- Articles Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $art)
            <article class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-xl transition-all flex flex-col justify-between news-card-hover group">
                <div>
                    @if($art->featured_image)
                        <div class="h-48 overflow-hidden relative">
                            <img src="{{ $art->featured_image }}" alt="{{ $art->title }}" class="w-full h-full object-cover">
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
            <div class="col-span-full p-12 text-center text-slate-400">
                {{ __('इस श्रेणी में अभी कोई खबर प्रकाशित नहीं हुई है।') }}
            </div>
        @endforelse
    </div>

    <div class="pt-6">
        {{ $articles->links() }}
    </div>

</div>
@endsection
