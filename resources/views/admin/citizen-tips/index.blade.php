@extends('layouts.admin')

@section('title', 'जनता से न्यूज़ टिप्स व सिटीज़न जर्नलिज्म')
@section('page_title', 'जनता की न्यूज़ टिप्स (Citizen News Tips Desk)')

@section('content')
<div class="space-y-6">

    <!-- Top Metrics Overview -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        <a href="{{ route('admin.citizen-tips.index') }}" 
           class="p-4 rounded-2xl border transition-all {{ !request('status') ? 'bg-brand-primary text-white border-brand-primary shadow-md' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300' }}">
            <span class="block text-xs font-bold uppercase tracking-wider {{ !request('status') ? 'text-white/80' : 'text-slate-400' }}">कुल टिप्स</span>
            <span class="text-2xl font-black">{{ number_format($statusCounts['all']) }}</span>
        </a>

        <a href="{{ route('admin.citizen-tips.index', ['status' => 'pending']) }}" 
           class="p-4 rounded-2xl border transition-all {{ request('status') === 'pending' ? 'bg-amber-500 text-white border-amber-500 shadow-md' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300' }}">
            <span class="block text-xs font-bold uppercase tracking-wider {{ request('status') === 'pending' ? 'text-white/80' : 'text-slate-400' }}">प्रतीक्षारत (Pending)</span>
            <span class="text-2xl font-black text-amber-500 {{ request('status') === 'pending' ? '!text-white' : '' }}">{{ number_format($statusCounts['pending']) }}</span>
        </a>

        <a href="{{ route('admin.citizen-tips.index', ['status' => 'verified']) }}" 
           class="p-4 rounded-2xl border transition-all {{ request('status') === 'verified' ? 'bg-blue-600 text-white border-blue-600 shadow-md' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300' }}">
            <span class="block text-xs font-bold uppercase tracking-wider {{ request('status') === 'verified' ? 'text-white/80' : 'text-slate-400' }}">सत्यापित (Verified)</span>
            <span class="text-2xl font-black text-blue-600 {{ request('status') === 'verified' ? '!text-white' : '' }}">{{ number_format($statusCounts['verified']) }}</span>
        </a>

        <a href="{{ route('admin.citizen-tips.index', ['status' => 'converted_to_article']) }}" 
           class="p-4 rounded-2xl border transition-all {{ request('status') === 'converted_to_article' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300' }}">
            <span class="block text-xs font-bold uppercase tracking-wider {{ request('status') === 'converted_to_article' ? 'text-white/80' : 'text-slate-400' }}">ख़बर बनी (Published)</span>
            <span class="text-2xl font-black text-emerald-600 {{ request('status') === 'converted_to_article' ? '!text-white' : '' }}">{{ number_format($statusCounts['converted_to_article']) }}</span>
        </a>

        <a href="{{ route('admin.citizen-tips.index', ['status' => 'rejected']) }}" 
           class="p-4 rounded-2xl border transition-all {{ request('status') === 'rejected' ? 'bg-rose-600 text-white border-rose-600 shadow-md' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300' }}">
            <span class="block text-xs font-bold uppercase tracking-wider {{ request('status') === 'rejected' ? 'text-white/80' : 'text-slate-400' }}">अस्वीकृत (Rejected)</span>
            <span class="text-2xl font-black text-rose-600 {{ request('status') === 'rejected' ? '!text-white' : '' }}">{{ number_format($statusCounts['rejected']) }}</span>
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs flex flex-wrap items-center justify-between gap-4">
        <form action="{{ route('admin.citizen-tips.index') }}" method="GET" class="flex items-center gap-3 flex-1 max-w-lg">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative flex-1">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="शीर्षक, स्थान या नागरिक नाम से खोजें..." 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-800 outline-none focus:border-brand-primary">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all">
                खोजें
            </button>
            @if(request('q') || request('status'))
                <a href="{{ route('admin.citizen-tips.index') }}" class="text-xs text-rose-600 hover:underline font-semibold">रीसेट</a>
            @endif
        </form>

        <a href="{{ route('portal.citizen-tip') }}" target="_blank" class="text-xs font-bold text-brand-primary hover:underline flex items-center gap-1">
            <span>पब्लिक टिप फॉर्म देखें ↗</span>
        </a>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 font-black uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4">घटना / शीर्षक (Title & Excerpt)</th>
                        <th class="py-3.5 px-4">स्थान (Location)</th>
                        <th class="py-3.5 px-4">नागरिक / चश्मदीद</th>
                        <th class="py-3.5 px-4 text-center">सबूत (Media)</th>
                        <th class="py-3.5 px-4 text-center">स्थिति (Status)</th>
                        <th class="py-3.5 px-4">तारीख</th>
                        <th class="py-3.5 px-6 text-right">कार्रवाई (Actions)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tips as $tip)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            
                            <!-- Title & Excerpt -->
                            <td class="py-4 px-4 max-w-sm">
                                <a href="{{ route('admin.citizen-tips.show', $tip->id) }}" class="font-bold text-slate-900 hover:text-brand-primary block line-clamp-1 text-sm">
                                    {{ $tip->title }}
                                </a>
                                <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $tip->description }}</p>
                            </td>

                            <!-- Location -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($tip->city)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        📍 {{ $tip->city->name }}
                                    </span>
                                @elseif($tip->state)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                                        📍 {{ $tip->state->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[11px]">—</span>
                                @endif
                                @if($tip->location_name)
                                    <span class="block text-[10px] text-slate-400 truncate max-w-[140px]">{{ $tip->location_name }}</span>
                                @endif
                            </td>

                            <!-- Witness -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($tip->is_anonymous)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-black bg-purple-100 text-purple-800">
                                        🔒 गोपनीय नागरिक
                                    </span>
                                @else
                                    <span class="font-bold text-slate-800 block">{{ $tip->witness_name }}</span>
                                    @if($tip->witness_phone)
                                        <span class="text-[10px] font-mono text-slate-400 block">{{ $tip->witness_phone }}</span>
                                    @endif
                                @endif
                            </td>

                            <!-- Media -->
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                @if($tip->media_path)
                                    @if($tip->isImage())
                                        <a href="{{ $tip->media_url }}" target="_blank" class="inline-block p-1 bg-slate-100 rounded-lg hover:ring-2 hover:ring-brand-primary">
                                            <img src="{{ $tip->media_url }}" alt="Proof" class="w-8 h-8 rounded object-cover">
                                        </a>
                                    @elseif($tip->isVideo())
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-100 text-blue-800">
                                            🎥 वीडियो
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-800">
                                            📄 दस्तावेज़
                                        </span>
                                    @endif
                                @else
                                    <span class="text-slate-400 text-[11px]">कोई फ़ाइल नहीं</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase border {{ $tip->status_badge_class }}">
                                    {{ $tip->status_label }}
                                </span>
                            </td>

                            <!-- Date -->
                            <td class="py-4 px-4 whitespace-nowrap text-slate-400 text-[11px]">
                                {{ $tip->created_at->format('d M, Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    <!-- View Details -->
                                    <a href="{{ route('admin.citizen-tips.show', $tip->id) }}" class="p-1.5 text-slate-400 hover:text-blue-600 rounded-lg hover:bg-slate-100" title="विवरण देखें">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>

                                    <!-- Convert to News Article Action -->
                                    @if($tip->status !== 'converted_to_article')
                                        <form action="{{ route('admin.citizen-tips.convert', $tip->id) }}" method="POST" class="inline" onsubmit="return confirm('क्या आप इस नागरिक टिप को सीधे समाचार आर्टिकल (ड्राफ्ट) में बदलना चाहते हैं?');">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg text-xs font-bold transition-all flex items-center gap-1 shadow-2xs" title="1-क्लिक में न्यूज़ ड्राफ्ट बनाएं">
                                                <span>⚡ खबर बनाएं</span>
                                            </button>
                                        </form>
                                    @else
                                        @if($tip->convertedArticle)
                                            <a href="{{ route('admin.articles.edit', $tip->convertedArticle->id) }}" class="px-2.5 py-1 text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg text-xs font-bold transition-all flex items-center gap-1">
                                                <span>आर्टिकल देखें ↗</span>
                                            </a>
                                        @endif
                                    @endif

                                    <!-- Delete -->
                                    <form action="{{ route('admin.citizen-tips.destroy', $tip->id) }}" method="POST" class="inline" onsubmit="return confirm('क्या आप इस टिप को हटाना चाहते हैं?');">
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
                            <td colspan="7" class="p-10 text-center text-slate-400">कोई नागरिक न्यूज़ टिप उपलब्ध नहीं है।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $tips->links() }}
        </div>
    </div>

</div>
@endsection
