@extends('layouts.admin')

@section('title', 'थीम कलर स्टूडियो व लाइव प्रीव्यू')
@section('page_title', 'थीम कलर स्टूडियो व लाइव प्रीव्यू (Theme & Styling Studio)')

@section('content')
<div class="space-y-6 pb-12">

    <!-- Top Announcement Bar -->
    <div class="p-5 rounded-2xl brand-gradient text-white shadow-lg flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-white/20 backdrop-blur-md">
                    Live Portal Styler
                </span>
                <span id="sync-status-badge" class="px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-400/20 text-emerald-200 border border-emerald-400/30 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 live-pulse"></span>
                    <span id="sync-status-text">लाइव पब्लिश है (In Sync)</span>
                </span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black tracking-tight">न्यूज़ पोर्टल लाइव थीम व कलर कस्टमाइज़र</h2>
            <p class="text-xs text-rose-100 mt-1 max-w-3xl leading-relaxed">
                यहाँ से आप पूरे न्यूज़ पोर्टल का रंग लाइव बदलकर देख सकते हैं। दाहिनी तरफ पोर्टल का रियल-टाइम प्रीव्यू दिखेगा। जब आपको नया रंग पसंद आ जाए, तो <strong class="text-white underline">"थीम लाइव पब्लिश करें"</strong> पर क्लिक करके इसे सभी पाठकों के लिए तुरंत लागू करें!
            </p>
        </div>

        <!-- Sticky Quick Actions in Header -->
        <div class="flex items-center gap-3 shrink-0">
            <button type="button" id="btn-reset-theme" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition-all flex items-center gap-2 border border-white/15">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>रीसेट करें</span>
            </button>

            <button type="button" id="btn-publish-theme-top" class="px-6 py-2.5 rounded-xl bg-white text-slate-900 hover:bg-rose-50 font-black text-xs transition-all shadow-lg flex items-center gap-2 active:scale-95">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>थीम लाइव पब्लिश करें</span>
            </button>
        </div>
    </div>

    <!-- Main Studio Split Layout -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">

        <!-- =============================================================== -->
        <!-- LEFT PANEL: Theme Color Controls & Palettes (4 cols)           -->
        <!-- =============================================================== -->
        <div class="xl:col-span-4 space-y-6">

            <!-- 1. Color Customizer Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <span class="p-2 rounded-xl bg-rose-50 text-brand-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4 4.5 4.5 0 011.5-3.322l11-11a2.121 2.121 0 013 3L6.828 17.172A4.5 4.5 0 013 21z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7l2 2"/></svg>
                        </span>
                        <div>
                            <h3 class="font-black text-slate-900 text-sm">कस्टम कलर पिकर (Color Pickers)</h3>
                            <p class="text-[11px] text-slate-500 font-medium">रंग बदलते ही प्रीव्यू तुरंत अपडेट होगा</p>
                        </div>
                    </div>
                </div>

                <!-- Primary Brand Color -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="picker-primary" class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full" id="indicator-primary" style="background-color: {{ $currentPrimary }};"></span>
                            <span>प्राइमरी ब्रांड कलर (Primary)</span>
                        </label>
                        <span class="text-[10px] font-semibold text-slate-400">हेडर्स, बटन्स, टिकर</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="relative shrink-0">
                            <input type="color" id="picker-primary" value="{{ $currentPrimary }}" 
                                   class="w-12 h-12 rounded-2xl border-2 border-slate-200 p-0.5 cursor-pointer shadow-xs bg-slate-50 transition-all hover:scale-105">
                        </div>
                        <div class="flex-1 relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-mono text-slate-400 text-xs font-bold">HEX</span>
                            <input type="text" id="hex-primary" value="{{ $currentPrimary }}" maxlength="7"
                                   class="w-full pl-12 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-mono text-sm font-bold tracking-wider focus:bg-white focus:border-brand-primary outline-none transition-all uppercase">
                        </div>
                    </div>
                </div>

                <!-- Accent Highlight Color -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="picker-accent" class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full" id="indicator-accent" style="background-color: {{ $currentAccent }};"></span>
                            <span>एक्सेंट / हाइलाइट कलर (Accent)</span>
                        </label>
                        <span class="text-[10px] font-semibold text-slate-400">बैज, सब-हेडर्स, स्टार्स</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="relative shrink-0">
                            <input type="color" id="picker-accent" value="{{ $currentAccent }}" 
                                   class="w-12 h-12 rounded-2xl border-2 border-slate-200 p-0.5 cursor-pointer shadow-xs bg-slate-50 transition-all hover:scale-105">
                        </div>
                        <div class="flex-1 relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-mono text-slate-400 text-xs font-bold">HEX</span>
                            <input type="text" id="hex-accent" value="{{ $currentAccent }}" maxlength="7"
                                   class="w-full pl-12 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-mono text-sm font-bold tracking-wider focus:bg-white focus:border-brand-primary outline-none transition-all uppercase">
                        </div>
                    </div>
                </div>

                <!-- Dual Swatch Preview Bar -->
                <div class="p-3.5 rounded-2xl bg-slate-900 text-white flex items-center justify-between shadow-inner">
                    <span class="text-[11px] font-bold text-slate-300">करंट ड्राफ्ट पैलेट:</span>
                    <div class="flex items-center gap-2">
                        <span id="draft-pill-primary" class="px-2.5 py-1 rounded-lg text-xs font-black shadow-sm text-white" style="background-color: {{ $currentPrimary }};">
                            Primary
                        </span>
                        <span id="draft-pill-accent" class="px-2.5 py-1 rounded-lg text-xs font-black shadow-sm text-slate-900" style="background-color: {{ $currentAccent }};">
                            Accent
                        </span>
                    </div>
                </div>
            </div>

            <!-- 2. One-Click Curated Pro Presets -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-black text-slate-900 text-sm flex items-center gap-2">
                        <span class="text-amber-500">✨</span>
                        <span>रेडीमेड प्रो पैलेट्स (One-Click Presets)</span>
                    </h3>
                    <span class="text-[10px] text-slate-400 font-bold uppercase">८ न्यूज़ थीम्स</span>
                </div>

                <div class="grid grid-cols-1 gap-2.5" id="presets-container">
                    @foreach($presets as $p)
                        <button type="button" 
                                data-primary="{{ $p['primary'] }}" 
                                data-accent="{{ $p['accent'] }}"
                                class="preset-btn group text-left p-3 rounded-2xl border border-slate-200 hover:border-slate-400 hover:bg-slate-50 transition-all flex items-center justify-between gap-3 {{ $p['primary'] === $currentPrimary && $p['accent'] === $currentAccent ? 'ring-2 ring-brand-primary border-transparent bg-rose-50/40' : '' }}">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center -space-x-1.5 shrink-0">
                                    <span class="w-6 h-6 rounded-full ring-2 ring-white shadow-xs" style="background-color: {{ $p['primary'] }}"></span>
                                    <span class="w-6 h-6 rounded-full ring-2 ring-white shadow-xs" style="background-color: {{ $p['accent'] }}"></span>
                                </div>
                                <div>
                                    <span class="block font-black text-xs text-slate-800 group-hover:text-slate-950">{{ $p['name'] }}</span>
                                    <span class="block text-[10px] text-slate-400 font-medium">{{ $p['tagline'] }}</span>
                                </div>
                            </div>
                            <span class="text-[10px] font-mono text-slate-400 group-hover:text-slate-700 font-semibold">{{ $p['primary'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- 3. Preview Controls (Page & Mode Switcher) -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-4">
                <h3 class="font-black text-slate-900 text-sm pb-3 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>प्रीव्यू पेज व मोड्स (Preview Target)</span>
                </h3>

                <!-- Pages Switcher -->
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">प्रीव्यू पेज बदलें:</label>
                    <div class="grid grid-cols-3 gap-2 text-xs font-bold">
                        <button type="button" data-url="{{ route('portal.index') }}" class="page-switch-btn active py-2 px-2.5 rounded-xl border border-slate-200 bg-slate-900 text-white transition-all text-center">
                            🏠 होमपेज
                        </button>
                        @if($sampleArticle)
                            <button type="button" data-url="{{ route('portal.article', $sampleArticle->slug) }}" class="page-switch-btn py-2 px-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 transition-all text-center truncate">
                                📰 आर्टिकल
                            </button>
                        @endif
                        @if($sampleCategory)
                            <button type="button" data-url="{{ route('portal.category', $sampleCategory->slug) }}" class="page-switch-btn py-2 px-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 transition-all text-center truncate">
                                📂 कैटेगरी
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Dark Mode & RTL Switches -->
                <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700">डार्क मोड में प्रीव्यू:</span>
                    <button type="button" id="btn-toggle-dark-preview" class="px-3 py-1.5 rounded-xl text-xs font-bold border border-slate-200 bg-slate-100 hover:bg-slate-200 text-slate-800 transition-all flex items-center gap-1.5">
                        <span id="dark-mode-icon">🌙</span>
                        <span id="dark-mode-text">Dark Mode Off</span>
                    </button>
                </div>
            </div>

            <!-- Big Bottom Publish Button -->
            <button type="button" id="btn-publish-theme-bottom" class="w-full py-4 rounded-2xl bg-brand-primary hover:brightness-110 text-white font-black text-sm tracking-wide shadow-xl transition-all flex items-center justify-center gap-2 active:scale-98">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>थीम लाइव पोर्टल पर पब्लिश करें</span>
            </button>

        </div>

        <!-- =============================================================== -->
        <!-- RIGHT PANEL: Live Interactive News Portal Iframe (8 cols)     -->
        <!-- =============================================================== -->
        <div class="xl:col-span-8 space-y-4">

            <!-- Device Toolbar & Status Bar -->
            <div class="bg-white rounded-2xl p-3.5 border border-slate-200 shadow-sm flex flex-wrap items-center justify-between gap-3">
                <!-- Device Mode Buttons -->
                <div class="flex items-center gap-1.5 bg-slate-100 p-1 rounded-xl">
                    <button type="button" data-device="desktop" class="device-btn active px-3 py-1.5 rounded-lg text-xs font-bold bg-white text-slate-900 shadow-xs transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>डेस्कटॉप</span>
                    </button>
                    <button type="button" data-device="tablet" class="device-btn px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <span>टैबलेट (768px)</span>
                    </button>
                    <button type="button" data-device="mobile" class="device-btn px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <span>मोबाइल (375px)</span>
                    </button>
                </div>

                <!-- Preview Actions -->
                <div class="flex items-center gap-2">
                    <button type="button" id="btn-reload-preview" title="रिफ्रेश प्रीव्यू" class="p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>
                    <a id="btn-open-live-window" href="{{ route('portal.index') }}" target="_blank" title="नई विंडो में लाइव साइट खोलें" class="p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-all flex items-center gap-1.5 text-xs font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>लाइव साइट</span>
                    </a>
                </div>
            </div>

            <!-- The Iframe Simulated Browser Frame -->
            <div id="preview-viewport-container" class="transition-all duration-300 mx-auto w-full">
                <div class="bg-slate-900 rounded-3xl overflow-hidden shadow-2xl border border-slate-800 flex flex-col">
                    
                    <!-- Simulated Browser Top Bar -->
                    <div class="h-10 px-4 bg-slate-950 flex items-center justify-between border-b border-slate-800 shrink-0">
                        <!-- Window Dots -->
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                        </div>

                        <!-- Simulated Address Bar -->
                        <div class="flex-1 max-w-md mx-4">
                            <div class="h-6 px-3 rounded-md bg-slate-900 border border-slate-800 text-[11px] font-mono text-slate-400 flex items-center justify-between truncate">
                                <span class="flex items-center gap-1.5 truncate">
                                    <svg class="w-3 h-3 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <span id="preview-url-display">{{ url('/') }}</span>
                                </span>
                                <span class="text-[9px] uppercase tracking-wider text-slate-500 font-bold shrink-0">PREVIEW</span>
                            </div>
                        </div>

                        <!-- Iframe Indicator -->
                        <div class="flex items-center gap-1.5 text-[11px] text-slate-400 font-semibold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 live-pulse"></span>
                            <span class="hidden sm:inline">Live Sync</span>
                        </div>
                    </div>

                    <!-- Iframe Wrapper -->
                    <div class="relative bg-slate-100 dark:bg-slate-950 w-full" style="height: 780px;">
                        <!-- Loading Overlay -->
                        <div id="iframe-loader" class="absolute inset-0 bg-white/80 dark:bg-slate-950/80 backdrop-blur-xs flex flex-col items-center justify-center gap-3 z-10 transition-opacity duration-200">
                            <div class="w-8 h-8 rounded-full border-3 border-slate-200 border-t-brand-primary animate-spin"></div>
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-400">पोर्टल प्रीव्यू लोड हो रहा है...</span>
                        </div>

                        <!-- Live News Portal Iframe -->
                        <iframe id="portal-preview-iframe" 
                                src="{{ route('portal.index') }}" 
                                class="w-full h-full border-0 transition-opacity duration-300" 
                                title="Live News Portal Preview"></iframe>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

<!-- Success Floating Toast Notification -->
<div id="publish-toast" class="fixed bottom-6 right-6 z-50 transform translate-y-24 opacity-0 transition-all duration-300 pointer-events-none">
    <div class="bg-slate-950 text-white px-5 py-4 rounded-2xl shadow-2xl border border-slate-800 flex items-center gap-4 max-w-md">
        <span class="p-2 rounded-xl bg-emerald-500/20 text-emerald-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </span>
        <div>
            <h4 class="font-black text-sm text-white">थीम सफलतापूर्वक लाइव पब्लिश हो गई!</h4>
            <p class="text-xs text-slate-300 mt-0.5 leading-relaxed">नए कलर्स तुरंत पूरे लाइव न्यूज़ पोर्टल पर सभी पाठकों के लिए लागू कर दिए गए हैं।</p>
        </div>
    </div>
</div>

<!-- Studio JavaScript Engine -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const publishedPrimary = @json($currentPrimary);
    const publishedAccent = @json($currentAccent);

    let activePrimary = publishedPrimary;
    let activeAccent = publishedAccent;
    let isDarkMode = false;

    const iframe = document.getElementById('portal-preview-iframe');
    const iframeLoader = document.getElementById('iframe-loader');
    const pickerPrimary = document.getElementById('picker-primary');
    const hexPrimary = document.getElementById('hex-primary');
    const pickerAccent = document.getElementById('picker-accent');
    const hexAccent = document.getElementById('hex-accent');
    const indicatorPrimary = document.getElementById('indicator-primary');
    const indicatorAccent = document.getElementById('indicator-accent');
    const draftPillPrimary = document.getElementById('draft-pill-primary');
    const draftPillAccent = document.getElementById('draft-pill-accent');
    const syncStatusText = document.getElementById('sync-status-text');
    const syncStatusBadge = document.getElementById('sync-status-badge');
    const previewUrlDisplay = document.getElementById('preview-url-display');
    const viewportContainer = document.getElementById('preview-viewport-container');
    const publishToast = document.getElementById('publish-toast');

    // 1. Send live CSS variable updates to Iframe (Zero-Lag Real-Time)
    function broadcastThemeUpdate(primary, accent) {
        activePrimary = primary;
        activeAccent = accent;

        // Visual controls update
        pickerPrimary.value = primary;
        hexPrimary.value = primary.toUpperCase();
        indicatorPrimary.style.backgroundColor = primary;
        draftPillPrimary.style.backgroundColor = primary;

        pickerAccent.value = accent;
        hexAccent.value = accent.toUpperCase();
        indicatorAccent.style.backgroundColor = accent;
        draftPillAccent.style.backgroundColor = accent;

        // Check if draft or published
        const isModified = (primary.toLowerCase() !== publishedPrimary.toLowerCase() || 
                            accent.toLowerCase() !== publishedAccent.toLowerCase());
        
        if (isModified) {
            syncStatusText.textContent = 'ड्राफ्ट मोड (Preview Only - Unsaved)';
            syncStatusBadge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-amber-400/20 text-amber-200 border border-amber-400/30 flex items-center gap-1.5';
        } else {
            syncStatusText.textContent = 'लाइव पब्लिश है (In Sync)';
            syncStatusBadge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-400/20 text-emerald-200 border border-emerald-400/30 flex items-center gap-1.5';
        }

        // PostMessage to Iframe
        try {
            if (iframe && iframe.contentWindow) {
                iframe.contentWindow.postMessage({
                    type: 'PORTAL_THEME_UPDATE',
                    primary: primary,
                    accent: accent
                }, '*');
            }
        } catch (e) {
            console.error('PostMessage error:', e);
        }

        // Direct DOM manipulation fallback (if same origin)
        try {
            const doc = iframe.contentDocument || iframe.contentWindow.document;
            if (doc && doc.documentElement) {
                doc.documentElement.style.setProperty('--brand-primary', primary);
                doc.documentElement.style.setProperty('--brand-accent', accent);
            }
        } catch (e) {}
    }

    // 2. Color picker listeners
    pickerPrimary.addEventListener('input', function (e) {
        broadcastThemeUpdate(e.target.value, activeAccent);
    });

    hexPrimary.addEventListener('input', function (e) {
        let val = e.target.value.trim();
        if (!val.startsWith('#')) val = '#' + val;
        if (/^#[0-9A-F]{6}$/i.test(val)) {
            broadcastThemeUpdate(val, activeAccent);
        }
    });

    pickerAccent.addEventListener('input', function (e) {
        broadcastThemeUpdate(activePrimary, e.target.value);
    });

    hexAccent.addEventListener('input', function (e) {
        let val = e.target.value.trim();
        if (!val.startsWith('#')) val = '#' + val;
        if (/^#[0-9A-F]{6}$/i.test(val)) {
            broadcastThemeUpdate(activePrimary, val);
        }
    });

    // 3. Preset Palette Clicks
    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const p = this.getAttribute('data-primary');
            const a = this.getAttribute('data-accent');
            broadcastThemeUpdate(p, a);

            document.querySelectorAll('.preset-btn').forEach(b => {
                b.classList.remove('ring-2', 'ring-brand-primary', 'border-transparent', 'bg-rose-50/40');
            });
            this.classList.add('ring-2', 'ring-brand-primary', 'border-transparent', 'bg-rose-50/40');
        });
    });

    // 4. Iframe loading and initialization
    iframe.addEventListener('load', function () {
        iframeLoader.classList.add('opacity-0', 'pointer-events-none');
        setTimeout(() => {
            iframeLoader.style.display = 'none';
        }, 200);

        // Apply active theme colors immediately upon page load
        broadcastThemeUpdate(activePrimary, activeAccent);

        // Apply dark mode state
        if (isDarkMode && iframe.contentWindow) {
            iframe.contentWindow.postMessage({ type: 'PORTAL_THEME_DARKMODE', darkMode: true }, '*');
        }

        // Update URL bar
        try {
            previewUrlDisplay.textContent = iframe.contentWindow.location.pathname;
        } catch (e) {}
    });

    // 5. Page Switcher (Homepage / Article / Category)
    document.querySelectorAll('.page-switch-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.page-switch-btn').forEach(b => {
                b.classList.remove('bg-slate-900', 'text-white');
                b.classList.add('bg-slate-50', 'text-slate-700');
            });
            this.classList.remove('bg-slate-50', 'text-slate-700');
            this.classList.add('bg-slate-900', 'text-white');

            const targetUrl = this.getAttribute('data-url');
            iframeLoader.style.display = 'flex';
            iframeLoader.classList.remove('opacity-0', 'pointer-events-none');
            previewUrlDisplay.textContent = targetUrl;
            iframe.src = targetUrl;
        });
    });

    // 6. Device Viewport Resizer (Desktop / Tablet / Mobile)
    document.querySelectorAll('.device-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.device-btn').forEach(b => {
                b.classList.remove('active', 'bg-white', 'text-slate-900', 'shadow-xs');
                b.classList.add('text-slate-600');
            });
            this.classList.add('active', 'bg-white', 'text-slate-900', 'shadow-xs');
            this.classList.remove('text-slate-600');

            const device = this.getAttribute('data-device');
            if (device === 'mobile') {
                viewportContainer.style.maxWidth = '375px';
            } else if (device === 'tablet') {
                viewportContainer.style.maxWidth = '768px';
            } else {
                viewportContainer.style.maxWidth = '100%';
            }
        });
    });

    // 7. Dark Mode Toggle
    const btnToggleDark = document.getElementById('btn-toggle-dark-preview');
    const darkModeIcon = document.getElementById('dark-mode-icon');
    const darkModeText = document.getElementById('dark-mode-text');

    btnToggleDark.addEventListener('click', function () {
        isDarkMode = !isDarkMode;
        darkModeIcon.textContent = isDarkMode ? '☀️' : '🌙';
        darkModeText.textContent = isDarkMode ? 'Dark Mode On' : 'Dark Mode Off';

        if (iframe && iframe.contentWindow) {
            iframe.contentWindow.postMessage({
                type: 'PORTAL_THEME_DARKMODE',
                darkMode: isDarkMode
            }, '*');
        }
    });

    // 8. Refresh Preview Button
    document.getElementById('btn-reload-preview').addEventListener('click', function () {
        iframeLoader.style.display = 'flex';
        iframeLoader.classList.remove('opacity-0', 'pointer-events-none');
        iframe.contentWindow.location.reload();
    });

    // 9. Reset Theme Button
    document.getElementById('btn-reset-theme').addEventListener('click', function () {
        broadcastThemeUpdate(publishedPrimary, publishedAccent);
    });

    // 10. Publish Theme to Live Portal (AJAX POST)
    async function publishTheme() {
        const publishBtns = [
            document.getElementById('btn-publish-theme-top'),
            document.getElementById('btn-publish-theme-bottom')
        ];

        publishBtns.forEach(btn => {
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');
            }
        });

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
            const response = await fetch("{{ route('admin.theme.publish') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    primary_color: activePrimary,
                    accent_color: activeAccent
                })
            });

            const data = await response.json();

            if (data.success) {
                // Update live published status
                syncStatusText.textContent = 'लाइव पब्लिश है (In Sync)';
                syncStatusBadge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-400/20 text-emerald-200 border border-emerald-400/30 flex items-center gap-1.5';

                // Show floating notification toast
                publishToast.classList.remove('translate-y-24', 'opacity-0');
                setTimeout(() => {
                    publishToast.classList.add('translate-y-24', 'opacity-0');
                }, 4000);
            }
        } catch (err) {
            alert('थीम पब्लिश करने में समस्या हुई। कृपया पुनः प्रयास करें।');
        } finally {
            publishBtns.forEach(btn => {
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-75', 'cursor-not-allowed');
                }
            });
        }
    }

    document.getElementById('btn-publish-theme-top').addEventListener('click', publishTheme);
    document.getElementById('btn-publish-theme-bottom').addEventListener('click', publishTheme);
});
</script>
@endsection
