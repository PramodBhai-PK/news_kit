<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mobile App Live Simulator & Inspector | {{ config('app.name', 'NewsKit') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        .phone-frame {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.7), 0 0 0 12px #1e293b, 0 0 0 14px #334155, 0 0 40px rgba(225, 29, 72, 0.15);
        }
        .dynamic-island {
            width: 110px;
            height: 28px;
            background: #000;
            border-radius: 20px;
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 10px;
        }
        .camera-lens {
            width: 10px;
            height: 10px;
            background: #0f172a;
            border: 2px solid #1e293b;
            border-radius: 50%;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col font-sans antialiased overflow-x-hidden selection:bg-rose-500 selection:text-white">

    <!-- Top Navigation Header -->
    <header class="h-16 border-b border-slate-800 bg-slate-900/90 backdrop-blur-md px-6 flex items-center justify-between z-30 sticky top-0">
        <div class="flex items-center gap-4">
            <a href="/" class="flex items-center gap-2 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-rose-600 to-amber-500 flex items-center justify-center font-bold text-white shadow-lg shadow-rose-600/30">
                    NK
                </div>
                <div>
                    <div class="text-sm font-bold tracking-tight text-white group-hover:text-rose-400 transition-colors">NewsKit Mobile Simulator</div>
                    <div class="text-xs text-slate-400">Flutter Web & API Debugging Environment</div>
                </div>
            </a>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                API Connected (Localhost:8000)
            </span>
        </div>

        <!-- Simulator Controls -->
        <div class="flex items-center gap-3">
            <div class="flex items-center bg-slate-800/80 border border-slate-700/80 rounded-xl p-1 text-xs font-medium">
                <button onclick="setDevice(393, 852, 'iPhone 15 Pro')" id="btn-iphone" class="px-3 py-1.5 rounded-lg bg-rose-600 text-white font-semibold transition-all">
                    iPhone 15 Pro
                </button>
                <button onclick="setDevice(412, 915, 'Galaxy S24')" id="btn-galaxy" class="px-3 py-1.5 rounded-lg text-slate-300 hover:text-white transition-all">
                    Galaxy S24
                </button>
                <button onclick="setDevice(412, 892, 'Pixel 8')" id="btn-pixel" class="px-3 py-1.5 rounded-lg text-slate-300 hover:text-white transition-all">
                    Pixel 8
                </button>
            </div>

            <button onclick="reloadFrame()" title="Reload Mobile App" class="p-2 rounded-xl bg-slate-800 border border-slate-700 text-slate-300 hover:text-white hover:bg-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>

            <a href="/app/" target="_blank" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 text-white text-xs font-bold hover:from-rose-500 hover:to-rose-600 shadow-lg shadow-rose-600/30 transition">
                <span>Standalone Tab (Inspect Element)</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
    </header>

    <!-- Main Workspace -->
    <main class="flex-1 flex flex-col lg:flex-row items-center justify-center p-6 gap-8 max-w-7xl mx-auto w-full">
        <!-- Phone Simulator Container -->
        <div class="flex flex-col items-center justify-center">
            <div class="relative phone-frame rounded-[46px] overflow-hidden bg-black border-4 border-slate-800" id="phoneContainer" style="width: 393px; height: 840px;">
                <!-- Dynamic Island / Top Notch -->
                <div class="dynamic-island pointer-events-none">
                    <div class="camera-lens"></div>
                    <div class="w-2.5 h-2.5 rounded-full bg-slate-900 border border-slate-800"></div>
                </div>

                <!-- Flutter App Web Iframe -->
                <iframe id="appFrame" src="/app/" class="w-full h-full border-0 bg-white" title="Flutter Web App Viewer"></iframe>
            </div>

            <!-- Device Dimension Badge -->
            <div class="mt-4 text-xs font-mono text-slate-400 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                <span id="currentDeviceLabel">iPhone 15 Pro (393 × 840 px)</span>
            </div>
        </div>

        <!-- DevTools & Inspection Helper Panel -->
        <div class="w-full lg:w-96 flex flex-col gap-5 self-center">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-rose-600/20 text-rose-400 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white">How to Inspect in Chrome</h3>
                        <p class="text-xs text-slate-400">Step-by-step developer guide</p>
                    </div>
                </div>

                <div class="space-y-3 text-xs text-slate-300">
                    <div class="flex items-start gap-2.5 p-2.5 rounded-xl bg-slate-800/60 border border-slate-700/60">
                        <span class="w-5 h-5 rounded-full bg-slate-700 flex items-center justify-center font-bold text-white flex-shrink-0 text-[10px]">1</span>
                        <div>Click <strong class="text-rose-400">Standalone Tab</strong> button above to open <code class="px-1.5 py-0.5 rounded bg-slate-950 font-mono text-rose-300">/app/</code> in a new Chrome window.</div>
                    </div>
                    <div class="flex items-start gap-2.5 p-2.5 rounded-xl bg-slate-800/60 border border-slate-700/60">
                        <span class="w-5 h-5 rounded-full bg-slate-700 flex items-center justify-center font-bold text-white flex-shrink-0 text-[10px]">2</span>
                        <div>Press <kbd class="px-1.5 py-0.5 rounded bg-slate-950 font-mono text-amber-300 border border-slate-700">F12</kbd> or <kbd class="px-1.5 py-0.5 rounded bg-slate-950 font-mono text-amber-300 border border-slate-700">Ctrl + Shift + I</kbd> to open Chrome Developer Tools.</div>
                    </div>
                    <div class="flex items-start gap-2.5 p-2.5 rounded-xl bg-slate-800/60 border border-slate-700/60">
                        <span class="w-5 h-5 rounded-full bg-slate-700 flex items-center justify-center font-bold text-white flex-shrink-0 text-[10px]">3</span>
                        <div>Press <kbd class="px-1.5 py-0.5 rounded bg-slate-950 font-mono text-emerald-300 border border-slate-700">Ctrl + Shift + M</kbd> to toggle the Device Toolbar (Mobile View).</div>
                    </div>
                    <div class="flex items-start gap-2.5 p-2.5 rounded-xl bg-slate-800/60 border border-slate-700/60">
                        <span class="w-5 h-5 rounded-full bg-slate-700 flex items-center justify-center font-bold text-white flex-shrink-0 text-[10px]">4</span>
                        <div>Select any smartphone model (iPhone 14 Pro, Samsung S20, Pixel 7) to inspect responsive layout, styles, and live API Network traffic.</div>
                    </div>
                </div>
            </div>

            <!-- API Endpoints Quick Reference -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Live Connected Endpoints</h4>
                <div class="space-y-2 text-xs font-mono">
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-950/60 border border-slate-800">
                        <span class="text-slate-400">Articles Feed:</span>
                        <a href="/api/v1/articles" target="_blank" class="text-rose-400 hover:underline">/api/v1/articles</a>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-950/60 border border-slate-800">
                        <span class="text-slate-400">Categories:</span>
                        <a href="/api/v1/categories" target="_blank" class="text-rose-400 hover:underline">/api/v1/categories</a>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-950/60 border border-slate-800">
                        <span class="text-slate-400">Portal Branding:</span>
                        <a href="/api/v1/config" target="_blank" class="text-rose-400 hover:underline">/api/v1/config</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function setDevice(w, h, name) {
            const container = document.getElementById('phoneContainer');
            container.style.width = w + 'px';
            container.style.height = h + 'px';
            document.getElementById('currentDeviceLabel').innerText = name + ' (' + w + ' × ' + h + ' px)';

            const buttons = ['btn-iphone', 'btn-galaxy', 'btn-pixel'];
            buttons.forEach(id => {
                const btn = document.getElementById(id);
                if (btn) {
                    btn.className = 'px-3 py-1.5 rounded-lg text-slate-300 hover:text-white transition-all';
                }
            });

            if (name.includes('iPhone')) {
                document.getElementById('btn-iphone').className = 'px-3 py-1.5 rounded-lg bg-rose-600 text-white font-semibold transition-all';
            } else if (name.includes('Galaxy')) {
                document.getElementById('btn-galaxy').className = 'px-3 py-1.5 rounded-lg bg-rose-600 text-white font-semibold transition-all';
            } else if (name.includes('Pixel')) {
                document.getElementById('btn-pixel').className = 'px-3 py-1.5 rounded-lg bg-rose-600 text-white font-semibold transition-all';
            }
        }

        function reloadFrame() {
            const frame = document.getElementById('appFrame');
            frame.src = frame.src;
        }
    </script>
</body>
</html>
