@extends('layouts.admin')

@section('title', 'डैशबोर्ड अवलोकन')
@section('page_title', Auth::user()->isReporter() ? 'रिपोर्टर डैशबोर्ड (Reporter Dashboard)' : 'डैशबोर्ड ओवरव्यू (CMS Overview)')

@section('content')
<div class="space-y-8">

    <!-- 1. Personalized Role Welcome Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-lg border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4 text-center sm:text-left">
            <img src="{{ Auth::user()->avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80' }}" 
                 alt="{{ Auth::user()->name }}" 
                 class="w-16 h-16 rounded-full object-cover ring-4 ring-slate-700 shrink-0">
            <div>
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                    <h2 class="text-xl sm:text-2xl font-black">नमस्ते, {{ Auth::user()->name }}!</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ Auth::user()->role_badge_class }}">
                        {{ Auth::user()->role_label }}
                    </span>
                </div>
                <p class="text-xs text-slate-300 mt-1">
                    @if(Auth::user()->isReporter())
                        यह आपका व्यक्तिगत रिपोर्टिंग डैशबोर्ड है। यहाँ केवल आपकी लिखी ख़बरों, व्यूज और पाठकों की टिप्पणियों का विश्लेषण प्रदर्शित है।
                    @else
                        न्यूज़ पोर्टल का केंद्रीय कंट्रोल सेंटर। यहाँ से सामग्री, श्रेणियां, टीम और ब्रांडिंग का संचालन करें।
                    @endif
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.articles.create') }}" class="bg-brand-primary text-white font-bold text-xs px-4 py-2.5 rounded-xl hover:brightness-110 shadow-md flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>नई ख़बर लिखें</span>
            </a>
            <a href="{{ route('admin.profile.edit') }}" class="bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md flex items-center gap-2 border border-slate-700">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>प्रोफ़ाइल</span>
            </a>
        </div>
    </div>

    <!-- 2. Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Stat 1: Articles -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">
                    {{ Auth::user()->isReporter() ? 'मेरी कुल ख़बरें' : 'कुल ख़बरें (Articles)' }}
                </span>
                <span class="block text-2xl font-black text-slate-900 mt-1">{{ number_format($stats['total_articles']) }}</span>
                <span class="block text-[11px] text-emerald-600 font-semibold mt-0.5">{{ $stats['published_articles'] }} प्रकाशित</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-brand-primary flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
        </div>

        <!-- Stat 2: Views -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">
                    {{ Auth::user()->isReporter() ? 'मेरे आर्टिकल्स के व्यूज' : 'कुल रीडरशिप व्यूज' }}
                </span>
                <span class="block text-2xl font-black text-slate-900 mt-1">{{ number_format($stats['total_views']) }}</span>
                <span class="block text-[11px] text-blue-600 font-semibold mt-0.5">ऑर्गेनिक पाठक</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
        </div>

        <!-- Stat 3: Categories or Drafts -->
        @if(Auth::user()->isReporter())
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">ड्राफ्ट / अप्रकाशित</span>
                    <span class="block text-2xl font-black text-slate-900 mt-1">{{ $stats['draft_articles'] ?? 0 }}</span>
                    <span class="block text-[11px] text-amber-600 font-semibold mt-0.5">समीक्षाधीन ख़बरें</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
            </div>
        @else
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">सक्रिय श्रेणियां</span>
                    <span class="block text-2xl font-black text-slate-900 mt-1">{{ $stats['total_categories'] }}</span>
                    <span class="block text-[11px] text-purple-600 font-semibold mt-0.5">कलर-कोडेड बीट्स</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
            </div>
        @endif

        <!-- Stat 4: Comments -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">
                    {{ Auth::user()->isReporter() ? 'मेरी ख़बरों पर टिप्पणियां' : 'पाठकों की टिप्पणियां' }}
                </span>
                <span class="block text-2xl font-black text-slate-900 mt-1">{{ $stats['total_comments'] }}</span>
                <span class="block text-[11px] text-emerald-600 font-semibold mt-0.5">लाइव सहभागिता</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
        </div>
    </div>

    <!-- 3. Team Statistics Card (Admins & Editors Only) -->
    @if(isset($teamStats) && (Auth::user()->isAdmin() || Auth::user()->isEditor()))
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                    <h3 class="font-bold text-slate-900 text-sm">संपादकीय व रिपोर्टिंग टीम सिंहावलोकन (Team Overview)</h3>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-purple-600 hover:underline flex items-center gap-1">
                    <span>टीम प्रबंधन में जाएं</span>
                    <span>→</span>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-center">
                    <span class="text-[11px] text-slate-500 font-semibold block">कुल टीम सदस्य</span>
                    <span class="text-lg font-black text-slate-900 block mt-0.5">{{ $teamStats['total_team'] }}</span>
                </div>
                <div class="p-3 rounded-xl bg-purple-50/70 border border-purple-100 text-center">
                    <span class="text-[11px] text-purple-700 font-semibold block">वरिष्ठ संपादक (Editors)</span>
                    <span class="text-lg font-black text-purple-900 block mt-0.5">{{ $teamStats['editors'] }}</span>
                </div>
                <div class="p-3 rounded-xl bg-emerald-50/70 border border-emerald-100 text-center">
                    <span class="text-[11px] text-emerald-700 font-semibold block">संवाददाता (Reporters)</span>
                    <span class="text-lg font-black text-emerald-900 block mt-0.5">{{ $teamStats['reporters'] }}</span>
                </div>
                <div class="p-3 rounded-xl bg-blue-50/70 border border-blue-100 text-center">
                    <span class="text-[11px] text-blue-700 font-semibold block">प्रकाशक (Publishers)</span>
                    <span class="text-lg font-black text-blue-900 block mt-0.5">{{ $teamStats['publishers'] }}</span>
                </div>
                <div class="p-3 rounded-xl bg-rose-50/70 border border-rose-100 text-center">
                    <span class="text-[11px] text-rose-700 font-semibold block">एडमिन्स (Super Admins)</span>
                    <span class="text-lg font-black text-rose-900 block mt-0.5">{{ $teamStats['admins'] }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- 4. Main Tables: Recent Stories & Top Trending Stories -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Col 1 & 2: Recent Articles List -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-sm">
                    {{ Auth::user()->isReporter() ? 'मेरी हालिया ख़बरें (My Recent Articles)' : 'हाल ही में प्रकाशित ख़बरें (Recent Articles)' }}
                </h3>
                <a href="{{ route('admin.articles.index') }}" class="text-xs font-semibold text-brand-primary hover:underline">सभी देखें →</a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentArticles as $article)
                    <div class="p-4 hover:bg-slate-50 transition-colors flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            @if($article->featured_image)
                                <img src="{{ $article->featured_image }}" alt="" class="w-14 h-14 rounded-xl object-cover shrink-0">
                            @else
                                <div class="w-14 h-14 rounded-xl bg-slate-100 shrink-0 flex items-center justify-center text-slate-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif

                            <div class="min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold text-white" style="background-color: {{ $article->category->color }}">
                                        {{ $article->category->hindi_name ?: $article->category->name }}
                                    </span>
                                    @if($article->is_breaking)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-rose-600 text-white">
                                            ब्रेकिंग
                                        </span>
                                    @endif
                                    <span class="text-[11px] text-slate-400">{{ $article->published_at ? $article->published_at->diffForHumans() : 'ड्राफ्ट' }}</span>
                                </div>
                                <h4 class="font-bold text-slate-900 text-sm truncate hover:text-brand-primary">
                                    <a href="{{ route('admin.articles.edit', $article->id) }}">{{ $article->title }}</a>
                                </h4>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-xs text-slate-500 font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                {{ number_format($article->views_count) }}
                            </span>
                            <a href="{{ route('admin.articles.edit', $article->id) }}" class="p-1.5 text-slate-400 hover:text-slate-800 rounded-lg hover:bg-slate-100" title="एडिट करें">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-sm">अभी कोई खबर नहीं है।</div>
                @endforelse
            </div>
        </div>

        <!-- Col 3: Most Read Stories (Trending) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span>{{ Auth::user()->isReporter() ? 'मेरी टॉप पढ़ी जाने वाली ख़बरें' : 'टॉप ट्रेंडिंग ख़बरें (Top Read)' }}</span>
                </h3>
            </div>

            <div class="p-4 space-y-4">
                @forelse($topArticles as $idx => $top)
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-700 font-black text-xs flex items-center justify-center shrink-0">
                            #{{ $idx + 1 }}
                        </span>
                        <div class="min-w-0">
                            <h5 class="text-xs font-bold text-slate-900 line-clamp-2 hover:text-brand-primary">
                                <a href="{{ route('portal.article', $top->slug) }}" target="_blank">{{ $top->title }}</a>
                            </h5>
                            <span class="text-[11px] text-slate-400 mt-1 block">{{ number_format($top->views_count) }} रीडर्स</span>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-slate-400 text-xs">अभी कोई आंकड़े उपलब्ध नहीं हैं।</div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
