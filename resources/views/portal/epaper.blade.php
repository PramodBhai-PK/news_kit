<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ $edition ? $edition->title . ' - ' . $edition->formatted_date : 'Digital E-Paper' }} | {{ config('app.name', 'NewsKit') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/js/page-flip.browser.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-title { font-family: 'Playfair Display', Georgia, serif; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0b0f19; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #334155; }

        /* Flipbook Styling */
        .flipbook-viewport {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .flipbook-container {
            margin: auto;
            position: relative;
            filter: drop-shadow(0 25px 40px rgba(0, 0, 0, 0.75));
        }

        .page {
            background-color: #faf8f5;
            overflow: hidden;
            position: relative;
            box-shadow: inset 0 0 30px rgba(0, 0, 0, 0.05);
        }

        /* 3D Newspaper Spine Shading */
        .page.--left .spine-shadow {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 32px;
            background: linear-gradient(to right, transparent, rgba(0, 0, 0, 0.15) 80%, rgba(0, 0, 0, 0.28) 100%);
            pointer-events: none;
            z-index: 10;
        }

        .page.--right .spine-shadow {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 32px;
            background: linear-gradient(to left, transparent, rgba(0, 0, 0, 0.15) 80%, rgba(0, 0, 0, 0.28) 100%);
            pointer-events: none;
            z-index: 10;
        }

        /* Crop Selection Box */
        .crop-selection {
            position: absolute;
            border: 2px dashed #f43f5e;
            background-color: rgba(244, 63, 94, 0.18);
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.65);
            pointer-events: none;
            z-index: 50;
        }

        /* Smooth Glass UI */
        .glass-panel {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(51, 65, 85, 0.6);
        }
    </style>
</head>
<body class="bg-[#090D16] text-slate-100 min-h-screen flex flex-col antialiased selection:bg-rose-500 selection:text-white overflow-hidden select-none">

    <!-- 1. Top Luxury Newspaper Toolbar -->
    <header class="h-14 sm:h-16 glass-panel px-3 sm:px-6 flex items-center justify-between shrink-0 z-40 border-b border-slate-800/80">
        <!-- Left: Logo & Masthead Info -->
        <div class="flex items-center gap-2.5 sm:gap-4">
            <a href="/" class="flex items-center gap-2 group">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-rose-500 to-rose-700 flex items-center justify-center font-black text-white text-xs shadow-md shadow-rose-600/30">
                    NK
                </div>
                <div class="hidden sm:block">
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-sm tracking-tight text-white group-hover:text-rose-400 transition-colors">
                            {{ config('app.name', 'NewsKit') }}
                        </span>
                        <span class="text-[10px] uppercase font-black tracking-widest px-1.5 py-0.5 rounded bg-rose-600/20 text-rose-400 border border-rose-500/30">
                            E-PAPER 3D
                        </span>
                    </div>
                    <div class="text-[10px] text-slate-400 font-medium">Digital Broadsheet Reader</div>
                </div>
            </a>

            @if($edition)
                <div class="h-5 w-px bg-slate-800 hidden md:block"></div>
                <div class="hidden md:flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-300 max-w-[200px] truncate">{{ $edition->title }}</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-800 text-slate-300 border border-slate-700">
                        📅 {{ $edition->formatted_date }}
                    </span>
                </div>
            @endif
        </div>

        <!-- Center: Interactive Reading Controls -->
        @if($edition && $edition->pages->isNotEmpty())
        <div class="flex items-center gap-1 sm:gap-2">
            <!-- Previous Button -->
            <button onclick="flipPrev()" id="btnPrevTop" title="Previous Page (Left Arrow)"
                    class="p-2 sm:px-3 sm:py-1.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700/60 transition flex items-center gap-1 text-xs font-bold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span class="hidden md:inline">Prev</span>
            </button>

            <!-- Page Indicator Badge -->
            <div class="px-3 py-1 sm:py-1.5 rounded-xl bg-slate-900 border border-slate-700/80 flex items-center gap-1.5 text-xs font-mono font-bold text-rose-400 shadow-inner">
                <span id="pageIndicatorText">Page 1 / {{ $edition->pages->count() }}</span>
            </div>

            <!-- Next Button -->
            <button onclick="flipNext()" id="btnNextTop" title="Next Page (Right Arrow)"
                    class="p-2 sm:px-3 sm:py-1.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700/60 transition flex items-center gap-1 text-xs font-bold">
                <span class="hidden md:inline">Next</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>

            <!-- Article Clipping Tool Button ✂️ (Industry Signature Feature) -->
            <button onclick="startClipping()" id="clipBtn" title="Clip & Share Article (C)"
                    class="ml-1 sm:ml-2 flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-extrabold shadow-md shadow-rose-600/30 transition transform active:scale-95">
                <span class="text-sm">✂️</span>
                <span class="hidden sm:inline">Clip Article</span>
            </button>

            <!-- Zoom & Full High-Res Read Mode -->
            <button onclick="openZoomModal()" title="Zoom & Read Columns (Z)"
                    class="flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white border border-slate-700 text-xs font-bold transition">
                <svg class="w-3.5 h-3.5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                <span class="hidden lg:inline">Zoom Read</span>
            </button>
        </div>
        @endif

        <!-- Right: Actions, Audio, Fullscreen & PDF Download -->
        <div class="flex items-center gap-1.5 sm:gap-2">
            <!-- Audio Toggle (Paper Sound) -->
            <button onclick="toggleSound()" id="soundToggleBtn" title="Page Flip Sound (S)"
                    class="p-2 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700/60 transition">
                <span id="soundIcon" class="text-xs">🔊</span>
            </button>

            <!-- Spread Mode Toggle (Desktop) -->
            <button onclick="toggleSpreadMode()" id="spreadToggleBtn" title="Toggle 2-Page / 1-Page Mode"
                    class="hidden md:flex p-2 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700/60 transition text-xs font-bold items-center gap-1">
                <span id="spreadIcon">📖</span>
                <span id="spreadLabel" class="text-[11px] hidden xl:inline">Spread</span>
            </button>

            <!-- Fullscreen Toggle -->
            <button onclick="toggleFullscreen()" title="Fullscreen Mode (F)"
                    class="hidden sm:flex p-2 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700/60 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
            </button>

            <!-- PDF Download Button -->
            @if($edition && $edition->pdf_file)
                <a href="{{ route('portal.epaper.download', $edition) }}"
                   title="Download Original Print PDF Edition"
                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gradient-to-r from-red-600 to-rose-700 text-white text-xs font-extrabold hover:from-red-500 hover:to-rose-600 shadow-md shadow-red-700/20 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span class="hidden sm:inline">PDF</span>
                </a>
            @endif

            <!-- Date Archive Dropdown -->
            @if($recentEditions->isNotEmpty())
                <select onchange="window.location.href='/epaper/' + this.value"
                        class="bg-slate-800 border border-slate-700 text-slate-200 text-xs rounded-xl px-2.5 py-1.5 font-bold outline-none cursor-pointer hover:border-slate-600 hidden sm:block">
                    @foreach($recentEditions as $recent)
                        <option value="{{ $recent->edition_date->format('Y-m-d') }}" {{ ($edition && $edition->id === $recent->id) ? 'selected' : '' }}>
                            {{ $recent->formatted_date }}
                        </option>
                    @endforeach
                </select>
            @endif
        </div>
    </header>

    @if(!$edition || $edition->pages->isEmpty())
        <!-- Empty State -->
        <main class="flex-1 flex flex-col items-center justify-center p-8 text-center">
            <div class="w-20 h-20 rounded-3xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-500 mb-4 shadow-xl">
                <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
            <h2 class="text-xl font-black text-white mb-2">No Digital E-Paper Edition Found</h2>
            <p class="text-xs text-slate-400 max-w-sm mb-6">There is no published digital edition available for this date. Check back later or jump to today's national broadsheet.</p>
            <a href="/epaper" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-extrabold rounded-xl shadow-lg transition">
                Read Today's Edition →
            </a>
        </main>
    @else
        <!-- 2. Main 3D Broadsheet Flipbook Stage -->
        <main class="flex-1 relative overflow-hidden flex items-center justify-center p-2 sm:p-4 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-slate-900 via-[#0B0F19] to-[#06080F]">
            
            <!-- Large Floating Navigation Wings (Left / Right) -->
            <button onclick="flipPrev()" id="btnPrevWing" title="Previous Page"
                    class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 z-30 w-12 h-16 rounded-2xl glass-panel text-slate-300 hover:text-white hover:bg-rose-600 hover:border-rose-500 flex items-center justify-center shadow-2xl transition duration-200 group active:scale-95">
                <svg class="w-6 h-6 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </button>

            <button onclick="flipNext()" id="btnNextWing" title="Next Page"
                    class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 z-30 w-12 h-16 rounded-2xl glass-panel text-slate-300 hover:text-white hover:bg-rose-600 hover:border-rose-500 flex items-center justify-center shadow-2xl transition duration-200 group active:scale-95">
                <svg class="w-6 h-6 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>

            <!-- 3D Flipbook Canvas Wrapper -->
            <div class="flipbook-viewport" id="flipbookViewport">
                <div id="flipbook" class="flipbook-container">
                    @foreach($edition->pages as $index => $page)
                        <div class="page {{ ($index % 2 === 0) ? '--left' : '--right' }}" 
                             data-page="{{ $index + 1 }}" 
                             data-title="{{ $page->page_title }}" 
                             data-density="{{ ($index === 0 || $index === $edition->pages->count() - 1) ? 'hard' : 'soft' }}">
                            
                            <!-- 3D Center Spine Gradient Shadow -->
                            <div class="spine-shadow"></div>

                            <!-- Page Image -->
                            <img src="{{ $page->page_image }}" 
                                 alt="{{ $page->page_title }}" 
                                 id="page-img-{{ $index + 1 }}"
                                 class="w-full h-full object-contain pointer-events-none select-none"
                                 loading="eager">

                            <!-- Subtle Newsprint Texture Overlay -->
                            <div class="absolute inset-0 pointer-events-none opacity-[0.02] bg-[radial-gradient(#000_1px,transparent_1px)] [background-size:12px_12px]"></div>

                            <!-- Page Number Corner Badge -->
                            <div class="absolute bottom-2 {{ ($index % 2 === 0) ? 'left-3' : 'right-3' }} px-2 py-0.5 rounded bg-black/60 backdrop-blur-sm text-[10px] font-mono text-white/90 z-20">
                                {{ $index + 1 }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Hint: Drag corner or click arrow -->
            <div id="flipHint" class="absolute bottom-20 left-1/2 -translate-x-1/2 px-4 py-1.5 rounded-full bg-slate-900/80 border border-slate-700/60 text-slate-400 text-[11px] font-semibold flex items-center gap-2 pointer-events-none transition-opacity duration-700">
                <span>💡 Tip: Click page corners or use arrow keys to flip with realistic 3D paper animation.</span>
            </div>
        </main>

        <!-- 3. Bottom Dock Thumbnails Drawer Strip -->
        <footer class="h-20 glass-panel border-t border-slate-800/80 px-4 flex items-center justify-between shrink-0 z-40 relative">
            <!-- Left Info -->
            <div class="hidden md:flex items-center gap-2 text-xs text-slate-400 font-semibold shrink-0">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span id="bottomPageTitle">Page 1: Front Page</span>
            </div>

            <!-- Center Thumbnails Strip -->
            <div class="flex items-center gap-2 sm:gap-3 overflow-x-auto max-w-3xl py-2 px-2 scrollbar-none mx-auto">
                @foreach($edition->pages as $index => $page)
                    <button onclick="goToPage({{ $index }})" 
                            id="thumb-btn-{{ $index + 1 }}" 
                            title="{{ $page->page_title }}"
                            class="thumb-btn shrink-0 relative group rounded-lg overflow-hidden border-2 transition-all duration-200 {{ $index === 0 ? 'border-rose-500 shadow-lg shadow-rose-500/20 scale-105' : 'border-slate-700/60 hover:border-slate-500 opacity-60 hover:opacity-100' }}">
                        <img src="{{ $page->page_image }}" alt="Thumb {{ $index + 1 }}" class="w-10 h-14 object-cover">
                        <span class="absolute bottom-0 inset-x-0 bg-black/80 text-[9px] font-mono font-bold text-center text-white py-0.5">
                            P.{{ $index + 1 }}
                        </span>
                    </button>
                @endforeach
            </div>

            <!-- Right Info / Analytics -->
            <div class="hidden lg:flex items-center gap-3 text-xs text-slate-400 font-mono shrink-0">
                <span>👁️ {{ number_format($edition->views_count) }} readers</span>
                <span>•</span>
                <span>📥 {{ number_format($edition->downloads_count) }} downloads</span>
            </div>
        </footer>

        <!-- 4. Interactive Article Clipping Overlay (Crop Tool) -->
        <div id="clippingOverlay" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-[2px] cursor-crosshair">
            <!-- Top Instruction Bar -->
            <div class="absolute top-4 inset-x-0 mx-auto max-w-lg glass-panel px-4 py-2.5 rounded-2xl flex items-center justify-between shadow-2xl border border-rose-500/40">
                <div class="flex items-center gap-2">
                    <span class="text-lg">✂️</span>
                    <div>
                        <div class="text-xs font-black text-white">ARTICLE CLIPPING TOOL</div>
                        <div class="text-[10px] text-slate-300">Click and drag over any article, headline or photo to crop.</div>
                    </div>
                </div>
                <button onclick="cancelClipping()" class="px-3 py-1 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold transition">
                    Cancel (Esc)
                </button>
            </div>

            <!-- Selection Box (Dynamically resized by mouse) -->
            <div id="cropBox" class="crop-selection hidden">
                <div class="absolute top-1 left-2 text-[9px] font-black uppercase text-white bg-rose-600 px-1 rounded">Clipped Area</div>
            </div>
        </div>

        <!-- 5. Clipping Result & Share Modal -->
        <div id="clippingModal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-md flex items-center justify-center p-4">
            <div class="glass-panel max-w-xl w-full rounded-3xl p-6 border border-slate-700/80 shadow-2xl animate-in fade-in zoom-in-95 duration-200">
                <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">📰</span>
                        <div>
                            <h3 class="font-black text-sm text-white">OFFICIAL ARTICLE CLIPPING</h3>
                            <p class="text-[11px] text-slate-400">Verified from {{ $edition->title }} ({{ $edition->formatted_date }})</p>
                        </div>
                    </div>
                    <button onclick="closeClippingModal()" class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Clipped Canvas / Image Container -->
                <div class="my-4 max-h-[55vh] overflow-auto rounded-2xl border border-slate-700/80 bg-white p-2 flex justify-center shadow-inner">
                    <img id="clippedImagePreview" src="" alt="Clipped Story" class="max-w-full h-auto object-contain rounded-lg">
                </div>

                <!-- Share & Download Actions -->
                <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                    <button onclick="clipAgain()" class="px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition">
                        ✂️ Clip Another
                    </button>

                    <div class="flex items-center gap-2">
                        <!-- Download PNG -->
                        <a id="downloadClipLink" href="#" download="newskit_clipping.jpg"
                           class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold transition border border-slate-700">
                            <svg class="w-3.5 h-3.5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Download Image</span>
                        </a>

                        <!-- Share WhatsApp -->
                        <button onclick="shareClipWhatsApp()"
                                class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-extrabold shadow-lg shadow-emerald-600/30 transition">
                            <span>💬</span>
                            <span>WhatsApp</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. High-Res Zoom & Read Lightbox Modal -->
        <div id="zoomModal" class="fixed inset-0 z-50 hidden bg-black/95 backdrop-blur-md flex flex-col">
            <!-- Zoom Header Bar -->
            <div class="h-14 glass-panel px-4 flex items-center justify-between shrink-0 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <span class="font-extrabold text-sm text-white">🔍 High-Resolution Column Reader</span>
                    <span id="zoomModalPageTitle" class="text-xs text-rose-400 font-mono">Page 1</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 bg-slate-800 px-3 py-1 rounded-xl text-xs">
                        <button onclick="adjustZoom(-0.25)" class="px-1 text-slate-300 hover:text-white font-bold text-sm">-</button>
                        <span id="zoomScaleLabel" class="font-mono text-[11px] text-slate-300">125%</span>
                        <button onclick="adjustZoom(0.25)" class="px-1 text-slate-300 hover:text-white font-bold text-sm">+</button>
                    </div>
                    <button onclick="closeZoomModal()" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-bold transition">
                        Done (Esc)
                    </button>
                </div>
            </div>

            <!-- Scrollable Full-Page Viewport -->
            <div id="zoomContainer" class="flex-1 overflow-auto p-4 flex items-start justify-center cursor-grab active:cursor-grabbing">
                <img id="zoomImage" src="" alt="High-Res Page" class="max-w-none transition-transform duration-150 origin-top shadow-2xl rounded-xl">
            </div>
        </div>

        <!-- Flipbook Controller Script -->
        <script>
            let pageFlip = null;
            let soundEnabled = true;
            let currentOrientation = 'landscape';
            const totalPages = {{ $edition->pages->count() }};
            let isClipping = false;
            let zoomScale = 1.25;

            const pagesData = [
                @foreach($edition->pages as $index => $page)
                    {
                        index: {{ $index }},
                        number: {{ $index + 1 }},
                        title: "{{ addslashes($page->page_title) }}",
                        url: "{{ $page->page_image }}"
                    },
                @endforeach
            ];

            // 1. Synthesize Authentic Paper Turn Audio (Web Audio API)
            function playPaperSound() {
                if (!soundEnabled) return;
                try {
                    const AudioContext = window.AudioContext || window.webkitAudioContext;
                    if (!AudioContext) return;
                    const ctx = new AudioContext();
                    const bufferSize = ctx.sampleRate * 0.22;
                    const buffer = ctx.createBuffer(1, bufferSize, ctx.sampleRate);
                    const data = buffer.getChannelData(0);
                    for (let i = 0; i < bufferSize; i++) {
                        data[i] = (Math.random() * 2 - 1) * Math.exp(-i / (ctx.sampleRate * 0.04));
                    }
                    const noise = ctx.createBufferSource();
                    noise.buffer = buffer;

                    const filter = ctx.createBiquadFilter();
                    filter.type = 'bandpass';
                    filter.frequency.value = 1450;
                    filter.Q.value = 2.2;

                    const gain = ctx.createGain();
                    gain.gain.setValueAtTime(0.4, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.22);

                    noise.connect(filter);
                    filter.connect(gain);
                    gain.connect(ctx.destination);
                    noise.start();
                } catch(e) {}
            }

            function toggleSound() {
                soundEnabled = !soundEnabled;
                document.getElementById('soundIcon').innerText = soundEnabled ? '🔊' : '🔇';
                localStorage.setItem('epaper_sound', soundEnabled ? '1' : '0');
            }

            // 2. Initialize 3D Physics PageFlip Engine
            function initFlipbook() {
                const container = document.getElementById('flipbook');
                const viewport = document.getElementById('flipbookViewport');
                
                // Calculate dimensions based on viewport height (maintain 1:1.414 broadsheet ratio)
                const vh = viewport.clientHeight || window.innerHeight;
                const vw = viewport.clientWidth || window.innerWidth;
                
                const targetH = Math.min(Math.max(vh - 120, 480), 960);
                const targetW = Math.round(targetH / 1.414);

                try {
                    pageFlip = new St.PageFlip(container, {
                        width: targetW,
                        height: targetH,
                        size: 'stretch',
                        minWidth: 320,
                        maxWidth: 900,
                        minHeight: 450,
                        maxHeight: 1280,
                        maxShadowOpacity: 0.55,
                        showCover: true,
                        mobileScrollSupport: false,
                        flippingTime: 700,
                        usePortrait: true,
                        startPage: 0
                    });

                    pageFlip.loadFromHTML(document.querySelectorAll('.page'));

                    pageFlip.on('flip', (e) => {
                        playPaperSound();
                        updateIndicators(e.data);
                    });

                    pageFlip.on('changeOrientation', (e) => {
                        currentOrientation = e.data;
                        document.getElementById('spreadLabel').innerText = (e.data === 'landscape') ? '2-Page Spread' : 'Single Page';
                    });

                    // Hide hint after 5 seconds
                    setTimeout(() => {
                        const hint = document.getElementById('flipHint');
                        if (hint) hint.style.opacity = '0';
                    }, 5000);

                } catch (err) {
                    console.error('PageFlip init error:', err);
                }
            }

            function updateIndicators(pageIndex) {
                const pageNum = pageIndex + 1;
                const text = `Page ${pageNum} / ${totalPages}`;
                document.getElementById('pageIndicatorText').innerText = text;

                const currData = pagesData[pageIndex] || pagesData[0];
                document.getElementById('bottomPageTitle').innerText = currData.title;

                // Update bottom thumbnails active state
                document.querySelectorAll('.thumb-btn').forEach((btn, i) => {
                    if (i === pageIndex) {
                        btn.classList.add('border-rose-500', 'shadow-lg', 'shadow-rose-500/20', 'scale-105', 'opacity-100');
                        btn.classList.remove('border-slate-700/60', 'opacity-60');
                    } else {
                        btn.classList.remove('border-rose-500', 'shadow-lg', 'shadow-rose-500/20', 'scale-105', 'opacity-100');
                        btn.classList.add('border-slate-700/60', 'opacity-60');
                    }
                });
            }

            function flipNext() {
                if (pageFlip) pageFlip.flipNext();
            }

            function flipPrev() {
                if (pageFlip) pageFlip.flipPrev();
            }

            function goToPage(index) {
                if (pageFlip) pageFlip.flip(index);
            }

            function toggleSpreadMode() {
                if (!pageFlip) return;
                // StPageFlip flips based on container size and usePortrait setting
                const vp = document.getElementById('flipbookViewport');
                vp.classList.toggle('max-w-2xl');
                pageFlip.update();
            }

            function toggleFullscreen() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen().catch(() => {});
                } else {
                    document.exitFullscreen().catch(() => {});
                }
            }

            // 3. Interactive Article Clipping Tool
            let startX, startY, endX, endY, isMouseDown = false;
            const overlay = document.getElementById('clippingOverlay');
            const cropBox = document.getElementById('cropBox');

            function startClipping() {
                isClipping = true;
                overlay.classList.remove('hidden');
            }

            function cancelClipping() {
                isClipping = false;
                overlay.classList.add('hidden');
                cropBox.classList.add('hidden');
            }

            overlay.addEventListener('mousedown', (e) => {
                if (!isClipping) return;
                isMouseDown = true;
                startX = e.clientX;
                startY = e.clientY;
                cropBox.style.left = startX + 'px';
                cropBox.style.top = startY + 'px';
                cropBox.style.width = '0px';
                cropBox.style.height = '0px';
                cropBox.classList.remove('hidden');
            });

            overlay.addEventListener('mousemove', (e) => {
                if (!isMouseDown || !isClipping) return;
                endX = e.clientX;
                endY = e.clientY;

                const left = Math.min(startX, endX);
                const top = Math.min(startY, endY);
                const width = Math.abs(endX - startX);
                const height = Math.abs(endY - startY);

                cropBox.style.left = left + 'px';
                cropBox.style.top = top + 'px';
                cropBox.style.width = width + 'px';
                cropBox.style.height = height + 'px';
            });

            overlay.addEventListener('mouseup', () => {
                if (!isMouseDown) return;
                isMouseDown = false;

                const width = parseInt(cropBox.style.width) || 0;
                const height = parseInt(cropBox.style.height) || 0;

                if (width > 60 && height > 60) {
                    captureClipping();
                } else {
                    cropBox.classList.add('hidden');
                }
            });

            function captureClipping() {
                const boxRect = cropBox.getBoundingClientRect();
                cancelClipping();

                // Find active visible page image
                const activeIndex = pageFlip ? pageFlip.getCurrentPageIndex() : 0;
                const pageData = pagesData[activeIndex] || pagesData[0];
                const activeImg = document.getElementById(`page-img-${pageData.number}`);
                if (!activeImg) return;

                const imgRect = activeImg.getBoundingClientRect();

                // Compute crop coordinates relative to actual rendered image
                const scaleX = activeImg.naturalWidth / imgRect.width;
                const scaleY = activeImg.naturalHeight / imgRect.height;

                const relX = Math.max(0, boxRect.left - imgRect.left) * scaleX;
                const relY = Math.max(0, boxRect.top - imgRect.top) * scaleY;
                const relW = Math.min(boxRect.width * scaleX, activeImg.naturalWidth - relX);
                const relH = Math.min(boxRect.height * scaleY, activeImg.naturalHeight - relY);

                // Create Offscreen Canvas with Newspaper Masthead Watermark
                const bannerHeight = 85;
                const footerHeight = 45;
                const canvas = document.createElement('canvas');
                canvas.width = Math.max(relW, 600);
                canvas.height = relH + bannerHeight + footerHeight;
                const ctx = canvas.getContext('2d');

                // White Paper Background
                ctx.fillStyle = '#FFFFFF';
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // Top Crimson Header Bar
                ctx.fillStyle = '#E11D48';
                ctx.fillRect(0, 0, canvas.width, 36);

                ctx.fillStyle = '#FFFFFF';
                ctx.font = 'bold 16px "Plus Jakarta Sans", sans-serif';
                ctx.fillText('NEWSKIT DIGITAL ARCHIVE • OFFICIAL ARTICLE CLIPPING', 16, 24);

                // Subheader with Date & Page
                ctx.fillStyle = '#F8FAFC';
                ctx.fillRect(0, 36, canvas.width, 49);

                ctx.fillStyle = '#0F172A';
                ctx.font = 'bold 14px "Plus Jakarta Sans", sans-serif';
                ctx.fillText(`{{ $edition->title }} — {{ $edition->formatted_date }}`, 16, 62);

                ctx.fillStyle = '#64748B';
                ctx.font = '12px "Plus Jakarta Sans", sans-serif';
                ctx.fillText(`Section: ${pageData.title}`, 16, 78);

                // Draw Cropped Image Content
                ctx.drawImage(activeImg, relX, relY, relW, relH, 0, bannerHeight, canvas.width, relH);

                // Bottom Footer
                ctx.fillStyle = '#0F172A';
                ctx.fillRect(0, canvas.height - footerHeight, canvas.width, footerHeight);

                ctx.fillStyle = '#E2E8F0';
                ctx.font = '11px "Plus Jakarta Sans", sans-serif';
                ctx.fillText('Verified Digital Broadsheet Clipping • {{ config("app.name") }} Network', 16, canvas.height - 18);
                ctx.fillText('www.newskit.com', canvas.width - 120, canvas.height - 18);

                // Show Modal
                const dataUrl = canvas.toDataURL('image/jpeg', 0.92);
                document.getElementById('clippedImagePreview').src = dataUrl;
                document.getElementById('downloadClipLink').href = dataUrl;
                document.getElementById('clippingModal').classList.remove('hidden');
            }

            function closeClippingModal() {
                document.getElementById('clippingModal').classList.add('hidden');
            }

            function clipAgain() {
                closeClippingModal();
                startClipping();
            }

            function shareClipWhatsApp() {
                const text = encodeURIComponent(`🗞️ Check out this news clipping from *{{ $edition->title }}* ({{ $edition->formatted_date }}) on {{ config('app.name') }}!\n\nRead full digital edition here: ${window.location.href}`);
                window.open(`https://api.whatsapp.com/send?text=${text}`, '_blank');
            }

            // 4. High-Res Zoom Modal
            function openZoomModal() {
                const activeIndex = pageFlip ? pageFlip.getCurrentPageIndex() : 0;
                const pageData = pagesData[activeIndex] || pagesData[0];
                document.getElementById('zoomModalPageTitle').innerText = pageData.title;
                const img = document.getElementById('zoomImage');
                img.src = pageData.url;
                img.style.transform = `scale(${zoomScale})`;
                document.getElementById('zoomModal').classList.remove('hidden');
            }

            function closeZoomModal() {
                document.getElementById('zoomModal').classList.add('hidden');
            }

            function adjustZoom(delta) {
                zoomScale = Math.min(Math.max(zoomScale + delta, 0.75), 3.0);
                document.getElementById('zoomScaleLabel').innerText = `${Math.round(zoomScale * 100)}%`;
                document.getElementById('zoomImage').style.transform = `scale(${zoomScale})`;
            }

            // 5. Global Keyboard Shortcuts
            window.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight' || e.key === ' ') {
                    flipNext();
                } else if (e.key === 'ArrowLeft') {
                    flipPrev();
                } else if (e.key === 'f' || e.key === 'F') {
                    toggleFullscreen();
                } else if (e.key === 's' || e.key === 'S') {
                    toggleSound();
                } else if (e.key === 'c' || e.key === 'C') {
                    if (!isClipping) startClipping();
                } else if (e.key === 'z' || e.key === 'Z') {
                    openZoomModal();
                } else if (e.key === 'Escape') {
                    cancelClipping();
                    closeClippingModal();
                    closeZoomModal();
                }
            });

            // Initialize on window load
            window.addEventListener('DOMContentLoaded', () => {
                const storedSound = localStorage.getItem('epaper_sound');
                if (storedSound !== null) {
                    soundEnabled = (storedSound === '1');
                    document.getElementById('soundIcon').innerText = soundEnabled ? '🔊' : '🔇';
                }
                initFlipbook();
            });

            // Recompute on window resize
            window.addEventListener('resize', () => {
                if (pageFlip) {
                    const viewport = document.getElementById('flipbookViewport');
                    const vh = viewport.clientHeight || window.innerHeight;
                    const targetH = Math.min(Math.max(vh - 120, 480), 960);
                    const targetW = Math.round(targetH / 1.414);
                    pageFlip.update();
                }
            });
        </script>
    @endif
</body>
</html>
