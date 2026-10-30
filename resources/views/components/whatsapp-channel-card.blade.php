@php
    $channelUrl = setting('social_whatsapp', 'https://whatsapp.com/channel/newskit');
    $qrUrl = setting('whatsapp_qr_url');
    if (!$qrUrl) {
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&margin=3&data=' . urlencode($channelUrl);
    }
    $subscriberCount = setting('whatsapp_subscribers_count', '50,000+');
@endphp

<div class="rounded-3xl p-5 sm:p-6 bg-gradient-to-br from-emerald-950 via-slate-900 to-slate-950 text-white border border-emerald-700/40 shadow-xl relative overflow-hidden group space-y-4">
    <!-- Ambient Green Glow Background Effect -->
    <div class="absolute -right-12 -top-12 w-36 h-36 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-emerald-500/20 transition-all duration-500"></div>

    <!-- Header & Badge -->
    <div class="relative z-10 flex items-start justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/30 shrink-0">
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                    <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.81 13.47 3.81 11.91C3.81 7.37 7.5 3.67 12.05 3.67Z"/>
                </svg>
            </div>
            <div>
                <h4 class="font-black text-sm text-white flex items-center gap-1.5">
                    <span>{{ setting('whatsapp_channel_title') ? __(setting('whatsapp_channel_title')) : __('व्हाट्सएप चैनल से जुड़ें') }}</span>
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 live-pulse"></span>
                </h4>
                <p class="text-[11px] text-slate-300 font-medium line-clamp-2 mt-0.5">
                    {{ setting('whatsapp_channel_desc') ? __(setting('whatsapp_channel_desc')) : __('ताज़ा व ब्रेकिंग ख़बरें सीधे अपने व्हाट्सएप पर पाने के लिए चैनल फॉलो करें।') }}
                </p>
            </div>
        </div>
    </div>

    <!-- QR Code Centerpiece Box (Clean High Contrast White Surface) -->
    <div class="relative z-10 flex flex-col items-center justify-center p-3 rounded-2xl bg-white/95 text-slate-900 border border-slate-200/80 shadow-md transition-transform duration-300 group-hover:scale-101">
        <div class="relative">
            <img src="{{ $qrUrl }}" 
                 alt="WhatsApp Channel QR Code" 
                 loading="lazy" 
                 class="w-36 h-36 sm:w-40 sm:h-40 object-contain rounded-xl mix-blend-multiply">
            <!-- Center WhatsApp watermark mini-icon -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center p-1.5 shadow-md border-2 border-white">
                    <svg class="w-full h-full fill-current" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.81 13.47 3.81 11.91C3.81 7.37 7.5 3.67 12.05 3.67Z"/></svg>
                </div>
            </div>
        </div>

        <span class="text-[11px] font-bold text-slate-700 tracking-wide mt-2 flex items-center gap-1.5">
            <span>📷</span>
            <span>{{ __('📱 मोबाइल से QR स्कैन करें') }}</span>
        </span>
    </div>

    <!-- Direct Click Button for Mobile / Instant Navigation -->
    <div class="relative z-10 space-y-2">
        <a href="{{ $channelUrl }}" 
           target="_blank" 
           rel="noopener noreferrer" 
           class="w-full py-3 px-4 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/25 transition-all group/btn">
            <svg class="w-4 h-4 fill-current transition-transform group-hover/btn:scale-110" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.81 13.47 3.81 11.91C3.81 7.37 7.5 3.67 12.05 3.67Z"/></svg>
            <span>{{ __('व्हाट्सएप पर फॉलो करें') }}</span>
            <span class="text-sm font-bold transition-transform group-hover/btn:translate-x-1">→</span>
        </a>

        <!-- Subscriber Count Badge & Trust Marker -->
        <div class="flex items-center justify-between text-[11px] text-slate-400 px-1 pt-1 border-t border-slate-800">
            <span class="flex items-center gap-1.5 text-emerald-400 font-semibold">
                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ $subscriberCount }} {{ __('पाठक जुड़े हैं') }}</span>
            </span>
            <span class="text-slate-500 font-mono text-[10px] uppercase">Official</span>
        </div>
    </div>
</div>
