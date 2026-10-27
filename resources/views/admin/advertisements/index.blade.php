@extends('layouts.admin')

@section('title', 'विज्ञापन प्रबंधन (Advertisement Manager)')
@section('page_title', 'विज्ञापन प्रबंधन (Ads & Monetization Control)')

@section('content')
<div class="space-y-6">

    <!-- 0. Advertisement Monetization & Performance Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">कुल विज्ञापन (Total Units)</span>
            <span class="text-2xl sm:text-3xl font-black text-slate-900 mt-1 block">{{ $ads->total() }}</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">कुल इम्प्रेशन्स (Impressions)</span>
            <span class="text-2xl sm:text-3xl font-black text-blue-600 mt-1 block">{{ number_format($totalViews) }}</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">कुल क्लिक्स (Total Clicks)</span>
            <span class="text-2xl sm:text-3xl font-black text-emerald-600 mt-1 block">{{ number_format($totalClicks) }}</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">औसत CTR (Click Rate)</span>
            <span class="text-2xl sm:text-3xl font-black text-purple-600 mt-1 block">{{ $overallCtr }}%</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

    <!-- 1. Add New Advertisement Form (5 cols) -->
    <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs h-fit space-y-6">
        <div class="pb-3 border-b border-slate-100">
            <h3 class="font-bold text-slate-900 text-base">नया विज्ञापन जोड़ें (Create Ad)</h3>
            <p class="text-xs text-slate-500">न्यूज़ डिटेल पेज के राइट साइड या हेडर में कस्टम इमेज या गूगल ऐडसेंस कोड लगाएं।</p>
        </div>

        <form action="{{ route('admin.advertisements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Optional Title -->
            <div>
                <label for="ad_title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    विज्ञापन का नाम / शीर्षक <span class="text-slate-400 font-normal lowercase">(Optional)</span>
                </label>
                <input type="text" id="ad_title" name="title" placeholder="उदा. दिवाली सेल, टेक स्पॉन्सर, गूगल साइडबार..."
                       class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-3.5 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
            </div>

            <!-- Ad Type Selector: Image vs Code -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">विज्ञापन का प्रकार (Ad Type) *</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 bg-slate-50 cursor-pointer hover:bg-slate-100">
                        <input type="radio" name="type" value="image" checked onchange="toggleAdType('image')" class="text-brand-primary focus:ring-0">
                        <span class="text-xs font-bold text-slate-800">इमेज बैनर (Upload)</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 bg-slate-50 cursor-pointer hover:bg-slate-100">
                        <input type="radio" name="type" value="code" onchange="toggleAdType('code')" class="text-brand-primary focus:ring-0">
                        <span class="text-xs font-bold text-slate-800">Google AdSense / HTML</span>
                    </label>
                </div>
            </div>

            <!-- Image Upload & URL Fields -->
            <div id="image_fields" class="space-y-3 p-4 rounded-xl bg-slate-50 border border-slate-200">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">इमेज फ़ाइल अपलोड करें:</label>
                    <input type="file" name="image_file" accept="image/*" class="text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-brand-primary cursor-pointer w-full">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">या इमेज URL:</label>
                    <input type="url" name="image_url" placeholder="https://images.unsplash.com/..." class="w-full bg-white text-xs rounded-lg px-3 py-2 border border-slate-200 focus:border-brand-primary outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">क्लिक करने पर खुलने वाला लिंक (Destination URL):</label>
                    <input type="url" name="destination_url" placeholder="https://yourstore.com/deal" class="w-full bg-white text-xs rounded-lg px-3 py-2 border border-slate-200 focus:border-brand-primary outline-none">
                </div>
            </div>

            <!-- Google AdSense / HTML Code Textarea -->
            <div id="code_fields" class="hidden space-y-2 p-4 rounded-xl bg-slate-900 text-white border border-slate-800">
                <label class="block text-xs font-bold text-amber-400 uppercase tracking-wider mb-1">
                    Google AdSense स्क्रिप्ट / कस्टम HTML कोड *
                </label>
                <textarea name="html_code" rows="5" placeholder="<script async src='https://pagead2.googlesyndication.com/...'>
</script>
<!-- Responsive Ad Unit -->
<ins class='adsbygoogle' ...></ins>" 
                          class="w-full bg-slate-950 font-mono text-xs rounded-lg p-3 border border-slate-700 focus:border-amber-400 outline-none text-emerald-400"></textarea>
                <span class="block text-[11px] text-slate-400">गूगल ऐडसेंस या किसी भी अन्य एड नेटवर्क का कोड पेस्ट करें।</span>
            </div>

            <!-- Placement & Order -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">स्थान (Placement) *</label>
                    <select name="placement" class="w-full bg-slate-50 text-xs font-bold rounded-xl px-3 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                        <option value="sidebar" selected>राइट साइडबार (होमपेज व न्यूज़ आर्टिकल दोनों में)</option>
                        <option value="header">होमपेज टॉप लीडरबोर्ड (Top Header Leaderboard)</option>
                        <option value="in_feed">होमपेज इन-फ़ीड स्ट्रिप (ट्रेंडिंग के तुरंत बाद)</option>
                        <option value="content_bottom">कंटेंट के नीचे (Footer Banner)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">क्रम संख्या (Sort Order)</label>
                    <input type="number" name="order" value="0" class="w-full bg-slate-50 text-xs font-bold rounded-xl px-3 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2 cursor-pointer pt-2">
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-brand-primary rounded border-slate-300 focus:ring-0">
                    <span class="text-xs font-bold text-slate-800">विज्ञापन तुरंत चालू (Active) रखें</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-brand-primary text-white font-extrabold text-xs py-3 rounded-xl hover:brightness-110 shadow-md flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>विज्ञापन सहेजें व प्रकाशित करें</span>
            </button>
        </form>
    </div>

    <!-- 2. Existing Advertisements List (7 cols) -->
    <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-sm">सक्रिय व कुल विज्ञापन (Active & Total Ads)</h3>
                <p class="text-xs text-slate-400">राइट साइड पर एक साथ कई विज्ञापन क्रमबद्ध दिखाए जाते हैं।</p>
            </div>
            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800">
                {{ $ads->total() }} विज्ञापन
            </span>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($ads as $ad)
                <div class="p-5 flex flex-col sm:flex-row items-start justify-between gap-4 hover:bg-slate-50/80 transition-colors">
                    
                    <div class="flex items-start gap-4 min-w-0">
                        <!-- Preview Thumbnail / Code Indicator -->
                        <div class="w-24 h-24 rounded-xl overflow-hidden shrink-0 border border-slate-200 bg-slate-100 flex items-center justify-center">
                            @if($ad->type === 'image' && $ad->image_url)
                                <img src="{{ $ad->image_url }}" alt="" class="w-full h-full object-cover">
                            @else
                                <div class="p-2 text-center text-slate-500 font-mono text-[10px]">
                                    <svg class="w-6 h-6 mx-auto mb-1 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                    <span>AdSense</span>
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="space-y-1.5 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold text-white uppercase {{ $ad->type === 'image' ? 'bg-blue-600' : 'bg-amber-600' }}">
                                    {{ $ad->type === 'image' ? 'इमेज बैनर' : 'गूगल कोड' }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                    @if($ad->placement === 'sidebar') 📌 राइट साइडबार
                                    @elseif($ad->placement === 'header') ⭐ टॉप लीडरबोर्ड
                                    @elseif($ad->placement === 'in_feed') 📰 इन-फ़ीड स्ट्रिप
                                    @else {{ $ad->placement }}
                                    @endif
                                </span>
                                @if($ad->is_active)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">लाइव</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">बंद</span>
                                @endif
                            </div>

                            <h4 class="font-bold text-slate-900 text-sm truncate">
                                {{ $ad->title ?: 'शीर्षक रहित विज्ञापन #' . $ad->id }}
                            </h4>

                            @if($ad->destination_url)
                                <a href="{{ $ad->destination_url }}" target="_blank" class="block text-xs text-blue-600 hover:underline truncate">
                                    🔗 {{ $ad->destination_url }}
                                </a>
                            @endif

                            <div class="flex flex-wrap items-center gap-2.5 text-[11px] text-slate-500 font-medium pt-1">
                                <span>ऑर्डर: <strong>#{{ $ad->order }}</strong></span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    इम्प्रेशन्स: <strong class="text-slate-800">{{ number_format($ad->views_count) }}</strong>
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                                    क्लिक्स: <strong class="text-emerald-700 font-bold">{{ number_format($ad->clicks_count) }}</strong>
                                </span>
                                <span>•</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $ad->ctr > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $ad->ctr }}% CTR
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 shrink-0">
                        <form action="{{ route('admin.advertisements.toggle', $ad->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors {{ $ad->is_active ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                {{ $ad->is_active ? 'रोकें (Pause)' : 'चालू करें (Resume)' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.advertisements.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('क्या आप इस विज्ञापन को वाकई हटाना चाहते हैं?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-slate-100" title="हटाएं">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>

                </div>
            @empty
                <div class="p-12 text-center text-slate-400 text-sm space-y-2">
                    <p class="font-bold">अभी कोई विज्ञापन नहीं लगाया गया है।</p>
                    <p class="text-xs">बाएँ दिए गए फ़ॉर्म से इमेज बैनर या गूगल ऐडसेंस कोड जोड़ें।</p>
                </div>
            @endforelse
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $ads->links() }}
        </div>
    </div>

    </div>
</div>

<script>
    function toggleAdType(type) {
        const imageFields = document.getElementById('image_fields');
        const codeFields = document.getElementById('code_fields');

        if (type === 'image') {
            imageFields.classList.remove('hidden');
            codeFields.classList.add('hidden');
        } else {
            imageFields.classList.add('hidden');
            codeFields.classList.remove('hidden');
        }
    }
</script>
@endsection
