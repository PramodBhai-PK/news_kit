<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $edition ? $edition->title . ' - ' . $edition->formatted_date : 'Digital E-Paper' }} | {{ config('app.name', 'NewsKit') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .page-container {
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top center;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col antialiased selection:bg-rose-500 selection:text-white overflow-x-hidden">

    <!-- Top Header Bar -->
    <header class="h-16 border-b border-slate-800 bg-slate-900/95 backdrop-blur-md px-4 sm:px-6 flex items-center justify-between sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <a href="/" class="flex items-center gap-2 group">
                <div class="w-8 h-8 rounded-lg bg-rose-600 flex items-center justify-center font-black text-white text-xs shadow-md shadow-rose-600/30">
                    NK
                </div>
                <span class="font-extrabold text-sm tracking-tight text-white group-hover:text-rose-400 transition-colors hidden sm:inline">
                    {{ config('app.name', 'NewsKit') }} E-Paper
                </span>
            </a>

            @if($edition)
                <div class="h-5 w-px bg-slate-800 hidden sm:block"></div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-300 hidden md:inline">{{ $edition->title }}</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-600/10 text-rose-400 border border-rose-500/20">
                        {{ $edition->formatted_date }}
                    </span>
                </div>
            @endif
        </div>

        <!-- Controls: Date Picker, Zoom, PDF Download -->
        <div class="flex items-center gap-2 sm:gap-3">
            <!-- Date Archive Selector -->
            @if($recentEditions->isNotEmpty())
                <div class="relative">
                    <select onchange="window.location.href='/epaper/' + this.value"
                            class="bg-slate-800 border border-slate-700 text-slate-200 text-xs rounded-xl px-3 py-1.5 font-medium outline-none cursor-pointer hover:border-slate-600">
                        @foreach($recentEditions as $recent)
                            <option value="{{ $recent->edition_date->format('Y-m-d') }}" {{ ($edition && $edition->id === $recent->id) ? 'selected' : '' }}>
                                📅 {{ $recent->formatted_date }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Zoom Controls -->
            <div class="hidden sm:flex items-center bg-slate-800 border border-slate-700 rounded-xl p-1 text-xs">
                <button onclick="zoomOut()" title="Zoom Out" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                </button>
                <span id="zoomLabel" class="px-2 font-mono text-[11px] text-slate-400">100%</span>
                <button onclick="zoomIn()" title="Zoom In" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </button>
                <button onclick="resetZoom()" title="Reset Zoom" class="px-2 py-1 text-slate-400 hover:text-white text-[10px] font-bold">
                    RESET
                </button>
            </div>

            <!-- PDF Download -->
            @if($edition && $edition->pdf_file)
                <a href="{{ route('portal.epaper.download', $edition) }}"
                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 text-white text-xs font-bold hover:from-rose-500 hover:to-rose-600 shadow-md transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span class="hidden sm:inline">Download PDF</span>
                </a>
            @endif
        </div>
    </header>

    @if(!$edition)
        <main class="flex-1 flex flex-col items-center justify-center p-8 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-500 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
            <h2 class="text-lg font-bold text-white mb-2">No E-Paper Edition Published</h2>
            <p class="text-xs text-slate-400 max-w-sm mb-6">There is no digital newspaper edition published for this date yet. Please check back later or select another date.</p>
            <a href="/" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-xl transition">
                Return to News Portal
            </a>
        </main>
    @else
        <!-- Main Interactive Flipbook Reader Area -->
        <main class="flex-1 flex flex-col items-center justify-start p-4 sm:p-6 overflow-auto relative">
            <!-- Navigation Arrow Left -->
            <button onclick="prevPage()" id="prevBtn" title="Previous Page"
                    class="fixed left-4 top-1/2 -translate-y-1/2 z-30 p-3 rounded-2xl bg-slate-900/90 border border-slate-800 text-white hover:bg-rose-600 shadow-xl transition-all hidden sm:block">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </button>

            <!-- Navigation Arrow Right -->
            <button onclick="nextPage()" id="nextBtn" title="Next Page"
                    class="fixed right-4 top-1/2 -translate-y-1/2 z-30 p-3 rounded-2xl bg-slate-900/90 border border-slate-800 text-white hover:bg-rose-600 shadow-xl transition-all hidden sm:block">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>

            <!-- Page Meta & Pagination Header -->
            <div class="mb-4 flex items-center justify-between w-full max-w-4xl px-2">
                <div class="flex items-center gap-2">
                    <span id="pageTitleLabel" class="text-xs font-bold text-slate-300">
                        {{ $edition->pages->first()?->page_title ?: 'Page 1' }}
                    </span>
                </div>
                <div class="flex items-center gap-2 bg-slate-900 border border-slate-800 px-3 py-1 rounded-full text-xs font-mono font-bold text-rose-400">
                    <span id="currentPageNum">1</span> / <span>{{ $edition->pages->count() }}</span>
                </div>
            </div>

            <!-- Page Canvas Container -->
            <div class="w-full max-w-4xl flex justify-center pb-24">
                <div id="pageContainer" class="page-container rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 shadow-2xl">
                    @foreach($edition->pages as $index => $page)
                        <div id="page-item-{{ $index + 1 }}" class="page-view {{ $index === 0 ? 'block' : 'hidden' }}">
                            <img src="{{ $page->page_image }}" alt="{{ $page->page_title }}" class="w-full h-auto object-contain max-h-[85vh] select-none pointer-events-auto">
                        </div>
                    @endforeach
                </div>
            </div>
        </main>

        <!-- Bottom Page Thumbnails Strip -->
        <footer class="fixed bottom-0 left-0 right-0 h-20 bg-slate-900/95 border-t border-slate-800 backdrop-blur-md px-4 flex items-center justify-center z-40">
            <div class="flex items-center gap-3 overflow-x-auto max-w-4xl py-2 px-2 scrollbar-none">
                @foreach($edition->pages as $index => $page)
                    <button onclick="goToPage({{ $index + 1 }})" id="thumb-btn-{{ $index + 1 }}"
                            class="thumb-btn shrink-0 flex items-center gap-2 px-3 py-1.5 rounded-xl border text-xs font-bold transition-all {{ $index === 0 ? 'border-rose-500 bg-rose-500/10 text-rose-400 shadow-md' : 'border-slate-800 bg-slate-950 text-slate-400 hover:border-slate-700' }}">
                        <span class="w-5 h-5 rounded-md bg-slate-800 flex items-center justify-center text-[10px] font-mono">{{ $index + 1 }}</span>
                        <span class="truncate max-w-[100px]">{{ $page->page_title ?: 'Page ' . ($index + 1) }}</span>
                    </button>
                @endforeach
            </div>
        </footer>

        <script>
            let currentPage = 1;
            const totalPages = {{ $edition->pages->count() }};
            let zoomLevel = 1.0;

            const pageTitles = {
                @foreach($edition->pages as $index => $page)
                    {{ $index + 1 }}: "{{ addslashes($page->page_title ?: 'Page ' . ($index + 1)) }}",
                @endforeach
            };

            function updateUI() {
                // Update page visibility
                for (let i = 1; i <= totalPages; i++) {
                    const el = document.getElementById('page-item-' + i);
                    const thumb = document.getElementById('thumb-btn-' + i);
                    if (el) el.className = (i === currentPage) ? 'page-view block' : 'page-view hidden';
                    if (thumb) {
                        thumb.className = (i === currentPage)
                            ? 'thumb-btn shrink-0 flex items-center gap-2 px-3 py-1.5 rounded-xl border text-xs font-bold transition-all border-rose-500 bg-rose-500/10 text-rose-400 shadow-md'
                            : 'thumb-btn shrink-0 flex items-center gap-2 px-3 py-1.5 rounded-xl border text-xs font-bold transition-all border-slate-800 bg-slate-950 text-slate-400 hover:border-slate-700';
                    }
                }

                document.getElementById('currentPageNum').innerText = currentPage;
                document.getElementById('pageTitleLabel').innerText = pageTitles[currentPage] || ('Page ' + currentPage);

                // Update prev/next button states
                const prev = document.getElementById('prevBtn');
                const next = document.getElementById('nextBtn');
                if (prev) prev.style.opacity = currentPage === 1 ? '0.4' : '1';
                if (next) next.style.opacity = currentPage === totalPages ? '0.4' : '1';

                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            function goToPage(page) {
                if (page < 1 || page > totalPages) return;
                currentPage = page;
                updateUI();
            }

            function prevPage() {
                if (currentPage > 1) {
                    currentPage--;
                    updateUI();
                }
            }

            function nextPage() {
                if (currentPage < totalPages) {
                    currentPage++;
                    updateUI();
                }
            }

            function zoomIn() {
                if (zoomLevel < 2.0) {
                    zoomLevel += 0.2;
                    applyZoom();
                }
            }

            function zoomOut() {
                if (zoomLevel > 0.8) {
                    zoomLevel -= 0.2;
                    applyZoom();
                }
            }

            function resetZoom() {
                zoomLevel = 1.0;
                applyZoom();
            }

            function applyZoom() {
                const container = document.getElementById('pageContainer');
                if (container) {
                    container.style.transform = 'scale(' + zoomLevel + ')';
                }
                const label = document.getElementById('zoomLabel');
                if (label) {
                    label.innerText = Math.round(zoomLevel * 100) + '%';
                }
            }

            // Keyboard Arrow navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') prevPage();
                if (e.key === 'ArrowRight') nextPage();
            });
        </script>
    @endif
</body>
</html>
