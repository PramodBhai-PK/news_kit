@extends('layouts.portal')

@section('title', 'Download NewsKit Mobile App (Android APK)')

@section('content')
<div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-[75vh] flex items-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-8 sm:p-12 text-center">
                <!-- App Icon Badge -->
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-rose-500 to-rose-700 text-white font-extrabold text-3xl shadow-lg shadow-rose-500/30 mb-6">
                    NK
                </div>

                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                    Get NewsKit on Your Android Phone
                </h1>
                <p class="mt-3 text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto">
                    Experience lightning-fast breaking news, audio text-to-speech reader, hyperlocal city updates, and offline bookmarks directly on your mobile device.
                </p>

                <!-- Actions & QR Code Grid -->
                <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-8 items-center bg-slate-50 dark:bg-slate-900/60 p-6 sm:p-8 rounded-2xl border border-slate-100 dark:border-slate-800">
                    <!-- Direct Download Column -->
                    <div class="text-left space-y-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                            Latest Release v1.0.0
                        </span>

                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">
                            Direct APK Download
                        </h3>

                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Download the standalone release APK file directly to your phone. Compatible with Android 5.0 (Lollipop) and higher.
                            @if($fileSizeMb)
                                <span class="font-medium text-slate-700 dark:text-slate-200">({{ $fileSizeMb }} MB)</span>
                            @endif
                        </p>

                        <div class="pt-2 flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('portal.download-apk') }}" class="inline-flex items-center justify-center px-6 py-3.5 border border-transparent text-base font-bold rounded-xl shadow-md text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-all hover:scale-[1.02]">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download APK
                            </a>

                            <a href="{{ $githubReleaseUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center px-5 py-3.5 border border-slate-300 dark:border-slate-600 text-base font-semibold rounded-xl text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                <svg class="w-5 h-5 mr-2 text-slate-800 dark:text-slate-200" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                                </svg>
                                GitHub Mirror
                            </a>
                        </div>
                    </div>

                    <!-- QR Code Column -->
                    <div class="flex flex-col items-center justify-center p-4 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
                        <img 
                            src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode(url()->current()) }}" 
                            alt="Scan QR to open on phone" 
                            class="w-40 h-40 rounded-lg p-1 bg-white border border-slate-200 shadow-sm"
                            loading="lazy"
                        >
                        <span class="mt-3 text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Scan with phone camera to open on mobile
                        </span>
                    </div>
                </div>

                <!-- Installation Instructions -->
                <div class="mt-8 text-left border-t border-slate-100 dark:border-slate-700 pt-6">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">
                        How to install on Android
                    </h4>
                    <ol class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs text-slate-600 dark:text-slate-400">
                        <li class="p-3 bg-slate-50 dark:bg-slate-900/40 rounded-lg">
                            <strong class="block text-slate-800 dark:text-slate-200 mb-1">1. Download APK</strong>
                            Tap the Download button or scan the QR code from your mobile browser.
                        </li>
                        <li class="p-3 bg-slate-50 dark:bg-slate-900/40 rounded-lg">
                            <strong class="block text-slate-800 dark:text-slate-200 mb-1">2. Allow Unknown Sources</strong>
                            When prompted by Android, tap "Settings" and toggle "Allow from this source".
                        </li>
                        <li class="p-3 bg-slate-50 dark:bg-slate-900/40 rounded-lg">
                            <strong class="block text-slate-800 dark:text-slate-200 mb-1">3. Install & Enjoy</strong>
                            Tap "Install" then "Open". Connect to local WiFi to sync live news!
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
