@extends('layouts.admin')

@section('title', Auth::user()->isReporter() ? 'मेरी ख़बरें' : 'सभी ख़बरें')
@section('page_title', Auth::user()->isReporter() ? 'मेरी ख़बरें (My News Articles)' : 'खबरें प्रबंधन (News Articles Management)')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.articles.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="शीर्षक से खोजें..."
                   class="bg-slate-50 text-sm rounded-xl px-4 py-2 border border-slate-200 focus:border-brand-primary outline-none">

            <select name="category_id" class="bg-slate-50 text-sm rounded-xl px-4 py-2 border border-slate-200 focus:border-brand-primary outline-none">
                <option value="">सभी श्रेणियां</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->hindi_name ?: $cat->name }}
                    </option>
                @endforeach
            </select>

            <select name="state_id" class="bg-slate-50 text-sm rounded-xl px-4 py-2 border border-slate-200 focus:border-brand-primary outline-none">
                <option value="">सभी राज्य (All States)</option>
                @foreach($states as $st)
                    <option value="{{ $st->id }}" {{ request('state_id') == $st->id ? 'selected' : '' }}>
                        {{ $st->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="bg-slate-50 text-sm rounded-xl px-4 py-2 border border-slate-200 focus:border-brand-primary outline-none">
                <option value="">सभी स्टेटस</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>प्रकाशित (Published)</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>ड्राफ्ट (Draft)</option>
                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>आर्काइव</option>
            </select>

            <button type="submit" class="bg-slate-900 text-white font-bold text-xs px-4 py-2 rounded-xl hover:bg-slate-800">
                फ़िल्टर लगाएं
            </button>
            @if(request()->anyFilled(['q', 'category_id', 'status']))
                <a href="{{ route('admin.articles.index') }}" class="text-xs text-rose-600 hover:underline">रीसेट</a>
            @endif
        </form>

        <a href="{{ route('admin.articles.create') }}" class="bg-brand-primary text-white font-bold text-xs px-4 py-2.5 rounded-xl hover:brightness-110 shadow-sm flex items-center gap-2 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>नई ख़बर लिखें</span>
        </a>
    </div>

    <!-- Articles Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-4 px-6">ख़बर (Story)</th>
                        <th class="py-4 px-4">श्रेणी (Category)</th>
                        <th class="py-4 px-4">लेखक (Author)</th>
                        <th class="py-4 px-4 text-center">रीडर्स व्यूज</th>
                        <th class="py-4 px-4 text-center">स्टेटस</th>
                        <th class="py-4 px-6 text-right">एक्शन</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($articles as $art)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    @if($art->featured_image)
                                        <img src="{{ $art->featured_image }}" alt="" class="w-12 h-12 rounded-lg object-cover shrink-0">
                                    @endif
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 mb-0.5">
                                            @if($art->is_breaking)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-rose-600 text-white">
                                                    ब्रेकिंग
                                                </span>
                                            @endif
                                            @if($art->is_featured)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-amber-500 text-white">
                                                    फीचर्ड
                                                </span>
                                            @endif
                                            @if($art->hasLocation())
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 flex items-center gap-1">
                                                    📍 {{ $art->location_badge }}
                                                </span>
                                            @endif
                                            <span class="text-[11px] text-slate-400">
                                                {{ $art->published_at ? $art->published_at->format('d M, Y') : 'अप्रकाशित' }}
                                            </span>
                                        </div>
                                        <a href="{{ route('admin.articles.edit', $art->id) }}" class="font-bold text-slate-900 hover:text-brand-primary line-clamp-1">
                                            {{ $art->title }}
                                        </a>
                                    </div>
                                </div>
                            </td>

                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold text-white whitespace-nowrap" style="background-color: {{ $art->category->color }}">
                                    {{ $art->category->hindi_name ?: $art->category->name }}
                                </span>
                            </td>

                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="text-xs font-semibold text-slate-700">{{ $art->author->name }}</span>
                            </td>

                            <td class="py-4 px-4 text-center whitespace-nowrap font-mono text-xs font-bold text-slate-600">
                                {{ number_format($art->views_count) }}
                            </td>

                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                @if($art->status === 'published')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">प्रकाशित</span>
                                @elseif($art->status === 'draft')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">ड्राफ्ट</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">आर्काइव</span>
                                @endif
                            </td>

                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Automatic Graphic Card Maker Button -->
                                    <button type="button" 
                                            class="open-news-poster-btn p-1.5 text-purple-600 hover:text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors flex items-center gap-1 text-xs font-bold cursor-pointer" 
                                            title="ऑटोमैटिक सोशल मीडिया पोस्टर बनाएं"
                                            data-title="{{ $art->title }}"
                                            data-category="{{ $art->category->hindi_name ?: $art->category->name }}"
                                            data-category-color="{{ $art->category->color }}"
                                            data-location="{{ $art->location_badge ?? '' }}"
                                            data-author="{{ $art->author->name }}"
                                            data-author-avatar="{{ $art->author->avatar ?: '' }}"
                                            data-image="{{ $art->featured_image ? asset($art->featured_image) : '' }}"
                                            data-date="{{ $art->published_at ? $art->published_at->format('d M, Y') : now()->format('d M, Y') }}"
                                            data-url="{{ route('portal.article', $art->slug) }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="hidden md:inline">पोस्टर</span>
                                    </button>

                                    <a href="{{ route('portal.article', $art->slug) }}" target="_blank" class="p-1.5 text-slate-400 hover:text-blue-600 rounded-lg hover:bg-slate-100" title="पोर्टल पर देखें">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                    <a href="{{ route('admin.articles.edit', $art->id) }}" class="p-1.5 text-slate-400 hover:text-brand-primary rounded-lg hover:bg-slate-100" title="एडिट करें">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.articles.destroy', $art->id) }}" method="POST" onsubmit="return confirm('क्या आप इस खबर को वाकई हटाना चाहते हैं?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-slate-100" title="हटाएं">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">कोई खबर नहीं मिली।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $articles->links() }}
        </div>
    </div>

</div>
@endsection
