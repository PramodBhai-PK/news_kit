<!-- ========================================================================= -->
<!-- AUTOMATIC NEWS POSTER & GRAPHIC CARD MAKER (ऑटोमैटिक ग्राफिक कार्ड स्टूडियो) -->
<!-- ========================================================================= -->
<div id="news-poster-studio-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto bg-slate-950/85 backdrop-blur-md transition-all duration-200">
    <div class="min-h-screen px-3 sm:px-6 py-6 flex items-center justify-center">
        
        <div class="relative w-full max-w-6xl bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl overflow-hidden flex flex-col text-slate-100 animate-in fade-in zoom-in-95 duration-200">
            
            <!-- Top Header Bar -->
            <div class="px-6 py-4 border-b border-slate-800 bg-slate-950/60 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-purple-600 to-rose-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-black text-base sm:text-lg text-white tracking-tight">ऑटोमैटिक न्यूज़ पोस्टर व ग्राफिक कार्ड मेकर</h3>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">HD 1080p</span>
                        </div>
                        <p class="text-xs text-slate-400">दैनिक भास्कर व लल्लनटॉप स्टाइल सोशल मीडिया कार्ड जनरेटर (WhatsApp, Insta, FB, X)</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" id="poster-studio-close-btn" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-colors" title="बंद करें (Esc)">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Studio Body: 2 Columns (Left Canvas Preview + Right Controls) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[580px]">
                
                <!-- LEFT COLUMN: Live Canvas Preview & Quick Action Bar (7 cols) -->
                <div class="lg:col-span-7 p-4 sm:p-6 bg-slate-950/40 flex flex-col items-center justify-between border-b lg:border-b-0 lg:border-r border-slate-800/80">
                    
                    <!-- Aspect Ratio & Resolution Info Pill -->
                    <div class="w-full flex items-center justify-between text-xs text-slate-400 mb-3 px-1">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span id="poster-canvas-dimen-badge" class="font-mono font-bold text-slate-300">1080 × 1080 px (1:1 Square)</span>
                        </span>
                        <span class="text-[11px] text-slate-500 hidden sm:inline">लाइव रेंडरिंग • कोई वॉटरमार्क नहीं</span>
                    </div>

                    <!-- Canvas Stage Wrapper with Subtle Grid Background -->
                    <div class="w-full flex-1 flex items-center justify-center p-2 rounded-2xl bg-slate-900/80 border border-slate-800/70 overflow-hidden relative min-h-[380px] max-h-[520px]">
                        <canvas id="newsPosterCanvas" class="max-w-full max-h-[480px] w-auto h-auto rounded-xl shadow-2xl object-contain border border-slate-700/50 transition-all duration-150"></canvas>
                        
                        <!-- Loading Overlay -->
                        <div id="poster-canvas-loader" class="absolute inset-0 bg-slate-900/80 backdrop-blur-xs flex items-center justify-center hidden">
                            <div class="flex items-center gap-2 text-rose-500 font-bold text-sm">
                                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>पोस्टर तैयार हो रहा है...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Primary Export Actions Bar -->
                    <div class="w-full mt-4 grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        <!-- Download HD PNG -->
                        <button type="button" id="poster-download-png-btn" class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-rose-600 to-brand-primary hover:brightness-110 text-white font-extrabold text-xs shadow-lg shadow-rose-600/30 transition-all">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>HD इमेज डाउनलोड करें</span>
                        </button>

                        <!-- Copy to Clipboard -->
                        <button type="button" id="poster-copy-btn" class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs border border-slate-700 transition-all">
                            <svg class="w-4 h-4 shrink-0 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span>क्लिपबोर्ड में कॉपी करें</span>
                        </button>

                        <!-- WhatsApp Share -->
                        <button type="button" id="poster-share-whatsapp-btn" class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition-all">
                            <svg class="w-4 h-4 shrink-0 fill-current" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.81 13.47 3.81 11.91C3.81 7.37 7.5 3.67 12.05 3.67Z"/></svg>
                            <span>WhatsApp शेयर</span>
                        </button>
                    </div>

                    <!-- Notification Feedback Toast -->
                    <div id="poster-studio-toast" class="hidden mt-2 text-xs font-semibold text-center text-emerald-400 bg-emerald-950/70 border border-emerald-800/80 px-3 py-1.5 rounded-lg w-full animate-in fade-in duration-150"></div>
                </div>

                <!-- RIGHT COLUMN: Customization Controls (5 cols) -->
                <div class="lg:col-span-5 p-4 sm:p-6 bg-slate-900/90 overflow-y-auto max-h-[640px] space-y-5">
                    
                    <!-- 1. Aspect Ratio Selector -->
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-2">1. कार्ड का आकार (Aspect Ratio)</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" data-ratio="1:1" class="poster-ratio-btn active-ratio flex flex-col items-center justify-center p-2.5 rounded-xl border border-rose-500 bg-rose-500/10 text-white font-bold text-xs transition-all">
                                <span class="text-sm">🔲 1:1</span>
                                <span class="text-[10px] text-slate-300 font-normal">वर्ग / पोस्ट</span>
                            </button>
                            <button type="button" data-ratio="9:16" class="poster-ratio-btn flex flex-col items-center justify-center p-2.5 rounded-xl border border-slate-800 bg-slate-950 hover:border-slate-700 text-slate-400 font-bold text-xs transition-all">
                                <span class="text-sm">📱 9:16</span>
                                <span class="text-[10px] text-slate-400 font-normal">स्टोरी / स्टेटस</span>
                            </button>
                            <button type="button" data-ratio="16:9" class="poster-ratio-btn flex flex-col items-center justify-center p-2.5 rounded-xl border border-slate-800 bg-slate-950 hover:border-slate-700 text-slate-400 font-bold text-xs transition-all">
                                <span class="text-sm">🖥️ 16:9</span>
                                <span class="text-[10px] text-slate-400 font-normal">X / यूट्यूब</span>
                            </button>
                        </div>
                    </div>

                    <!-- 2. Design Theme Selector -->
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-2">2. डिज़ाइन थीम (Themes)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" data-theme="breaking_crimson" class="poster-theme-btn active-theme flex items-center gap-2 p-2 rounded-xl border border-rose-500 bg-rose-950/40 text-left text-xs font-bold text-white transition-all">
                                <span class="w-3.5 h-3.5 rounded-full bg-rose-600 ring-2 ring-rose-400"></span>
                                <div>
                                    <span class="block leading-tight">ब्रेकिंग डार्क</span>
                                    <span class="block text-[9px] text-slate-400 font-normal">रेड क्रिमसन</span>
                                </div>
                            </button>
                            <button type="button" data-theme="editorial_navy" class="poster-theme-btn flex items-center gap-2 p-2 rounded-xl border border-slate-800 bg-slate-950 hover:border-slate-700 text-left text-xs font-bold text-slate-300 transition-all">
                                <span class="w-3.5 h-3.5 rounded-full bg-blue-600 ring-2 ring-blue-400"></span>
                                <div>
                                    <span class="block leading-tight">क्लासिक नेवी</span>
                                    <span class="block text-[9px] text-slate-400 font-normal">प्रीमियम ब्लू</span>
                                </div>
                            </button>
                            <button type="button" data-theme="golden_impact" class="poster-theme-btn flex items-center gap-2 p-2 rounded-xl border border-slate-800 bg-slate-950 hover:border-slate-700 text-left text-xs font-bold text-slate-300 transition-all">
                                <span class="w-3.5 h-3.5 rounded-full bg-amber-500 ring-2 ring-amber-300"></span>
                                <div>
                                    <span class="block leading-tight">गोल्डन इम्पैक्ट</span>
                                    <span class="block text-[9px] text-slate-400 font-normal">ब्लैक & गोल्ड</span>
                                </div>
                            </button>
                            <button type="button" data-theme="quote_statement" class="poster-theme-btn flex items-center gap-2 p-2 rounded-xl border border-slate-800 bg-slate-950 hover:border-slate-700 text-left text-xs font-bold text-slate-300 transition-all">
                                <span class="w-3.5 h-3.5 rounded-full bg-purple-600 ring-2 ring-purple-400"></span>
                                <div>
                                    <span class="block leading-tight">बयान / कोट कार्ड</span>
                                    <span class="block text-[9px] text-slate-400 font-normal">नेता/VIP स्टेटमेंट</span>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- 3. Headline & Font Size -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="poster-ctrl-headline" class="block text-[11px] font-black uppercase tracking-wider text-slate-400">3. मुख्य शीर्षक (Headline)</label>
                            <span id="poster-headline-length" class="text-[10px] text-slate-500 font-mono">0 अक्षर</span>
                        </div>
                        <textarea id="poster-ctrl-headline" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm font-bold text-white focus:border-rose-500 outline-none leading-relaxed resize-none" placeholder="यहाँ खबर का मुख्य शीर्षक लिखें..."></textarea>
                        
                        <!-- Font Size Slider -->
                        <div class="flex items-center gap-3 pt-1">
                            <span class="text-[10px] text-slate-400 shrink-0 font-bold">फ़ॉन्ट साइज़:</span>
                            <input type="range" id="poster-ctrl-fontsize" min="32" max="68" value="48" step="2" class="w-full accent-rose-500 h-1.5 bg-slate-800 rounded-lg cursor-pointer">
                            <span id="poster-ctrl-fontsize-val" class="text-[11px] font-mono text-rose-400 font-bold shrink-0">48px</span>
                        </div>
                    </div>

                    <!-- 4. Category & Location Badges -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Category Input & Color -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 mb-1">श्रेणी (Category):</label>
                            <div class="flex items-center gap-1.5">
                                <input type="text" id="poster-ctrl-category" value="ब्रेकिंग न्यूज़" class="flex-1 bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs text-white outline-none focus:border-rose-500">
                                <input type="color" id="poster-ctrl-category-color" value="#E11D48" class="w-8 h-8 rounded-lg border-0 bg-transparent cursor-pointer shrink-0" title="श्रेणी का रंग बदलें">
                            </div>
                        </div>

                        <!-- Location Badge -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-[11px] font-bold text-slate-400">लोकेशन (Location):</label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="poster-ctrl-show-location" checked class="sr-only peer">
                                    <div class="w-7 h-4 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-rose-600"></div>
                                </label>
                            </div>
                            <input type="text" id="poster-ctrl-location" value="📍 लखनऊ, उत्तर प्रदेश" placeholder="📍 शहर या राज्य" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs text-white outline-none focus:border-rose-500">
                        </div>
                    </div>

                    <!-- 5. Background Image & Dark Overlay Scrim -->
                    <div class="p-3 rounded-xl bg-slate-950 border border-slate-800/80 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-300">फ़ोटो व बैकग्राउंड डार्कनेस</span>
                            <span id="poster-ctrl-darkness-val" class="text-[10px] font-mono text-slate-400">75% डार्क</span>
                        </div>

                        <!-- Image File Upload or URL -->
                        <div class="flex items-center gap-2">
                            <label class="cursor-pointer flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs text-slate-300 transition-colors">
                                <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span>कंप्यूटर/मोबाइल से फ़ोटो चुनें</span>
                                <input type="file" id="poster-ctrl-image-file" accept="image/*" class="hidden">
                            </label>
                            <button type="button" id="poster-ctrl-clear-img" class="px-2.5 py-2 rounded-lg bg-slate-800 hover:bg-rose-950 hover:text-rose-400 text-slate-400 text-xs" title="फ़ोटो हटाएं (डार्क बैकग्राउंड रखें)">
                                हटाएँ
                            </button>
                        </div>

                        <!-- Darkness Scrim Range -->
                        <div class="pt-1">
                            <input type="range" id="poster-ctrl-darkness" min="30" max="95" value="75" step="5" class="w-full accent-rose-500 h-1.5 bg-slate-800 rounded-lg cursor-pointer">
                        </div>
                    </div>

                    <!-- 6. Reporter Byline & Branding Watermark Toggles -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                        <!-- Reporter Info -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-[11px] font-bold text-slate-400">रिपोर्टर नाम:</label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="poster-ctrl-show-author" checked class="sr-only peer">
                                    <div class="w-7 h-4 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-rose-600"></div>
                                </label>
                            </div>
                            <input type="text" id="poster-ctrl-author" value="डिजिटल डेस्क" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs text-white outline-none focus:border-rose-500">
                        </div>

                        <!-- Watermark Site Name -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-[11px] font-bold text-slate-400">वेबसाइट वॉटरमार्क:</label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="poster-ctrl-show-watermark" checked class="sr-only peer">
                                    <div class="w-7 h-4 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-rose-600"></div>
                                </label>
                            </div>
                            <input type="text" id="poster-ctrl-watermark" value="{{ setting('site_name', 'The LallanTop News') }}" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs text-white outline-none focus:border-rose-500">
                        </div>
                    </div>

                    <!-- 7. Alert Badge Text & Date -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 mb-1">अलर्ट बैज टेक्स्ट:</label>
                            <input type="text" id="poster-ctrl-badge" value="🔴 ब्रेकिंग न्यूज़" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs text-white outline-none focus:border-rose-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 mb-1">तारीख / समय:</label>
                            <input type="text" id="poster-ctrl-date" value="{{ now()->format('d M, Y') }}" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs text-white outline-none focus:border-rose-500">
                        </div>
                    </div>

                    <!-- Reset to defaults button -->
                    <div class="pt-2 text-right">
                        <button type="button" id="poster-ctrl-reset-btn" class="text-xs text-slate-500 hover:text-slate-300 font-semibold transition-colors">
                            🔄 फ़ील्ड्स रीसेट करें
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- AUTOMATIC NEWS POSTER ENGINE (PURE JAVASCRIPT CANVAS 2D RENDERER)         -->
<!-- ========================================================================= -->
<script>
(function() {
    // State Store
    const posterState = {
        ratio: '1:1', // '1:1', '9:16', '16:9'
        theme: 'breaking_crimson', // 'breaking_crimson', 'editorial_navy', 'golden_impact', 'quote_statement'
        width: 1080,
        height: 1080,
        headline: 'यहाँ अपनी बड़ी खबर या ब्रेकिंग न्यूज़ का शीर्षक लिखें...',
        fontSize: 48,
        category: 'ताज़ा ख़बर',
        categoryColor: '#E11D48',
        location: '📍 लखनऊ, उत्तर प्रदेश',
        showLocation: true,
        author: 'डिजिटल डेस्क',
        showAuthor: true,
        authorAvatar: null,
        authorAvatarImg: null,
        watermark: @json(setting('site_name', 'The LallanTop News')),
        showWatermark: true,
        badgeText: '🔴 ब्रेकिंग न्यूज़',
        dateText: @json(now()->format('d M, Y')),
        articleUrl: @json(url('/')),
        bgImageSrc: null,
        bgImageObj: null,
        darkness: 0.75, // scrim opacity
    };

    // DOM Elements
    const modal = document.getElementById('news-poster-studio-modal');
    const closeBtn = document.getElementById('poster-studio-close-btn');
    const canvas = document.getElementById('newsPosterCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');

    const dimenBadge = document.getElementById('poster-canvas-dimen-badge');
    const toast = document.getElementById('poster-studio-toast');
    const headlineInput = document.getElementById('poster-ctrl-headline');
    const headlineLen = document.getElementById('poster-headline-length');
    const fontSizeInput = document.getElementById('poster-ctrl-fontsize');
    const fontSizeVal = document.getElementById('poster-ctrl-fontsize-val');
    const categoryInput = document.getElementById('poster-ctrl-category');
    const categoryColorInput = document.getElementById('poster-ctrl-category-color');
    const locationInput = document.getElementById('poster-ctrl-location');
    const showLocationCheck = document.getElementById('poster-ctrl-show-location');
    const authorInput = document.getElementById('poster-ctrl-author');
    const showAuthorCheck = document.getElementById('poster-ctrl-show-author');
    const watermarkInput = document.getElementById('poster-ctrl-watermark');
    const showWatermarkCheck = document.getElementById('poster-ctrl-show-watermark');
    const badgeInput = document.getElementById('poster-ctrl-badge');
    const dateInput = document.getElementById('poster-ctrl-date');
    const darknessInput = document.getElementById('poster-ctrl-darkness');
    const darknessVal = document.getElementById('poster-ctrl-darkness-val');
    const imageFileInput = document.getElementById('poster-ctrl-image-file');
    const clearImgBtn = document.getElementById('poster-ctrl-clear-img');
    const downloadPngBtn = document.getElementById('poster-download-png-btn');
    const copyBtn = document.getElementById('poster-copy-btn');
    const shareWhatsappBtn = document.getElementById('poster-share-whatsapp-btn');
    const resetBtn = document.getElementById('poster-ctrl-reset-btn');

    // Ratio dimensions map
    const ratioDimensions = {
        '1:1': { width: 1080, height: 1080, label: '1080 × 1080 px (1:1 Square - WhatsApp / Insta)' },
        '9:16': { width: 1080, height: 1920, label: '1080 × 1920 px (9:16 Story - Status / Reel)' },
        '16:9': { width: 1200, height: 675, label: '1200 × 675 px (16:9 Landscape - X / YouTube)' }
    };

    // Helper: Draw Rounded Rectangles
    function drawRoundRect(ctx, x, y, w, h, r) {
        if (typeof ctx.roundRect === 'function') {
            ctx.beginPath();
            ctx.roundRect(x, y, w, h, r);
            return;
        }
        ctx.beginPath();
        ctx.moveTo(x + r, y);
        ctx.arcTo(x + w, y, x + w, y + h, r);
        ctx.arcTo(x + w, y + h, x, y + h, r);
        ctx.arcTo(x, y + h, x, y, r);
        ctx.arcTo(x, y, x + w, y, r);
        ctx.closePath();
    }

    // Helper: Wrap text in Canvas with word boundary & max lines
    function getWrappedLines(ctx, text, maxWidth) {
        const words = (text || '').trim().split(/\s+/);
        const lines = [];
        let currentLine = '';

        for (let i = 0; i < words.length; i++) {
            const word = words[i];
            const testLine = currentLine ? (currentLine + ' ' + word) : word;
            const metrics = ctx.measureText(testLine);

            if (metrics.width > maxWidth && currentLine) {
                lines.push(currentLine);
                currentLine = word;
            } else {
                currentLine = testLine;
            }
        }
        if (currentLine) {
            lines.push(currentLine);
        }
        return lines;
    }

    // Helper: Show Feedback Toast
    function showToast(msg) {
        if (!toast) return;
        toast.textContent = msg;
        toast.classList.remove('hidden');
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3500);
    }

    // Main Canvas Render Engine
    function renderPoster() {
        const { width, height } = ratioDimensions[posterState.ratio];
        canvas.width = width;
        canvas.height = height;

        ctx.clearRect(0, 0, width, height);

        // 1. Render Background
        if (posterState.bgImageObj && posterState.bgImageObj.complete && posterState.bgImageObj.naturalWidth > 0) {
            // Draw image with "cover" aspect fit
            const img = posterState.bgImageObj;
            const hRatio = width / img.width;
            const vRatio = height / img.height;
            const ratio = Math.max(hRatio, vRatio);
            const centerShiftX = (width - img.width * ratio) / 2;
            const centerShiftY = (height - img.height * ratio) / 2;
            ctx.drawImage(img, 0, 0, img.width, img.height, centerShiftX, centerShiftY, img.width * ratio, img.height * ratio);

            // Scrim Overlay: Gradient from subtle top to dark bottom where text lives
            const scrim = ctx.createLinearGradient(0, 0, 0, height);
            const dark = posterState.darkness;
            scrim.addColorStop(0, 'rgba(5, 5, 10, ' + Math.min(0.7, dark * 0.6) + ')');
            scrim.addColorStop(0.35, 'rgba(10, 10, 15, ' + Math.min(0.8, dark * 0.75) + ')');
            scrim.addColorStop(0.65, 'rgba(5, 5, 10, ' + dark + ')');
            scrim.addColorStop(1, 'rgba(0, 0, 5, ' + Math.min(0.98, dark + 0.15) + ')');
            ctx.fillStyle = scrim;
            ctx.fillRect(0, 0, width, height);
        } else {
            // No Image: Procedural Theme Gradient
            const bgGrad = ctx.createLinearGradient(0, 0, width, height);
            if (posterState.theme === 'breaking_crimson') {
                bgGrad.addColorStop(0, '#1a050b');
                bgGrad.addColorStop(0.5, '#2e0712');
                bgGrad.addColorStop(1, '#0d0205');
            } else if (posterState.theme === 'editorial_navy') {
                bgGrad.addColorStop(0, '#070f26');
                bgGrad.addColorStop(0.5, '#0f1d4a');
                bgGrad.addColorStop(1, '#050814');
            } else if (posterState.theme === 'golden_impact') {
                bgGrad.addColorStop(0, '#17140b');
                bgGrad.addColorStop(0.5, '#261e0f');
                bgGrad.addColorStop(1, '#0d0b07');
            } else {
                bgGrad.addColorStop(0, '#160d24');
                bgGrad.addColorStop(0.5, '#221138');
                bgGrad.addColorStop(1, '#0a0512');
            }
            ctx.fillStyle = bgGrad;
            ctx.fillRect(0, 0, width, height);

            // Subtle Background Geometric Grid / Particle Rings
            ctx.save();
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.03)';
            ctx.lineWidth = 1;
            for (let x = 0; x < width; x += 60) {
                ctx.beginPath();
                ctx.moveTo(x, 0);
                ctx.lineTo(x, height);
                ctx.stroke();
            }
            for (let y = 0; y < height; y += 60) {
                ctx.beginPath();
                ctx.moveTo(0, y);
                ctx.lineTo(width, y);
                ctx.stroke();
            }
            ctx.restore();
        }

        // Theme Specific Accent Trim & Corner Elements
        ctx.save();
        if (posterState.theme === 'breaking_crimson') {
            ctx.strokeStyle = '#E11D48';
            ctx.lineWidth = 6;
            ctx.strokeRect(0, 0, width, height);
        } else if (posterState.theme === 'golden_impact') {
            ctx.strokeStyle = '#F59E0B';
            ctx.lineWidth = 6;
            ctx.strokeRect(0, 0, width, height);
        } else if (posterState.theme === 'editorial_navy') {
            ctx.strokeStyle = '#38BDF8';
            ctx.lineWidth = 5;
            ctx.strokeRect(0, 0, width, height);
        } else {
            ctx.strokeStyle = '#A855F7';
            ctx.lineWidth = 5;
            ctx.strokeRect(0, 0, width, height);
        }
        ctx.restore();

        // Safe padding calculation based on ratio
        const padX = width * 0.065;
        let topY = height * 0.065;

        // 2. TOP HEADER SECTION: Brand Watermark, Category Badge, Alert Pill
        ctx.save();
        
        // Category Pill
        const catText = (posterState.category || 'समाचार').toUpperCase();
        ctx.font = 'bold 22px "Noto Sans Devanagari", "Outfit", sans-serif';
        const catWidth = ctx.measureText(catText).width + 36;
        const catHeight = 40;

        drawRoundRect(ctx, padX, topY, catWidth, catHeight, 10);
        ctx.fillStyle = posterState.categoryColor || '#E11D48';
        ctx.fill();

        ctx.fillStyle = '#FFFFFF';
        ctx.textAlign = 'left';
        ctx.textBaseline = 'middle';
        ctx.fillText(catText, padX + 18, topY + (catHeight / 2));

        // Alert Badge (e.g. 🔴 ब्रेकिंग न्यूज़ or ⚡ विशेष रिपोर्ट)
        if (posterState.badgeText) {
            const badgeX = padX + catWidth + 14;
            ctx.font = 'bold 20px "Noto Sans Devanagari", "Outfit", sans-serif';
            const badgeMetrics = ctx.measureText(posterState.badgeText);
            const badgeW = badgeMetrics.width + 30;

            drawRoundRect(ctx, badgeX, topY, badgeW, catHeight, 10);
            ctx.fillStyle = 'rgba(255, 255, 255, 0.12)';
            ctx.fill();
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.25)';
            ctx.lineWidth = 1;
            ctx.stroke();

            ctx.fillStyle = '#FFFFFF';
            ctx.textAlign = 'left';
            ctx.fillText(posterState.badgeText, badgeX + 15, topY + (catHeight / 2));
        }

        // Top Right: Portal Branding & Logo Text
        if (posterState.showWatermark && posterState.watermark) {
            ctx.textAlign = 'right';
            ctx.font = '900 24px "Outfit", "Noto Sans Devanagari", sans-serif';
            ctx.fillStyle = '#FFFFFF';
            ctx.shadowColor = 'rgba(0, 0, 0, 0.8)';
            ctx.shadowBlur = 8;
            ctx.fillText(posterState.watermark, width - padX, topY + (catHeight / 2));
        }
        ctx.restore();

        // 3. QUOTE THEME SPECIAL: Large Background Quotation Mark
        if (posterState.theme === 'quote_statement') {
            ctx.save();
            ctx.font = '900 240px "Outfit", serif';
            ctx.fillStyle = 'rgba(255, 255, 255, 0.08)';
            ctx.textAlign = 'left';
            ctx.textBaseline = 'top';
            ctx.fillText('“', padX - 20, topY + 60);
            ctx.restore();
        }

        // 4. MAIN HEADLINE SECTION
        ctx.save();
        const headline = posterState.headline || 'यहाँ अपनी खबर का शीर्षक लिखें...';
        const fontSize = parseInt(posterState.fontSize, 10) || 48;
        const lineHeight = fontSize * 1.38;
        ctx.font = `800 ${fontSize}px "Noto Sans Devanagari", "Outfit", system-ui, sans-serif`;
        
        const maxHeadlineWidth = width - (padX * 2);
        const headlineLines = getWrappedLines(ctx, headline, maxHeadlineWidth);

        // Calculate Y position based on aspect ratio
        let headlineStartY;
        if (posterState.ratio === '9:16') {
            headlineStartY = height * 0.52;
        } else if (posterState.ratio === '16:9') {
            headlineStartY = height * 0.38;
        } else {
            // 1:1 Square
            headlineStartY = height * 0.46;
        }

        // Location Badge (Rendered right above headline if enabled)
        if (posterState.showLocation && posterState.location) {
            const locY = headlineStartY - 38;
            ctx.font = 'bold 22px "Noto Sans Devanagari", "Outfit", sans-serif';
            ctx.fillStyle = '#FBBF24'; // Warm Gold/Amber
            ctx.textAlign = 'left';
            ctx.shadowColor = 'rgba(0, 0, 0, 0.9)';
            ctx.shadowBlur = 6;
            ctx.fillText(posterState.location, padX, locY);
        }

        // Render Headline Lines
        ctx.fillStyle = '#FFFFFF';
        ctx.textAlign = 'left';
        ctx.textBaseline = 'top';
        ctx.shadowColor = 'rgba(0, 0, 0, 0.95)';
        ctx.shadowBlur = 14;
        ctx.shadowOffsetX = 0;
        ctx.shadowOffsetY = 4;

        for (let i = 0; i < headlineLines.length; i++) {
            const line = headlineLines[i];
            const curY = headlineStartY + (i * lineHeight);

            // Special quote theme adds quotation marks
            if (posterState.theme === 'quote_statement' && i === 0 && !line.startsWith('“')) {
                ctx.fillText('“' + line, padX, curY);
            } else if (posterState.theme === 'quote_statement' && i === headlineLines.length - 1 && !line.endsWith('”')) {
                ctx.fillText(line + '”', padX, curY);
            } else {
                ctx.fillText(line, padX, curY);
            }
        }
        ctx.restore();

        // 5. BOTTOM FOOTER SECTION (Reporter Byline, Date Stamp & Website URL)
        ctx.save();
        const footerY = height - (height * 0.065);
        const footerLineY = footerY - 45;

        // Accent divider line
        const divGrad = ctx.createLinearGradient(padX, 0, width - padX, 0);
        if (posterState.theme === 'breaking_crimson') {
            divGrad.addColorStop(0, '#E11D48');
            divGrad.addColorStop(0.5, 'rgba(225, 29, 72, 0.5)');
            divGrad.addColorStop(1, 'rgba(225, 29, 72, 0)');
        } else if (posterState.theme === 'golden_impact') {
            divGrad.addColorStop(0, '#F59E0B');
            divGrad.addColorStop(0.5, 'rgba(245, 158, 11, 0.5)');
            divGrad.addColorStop(1, 'rgba(245, 158, 11, 0)');
        } else {
            divGrad.addColorStop(0, '#38BDF8');
            divGrad.addColorStop(0.5, 'rgba(56, 189, 248, 0.5)');
            divGrad.addColorStop(1, 'rgba(56, 189, 248, 0)');
        }
        ctx.strokeStyle = divGrad;
        ctx.lineWidth = 3;
        ctx.beginPath();
        ctx.moveTo(padX, footerLineY);
        ctx.lineTo(width - padX, footerLineY);
        ctx.stroke();

        // Reporter Profile & Byline
        let textLeftX = padX;
        if (posterState.showAuthor && posterState.author) {
            // Optional Avatar Circle
            if (posterState.authorAvatarImg && posterState.authorAvatarImg.complete) {
                const avatarRadius = 24;
                const avatarX = padX + avatarRadius;
                const avatarY = footerY - 6;

                ctx.save();
                ctx.beginPath();
                ctx.arc(avatarX, avatarY, avatarRadius, 0, Math.PI * 2);
                ctx.closePath();
                ctx.clip();
                ctx.drawImage(posterState.authorAvatarImg, avatarX - avatarRadius, avatarY - avatarRadius, avatarRadius * 2, avatarRadius * 2);
                ctx.restore();

                // Ring around avatar
                ctx.beginPath();
                ctx.arc(avatarX, avatarY, avatarRadius, 0, Math.PI * 2);
                ctx.strokeStyle = '#FFFFFF';
                ctx.lineWidth = 2.5;
                ctx.stroke();

                textLeftX = avatarX + avatarRadius + 14;
            }

            // Reporter Byline text
            ctx.font = 'bold 22px "Noto Sans Devanagari", "Outfit", sans-serif';
            ctx.fillStyle = '#FFFFFF';
            ctx.textAlign = 'left';
            ctx.textBaseline = 'middle';
            ctx.shadowColor = 'rgba(0, 0, 0, 0.8)';
            ctx.shadowBlur = 6;
            ctx.fillText(posterState.author, textLeftX, footerY - 14);

            ctx.font = '600 16px "Noto Sans Devanagari", "Outfit", sans-serif';
            ctx.fillStyle = '#94A3B8';
            ctx.fillText('विशेष रिपोर्टर • ' + (posterState.dateText || ''), textLeftX, footerY + 12);
        } else {
            // Date only on left
            ctx.font = '600 18px "Noto Sans Devanagari", "Outfit", sans-serif';
            ctx.fillStyle = '#CBD5E1';
            ctx.textAlign = 'left';
            ctx.textBaseline = 'middle';
            ctx.fillText('प्रकाशित: ' + (posterState.dateText || ''), padX, footerY);
        }

        // Right side: Web Domain / Read More CTA
        ctx.textAlign = 'right';
        ctx.textBaseline = 'middle';
        ctx.font = '800 20px "Outfit", "Noto Sans Devanagari", sans-serif';
        ctx.fillStyle = '#F1F5F9';
        ctx.shadowColor = 'rgba(0, 0, 0, 0.8)';
        ctx.shadowBlur = 6;
        
        let displayHost = 'lallantopnews.com';
        try {
            const parsed = new URL(posterState.articleUrl);
            displayHost = parsed.hostname.replace('www.', '');
        } catch(e) {}
        ctx.fillText(displayHost + ' • पूरी ख़बर पढ़ें →', width - padX, footerY);

        ctx.restore();
    }

    // Helper: Safely load image object (with crossOrigin)
    function loadPosterImage(src, callback) {
        if (!src) {
            posterState.bgImageObj = null;
            if (callback) callback();
            return;
        }
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = function() {
            posterState.bgImageObj = img;
            if (callback) callback();
        };
        img.onerror = function() {
            console.warn('[Poster Studio] Could not load image via CORS; using procedural theme background:', src);
            posterState.bgImageObj = null;
            if (callback) callback();
        };
        img.src = src;
    }

    // Helper: Safely load author avatar
    function loadAuthorAvatar(src) {
        if (!src) {
            posterState.authorAvatarImg = null;
            return;
        }
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = function() {
            posterState.authorAvatarImg = img;
            renderPoster();
        };
        img.onerror = function() {
            posterState.authorAvatarImg = null;
        };
        img.src = src;
    }

    // Ratio Switch Event Handlers
    document.querySelectorAll('.poster-ratio-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.poster-ratio-btn').forEach(b => {
                b.classList.remove('active-ratio', 'border-rose-500', 'bg-rose-500/10', 'text-white');
                b.classList.add('border-slate-800', 'bg-slate-950', 'text-slate-400');
            });
            this.classList.add('active-ratio', 'border-rose-500', 'bg-rose-500/10', 'text-white');
            this.classList.remove('border-slate-800', 'bg-slate-950', 'text-slate-400');

            posterState.ratio = this.getAttribute('data-ratio');
            if (dimenBadge) {
                dimenBadge.textContent = ratioDimensions[posterState.ratio].label;
            }
            renderPoster();
        });
    });

    // Theme Switch Event Handlers
    document.querySelectorAll('.poster-theme-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.poster-theme-btn').forEach(b => {
                b.classList.remove('active-theme', 'border-rose-500', 'bg-rose-950/40', 'text-white');
                b.classList.add('border-slate-800', 'bg-slate-950', 'text-slate-300');
            });
            this.classList.add('active-theme', 'border-rose-500', 'bg-rose-950/40', 'text-white');
            this.classList.remove('border-slate-800', 'bg-slate-950', 'text-slate-300');

            posterState.theme = this.getAttribute('data-theme');
            renderPoster();
        });
    });

    // Inputs Event Listeners (Real-time live re-render)
    if (headlineInput) {
        headlineInput.addEventListener('input', function() {
            posterState.headline = this.value;
            if (headlineLen) headlineLen.textContent = `${this.value.length} अक्षर`;
            renderPoster();
        });
    }

    if (fontSizeInput) {
        fontSizeInput.addEventListener('input', function() {
            posterState.fontSize = this.value;
            if (fontSizeVal) fontSizeVal.textContent = `${this.value}px`;
            renderPoster();
        });
    }

    if (categoryInput) {
        categoryInput.addEventListener('input', function() {
            posterState.category = this.value;
            renderPoster();
        });
    }

    if (categoryColorInput) {
        categoryColorInput.addEventListener('input', function() {
            posterState.categoryColor = this.value;
            renderPoster();
        });
    }

    if (locationInput) {
        locationInput.addEventListener('input', function() {
            posterState.location = this.value;
            renderPoster();
        });
    }

    if (showLocationCheck) {
        showLocationCheck.addEventListener('change', function() {
            posterState.showLocation = this.checked;
            renderPoster();
        });
    }

    if (authorInput) {
        authorInput.addEventListener('input', function() {
            posterState.author = this.value;
            renderPoster();
        });
    }

    if (showAuthorCheck) {
        showAuthorCheck.addEventListener('change', function() {
            posterState.showAuthor = this.checked;
            renderPoster();
        });
    }

    if (watermarkInput) {
        watermarkInput.addEventListener('input', function() {
            posterState.watermark = this.value;
            renderPoster();
        });
    }

    if (showWatermarkCheck) {
        showWatermarkCheck.addEventListener('change', function() {
            posterState.showWatermark = this.checked;
            renderPoster();
        });
    }

    if (badgeInput) {
        badgeInput.addEventListener('input', function() {
            posterState.badgeText = this.value;
            renderPoster();
        });
    }

    if (dateInput) {
        dateInput.addEventListener('input', function() {
            posterState.dateText = this.value;
            renderPoster();
        });
    }

    if (darknessInput) {
        darknessInput.addEventListener('input', function() {
            posterState.darkness = parseInt(this.value, 10) / 100;
            if (darknessVal) darknessVal.textContent = `${this.value}% डार्क`;
            renderPoster();
        });
    }

    // Custom Image File Upload via FileReader (NEVER taints canvas)
    if (imageFileInput) {
        imageFileInput.addEventListener('change', function(e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(event) {
                loadPosterImage(event.target.result, function() {
                    renderPoster();
                    showToast('✅ फ़ोटो सफलतापूर्वक लोड हो गई!');
                });
            };
            reader.readAsDataURL(file);
        });
    }

    if (clearImgBtn) {
        clearImgBtn.addEventListener('click', function() {
            posterState.bgImageObj = null;
            if (imageFileInput) imageFileInput.value = '';
            renderPoster();
            showToast('बैकग्राउंड फ़ोटो हटा दी गई');
        });
    }

    // Reset Defaults
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            posterState.fontSize = 48;
            if (fontSizeInput) fontSizeInput.value = 48;
            if (fontSizeVal) fontSizeVal.textContent = '48px';
            posterState.darkness = 0.75;
            if (darknessInput) darknessInput.value = 75;
            if (darknessVal) darknessVal.textContent = '75% डार्क';
            posterState.showLocation = true;
            if (showLocationCheck) showLocationCheck.checked = true;
            posterState.showAuthor = true;
            if (showAuthorCheck) showAuthorCheck.checked = true;
            posterState.showWatermark = true;
            if (showWatermarkCheck) showWatermarkCheck.checked = true;
            renderPoster();
            showToast('सेटिंग्स रीसेट हो गई हैं');
        });
    }

    // Download PNG Action
    if (downloadPngBtn) {
        downloadPngBtn.addEventListener('click', function() {
            try {
                const dataUrl = canvas.toDataURL('image/png');
                const link = document.createElement('a');
                const cleanName = (posterState.headline || 'news-poster')
                    .replace(/[^a-zA-Z0-9\u0900-\u097F]/g, '-')
                    .substring(0, 40);
                link.download = `${cleanName}-${posterState.ratio.replace(':', 'x')}-${Date.now()}.png`;
                link.href = dataUrl;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                showToast('🎉 HD इमेज डाउनलोड हो गई!');
            } catch(e) {
                console.error(e);
                showToast('⚠️ डाउनलोड विफल: कृपया स्थानीय फ़ोटो अपलोड करके पुनः प्रयास करें।');
            }
        });
    }

    // Copy to Clipboard Action
    if (copyBtn) {
        copyBtn.addEventListener('click', function() {
            try {
                canvas.toBlob(async function(blob) {
                    if (!blob) {
                        showToast('इमेज क्लिपबोर्ड में कॉपी नहीं हो सकी।');
                        return;
                    }
                    try {
                        await navigator.clipboard.write([
                            new ClipboardItem({ 'image/png': blob })
                        ]);
                        showToast('📋 इमेज क्लिपबोर्ड में कॉपी हो गई! कहीं भी Ctrl+V करें।');
                    } catch(err) {
                        console.warn('ClipboardItem write failed:', err);
                        showToast('क्लिपबोर्ड में कॉपी करने की अनुमति नहीं मिली। कृपया इमेज डाउनलोड करें।');
                    }
                }, 'image/png');
            } catch(e) {
                console.error(e);
                showToast('इमेज कॉपी नहीं हो सकी।');
            }
        });
    }

    // WhatsApp Share Action
    if (shareWhatsappBtn) {
        shareWhatsappBtn.addEventListener('click', async function() {
            const shareTitle = posterState.headline || 'बड़ी खबर';
            const shareUrl = posterState.articleUrl || window.location.href;

            // Attempt Native File Web Share API (Mobile Support)
            if (navigator.canShare && canvas.toBlob) {
                canvas.toBlob(async function(blob) {
                    if (blob) {
                        const file = new File([blob], 'news-poster.png', { type: 'image/png' });
                        if (navigator.canShare({ files: [file] })) {
                            try {
                                await navigator.share({
                                    title: shareTitle,
                                    text: `${shareTitle}\n\nपूरी खबर पढ़ें: ${shareUrl}`,
                                    files: [file]
                                });
                                showToast('सफलतापूर्वक शेयर किया गया!');
                                return;
                            } catch(err) {
                                // Fallback to normal WhatsApp URL
                            }
                        }
                    }
                    // Web URL Fallback
                    const waText = encodeURIComponent(`🔴 *${shareTitle}*\n\nपूरी ख़बर यहाँ पढ़ें:\n${shareUrl}`);
                    window.open(`https://api.whatsapp.com/send?text=${waText}`, '_blank');
                }, 'image/png');
            } else {
                const waText = encodeURIComponent(`🔴 *${shareTitle}*\n\nपूरी ख़बर यहाँ पढ़ें:\n${shareUrl}`);
                window.open(`https://api.whatsapp.com/send?text=${waText}`, '_blank');
            }
        });
    }

    // Modal Close Triggers
    function closeStudioModal() {
        if (modal) modal.classList.add('hidden');
    }

    if (closeBtn) closeBtn.addEventListener('click', closeStudioModal);
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeStudioModal();
        });
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeStudioModal();
        }
    });

    // Public Studio Launcher Method
    window.openNewsPosterStudio = function(data = {}) {
        if (!modal) return;

        // Populate state from passed data
        if (data.title) posterState.headline = data.title;
        if (data.category) posterState.category = data.category;
        if (data.categoryColor) posterState.categoryColor = data.categoryColor;
        if (data.location) {
            posterState.location = data.location;
            posterState.showLocation = true;
        } else if (data.location === '') {
            posterState.showLocation = false;
        }
        if (data.author) posterState.author = data.author;
        if (data.authorAvatar) posterState.authorAvatar = data.authorAvatar;
        if (data.date) posterState.dateText = data.date;
        if (data.url) posterState.articleUrl = data.url;

        // Auto-adapt font size based on headline length
        if (posterState.headline.length > 90) {
            posterState.fontSize = 38;
        } else if (posterState.headline.length > 60) {
            posterState.fontSize = 44;
        } else {
            posterState.fontSize = 50;
        }

        // Sync UI inputs
        if (headlineInput) headlineInput.value = posterState.headline;
        if (headlineLen) headlineLen.textContent = `${posterState.headline.length} अक्षर`;
        if (fontSizeInput) fontSizeInput.value = posterState.fontSize;
        if (fontSizeVal) fontSizeVal.textContent = `${posterState.fontSize}px`;
        if (categoryInput) categoryInput.value = posterState.category;
        if (categoryColorInput) categoryColorInput.value = posterState.categoryColor;
        if (locationInput) locationInput.value = posterState.location;
        if (showLocationCheck) showLocationCheck.checked = posterState.showLocation;
        if (authorInput) authorInput.value = posterState.author;
        if (showAuthorCheck) showAuthorCheck.checked = posterState.showAuthor;
        if (watermarkInput) watermarkInput.value = posterState.watermark;
        if (showWatermarkCheck) showWatermarkCheck.checked = posterState.showWatermark;
        if (dateInput) dateInput.value = posterState.dateText;

        // Open modal
        modal.classList.remove('hidden');

        // Load images
        if (data.authorAvatar) {
            loadAuthorAvatar(data.authorAvatar);
        } else {
            posterState.authorAvatarImg = null;
        }

        if (data.image) {
            loadPosterImage(data.image, function() {
                renderPoster();
            });
        } else {
            posterState.bgImageObj = null;
            renderPoster();
        }
    };

    // Attach Click Handlers to all ".open-news-poster-btn" triggers anywhere on the page
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.open-news-poster-btn');
        if (!btn) return;

        e.preventDefault();
        const data = {
            title: btn.getAttribute('data-title') || '',
            category: btn.getAttribute('data-category') || 'ताज़ा ख़बर',
            categoryColor: btn.getAttribute('data-category-color') || '#E11D48',
            location: btn.getAttribute('data-location') || '',
            author: btn.getAttribute('data-author') || 'संपादक',
            authorAvatar: btn.getAttribute('data-author-avatar') || '',
            image: btn.getAttribute('data-image') || '',
            date: btn.getAttribute('data-date') || '',
            url: btn.getAttribute('data-url') || window.location.href,
        };

        window.openNewsPosterStudio(data);
    });

})();
</script>
