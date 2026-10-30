@extends('layouts.admin')

@section('title', 'ब्रांडिंग, लोगो व साइट नाम सेटिंग्स')
@section('page_title', 'ब्रांडिंग, लोगो व साइट नाम सेटिंग्स (Central Control Hub)')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    <!-- Top Notice Banner -->
    <div class="p-5 rounded-2xl brand-gradient text-white shadow-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black">सिंगल-पेज ब्रांडिंग व कस्टमाइजेशन हब</h2>
            <p class="text-xs text-rose-100 mt-1 max-w-2xl leading-relaxed">
                यहाँ से आप पूरी वेबसाइट का नाम, मुख्य लोगो, फ़ेविकॉन, प्राइमरी व एक्सेंट थीम कलर्स, और सोशल लिंक्स एक ही जगह से बदल सकते हैं। सेव करते ही पूरा CMS और लाइव न्यूज़ पोर्टल तुरंत नए रूप में अपडेट हो जाता है!
            </p>
        </div>
        <div class="shrink-0">
            <span class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-md px-3 py-1.5 rounded-full text-xs font-bold">
                <span class="w-2 h-2 rounded-full bg-emerald-400 live-pulse"></span>
                <span>Live Dynamic Theming</span>
            </span>
        </div>
    </div>

    <!-- Live Preview Card (Powered by TypeScript) -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm" id="preview-card">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="p-1.5 rounded-lg bg-amber-50 text-amber-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </span>
                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">रियल-टाइम ब्रांडिंग प्रीव्यू (Live Real-Time Preview)</span>
            </div>
            <span class="text-[11px] text-slate-500 font-medium">नीचे फ़ॉर्म में टाइप करते ही यहाँ बदलाव दिखेगा</span>
        </div>

        <div class="p-5 rounded-xl bg-slate-950 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-inner">
            <div class="flex items-center gap-4">
                <img id="preview-logo-img" src="{{ setting('logo_url', '/images/logo.svg') }}" alt="Logo" class="h-10 w-auto object-contain">
                <div>
                    <h3 id="preview-site-name" class="text-xl font-black tracking-tight text-white leading-tight">
                        {{ setting('site_name', 'The LallanTop News') }}
                    </h3>
                    <p id="preview-site-tagline" class="text-xs text-amber-400 font-medium mt-0.5">
                        {{ setting('site_tagline', 'बेबाक, सटीक और सबसे तेज़ ख़बरें') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div id="preview-badge" class="px-3 py-1 rounded-full text-xs font-bold text-white shadow-sm" style="background-color: {{ setting('primary_color', '#E11D48') }};">
                    प्राइमरी कलर
                </div>
                <div class="px-3 py-1 rounded-full text-xs font-bold text-slate-900 bg-amber-400 shadow-sm">
                    एक्सेंट कलर
                </div>
            </div>
        </div>
    </div>

    <!-- The Master Settings Form -->
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- 1. Site Identity & Naming -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                <span class="p-2 rounded-xl bg-rose-50 text-brand-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </span>
                <div>
                    <h3 class="font-bold text-slate-900 text-base">१. साइट पहचान व नाम (Site Identity & Naming)</h3>
                    <p class="text-xs text-slate-500">यहाँ बदला गया नाम पूरे पोर्टल, हेडर, फुटर और एडमिन पैनल में लागू होगा।</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="input_site_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        वेबसाइट का मुख्य नाम (Site Name) *
                    </label>
                    <input type="text" id="input_site_name" name="site_name" required
                           value="{{ $settings['site_name'] ?? setting('site_name') }}"
                           class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary focus:bg-white outline-none transition-all font-semibold">
                </div>

                <div>
                    <label for="input_site_tagline" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        साइट टैगलाइन / स्लोगन (Tagline) *
                    </label>
                    <input type="text" id="input_site_tagline" name="site_tagline" required
                           value="{{ $settings['site_tagline'] ?? setting('site_tagline') }}"
                           class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary focus:bg-white outline-none transition-all font-semibold">
                </div>

                <div>
                    <label for="input_site_short_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        शॉर्ट नेम / बैज कोड (Short Name)
                    </label>
                    <input type="text" id="input_site_short_name" name="site_short_name"
                           value="{{ $settings['site_short_name'] ?? setting('site_short_name', 'LT') }}"
                           class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary focus:bg-white outline-none transition-all">
                </div>
            </div>
        </div>

        <!-- 2. Logo & Favicon -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                <span class="p-2 rounded-xl bg-blue-50 text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
                <div>
                    <h3 class="font-bold text-slate-900 text-base">२. मुख्य लोगो व फ़ेविकॉन (Logo & Favicon)</h3>
                    <p class="text-xs text-slate-500">आप URL दे सकते हैं या सीधे अपने कंप्यूटर से इमेज/SVG फ़ाइल अपलोड कर सकते हैं।</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Logo -->
                <div class="space-y-3">
                    <label for="input_logo_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        लोगो URL (Logo URL / Path)
                    </label>
                    <input type="text" id="input_logo_url" name="logo_url"
                           value="{{ $settings['logo_url'] ?? setting('logo_url') }}"
                           class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary focus:bg-white outline-none transition-all">
                    
                    <div>
                        <span class="block text-xs text-slate-500 mb-1.5 font-medium">या नया लोगो फ़ाइल अपलोड करें:</span>
                        <input type="file" name="logo_file" accept="image/*"
                               class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>
                </div>

                <!-- Favicon -->
                <div class="space-y-3">
                    <label for="input_favicon_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        फ़ेविकॉन URL (Favicon URL)
                    </label>
                    <input type="text" id="input_favicon_url" name="favicon_url"
                           value="{{ $settings['favicon_url'] ?? setting('favicon_url') }}"
                           class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary focus:bg-white outline-none transition-all">
                    
                    <div>
                        <span class="block text-xs text-slate-500 mb-1.5 font-medium">या नया फ़ेविकॉन फ़ाइल अपलोड करें:</span>
                        <input type="file" name="favicon_file" accept="image/*"
                               class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Dynamic Theme Colors -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                <span class="p-2 rounded-xl bg-purple-50 text-purple-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4 4.5 4.5 0 011.5-3.322l11-11a2.121 2.121 0 013 3L6.828 17.172A4.5 4.5 0 013 21z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7l2 2"/></svg>
                </span>
                <div>
                    <h3 class="font-bold text-slate-900 text-base">३. थीम कलर्स व सौंदर्य (Color Palette)</h3>
                    <p class="text-xs text-slate-500">रंग चुनते ही बटन्स, हेडर्स, लाइव टिकर, और बॉर्डर कलर्स तुरंत उस रंग में ढल जाते हैं।</p>
                </div>
            </div>

            <!-- Theme Studio Callout Banner -->
            <div class="p-4 rounded-xl bg-gradient-to-r from-rose-50 to-amber-50 border border-rose-200/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <span class="text-lg">🎨</span>
                    <div>
                        <span class="block text-xs font-bold text-slate-900">नया लाइव थीम स्टूडियो (Live Interactive Theme Studio)</span>
                        <span class="block text-[11px] text-slate-500">लाइव न्यूज़ पोर्टल के साथ रियल-टाइम रंग बदलकर देखें व सीधे पब्लिश करें।</span>
                    </div>
                </div>
                <a href="{{ route('admin.theme.index') }}" class="px-4 py-2 rounded-xl bg-brand-primary hover:brightness-110 text-white font-bold text-xs shadow-sm transition-all shrink-0 flex items-center gap-1.5">
                    <span>थीम स्टूडियो खोलें</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Primary Color -->
                <div>
                    <label for="input_primary_color" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        प्राइमरी ब्रांड कलर (Primary Color)
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="color" id="input_primary_color" name="primary_color"
                               value="{{ $settings['primary_color'] ?? setting('primary_color', '#E11D48') }}"
                               class="w-12 h-12 rounded-xl border-0 p-1 cursor-pointer bg-slate-50">
                        <input type="text" readonly
                               value="{{ $settings['primary_color'] ?? setting('primary_color', '#E11D48') }}"
                               class="flex-1 bg-slate-50 text-slate-900 text-sm font-mono rounded-xl px-4 py-2.5 border border-slate-200">
                    </div>
                    <span class="block text-[11px] text-slate-400 mt-1">LallanTop बोल्ड रेड: #E11D48, AajTak रेड: #DC2626</span>
                </div>

                <!-- Accent Color -->
                <div>
                    <label for="input_accent_color" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        एक्सेंट / हाइलाइट कलर (Accent Color)
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="color" id="input_accent_color" name="accent_color"
                               value="{{ $settings['accent_color'] ?? setting('accent_color', '#F59E0B') }}"
                               class="w-12 h-12 rounded-xl border-0 p-1 cursor-pointer bg-slate-50">
                        <input type="text" readonly
                               value="{{ $settings['accent_color'] ?? setting('accent_color', '#F59E0B') }}"
                               class="flex-1 bg-slate-50 text-slate-900 text-sm font-mono rounded-xl px-4 py-2.5 border border-slate-200">
                    </div>
                    <span class="block text-[11px] text-slate-400 mt-1">गोल्ड/अंबर: #F59E0B, ऑरेंज: #EA580C</span>
                </div>
            </div>
        </div>

        <!-- 4. Portal Features & Breaking Ticker -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                <span class="p-2 rounded-xl bg-amber-50 text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </span>
                <div>
                    <h3 class="font-bold text-slate-900 text-base">४. ब्रेकिंग न्यूज़ टिकर व पोर्टल फ़ीचर्स</h3>
                    <p class="text-xs text-slate-500">लाइव टिकर और फीचर्स को नियंत्रित करें।</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="breaking_ticker_title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        टिकर हेडिंग (Ticker Headline)
                    </label>
                    <input type="text" id="breaking_ticker_title" name="breaking_ticker_title"
                           value="{{ $settings['breaking_ticker_title'] ?? setting('breaking_ticker_title', 'ताज़ा ख़बरें') }}"
                           class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="breaking_ticker_enabled" value="1" 
                               {{ ($settings['breaking_ticker_enabled'] ?? setting('breaking_ticker_enabled')) ? 'checked' : '' }}
                               class="w-5 h-5 text-brand-primary rounded border-slate-300 focus:ring-0">
                        <span class="text-sm font-bold text-slate-800">ब्रेकिंग न्यूज़ टिकर एक्टिवेट रखें (Enable Ticker)</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- 5. Contact & Social Channels -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </span>
                <div>
                    <h3 class="font-bold text-slate-900 text-base">५. सम्पर्क व सोशल मीडिया चैनल्स</h3>
                    <p class="text-xs text-slate-500">फुटर और हेडर में प्रदर्शित होने वाली आधिकारिक जानकारी।</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">सम्पर्क ईमेल</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? setting('contact_email') }}" class="w-full bg-slate-50 text-sm rounded-xl px-4 py-2.5 border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">फ़ोन नंबर</label>
                    <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? setting('contact_phone') }}" class="w-full bg-slate-50 text-sm rounded-xl px-4 py-2.5 border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">कार्यालय पता</label>
                    <input type="text" name="office_address" value="{{ $settings['office_address'] ?? setting('office_address', 'नई दिल्ली') }}" class="w-full bg-slate-50 text-sm rounded-xl px-4 py-2.5 border border-slate-200">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Facebook Page Link</label>
                    <input type="url" name="social_facebook" value="{{ $settings['social_facebook'] ?? setting('social_facebook', 'https://facebook.com/newskit') }}" class="w-full bg-slate-50 text-sm rounded-xl px-4 py-2.5 border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Instagram Profile Link</label>
                    <input type="url" name="social_instagram" value="{{ $settings['social_instagram'] ?? setting('social_instagram', 'https://instagram.com/newskit') }}" class="w-full bg-slate-50 text-sm rounded-xl px-4 py-2.5 border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">X (Twitter) Link</label>
                    <input type="url" name="social_twitter" value="{{ $settings['social_twitter'] ?? setting('social_twitter') }}" class="w-full bg-slate-50 text-sm rounded-xl px-4 py-2.5 border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">YouTube Channel</label>
                    <input type="url" name="social_youtube" value="{{ $settings['social_youtube'] ?? setting('social_youtube') }}" class="w-full bg-slate-50 text-sm rounded-xl px-4 py-2.5 border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Telegram Channel Link</label>
                    <input type="url" name="social_telegram" value="{{ $settings['social_telegram'] ?? setting('social_telegram', 'https://t.me/newskit') }}" class="w-full bg-slate-50 text-sm rounded-xl px-4 py-2.5 border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">LinkedIn Page Link</label>
                    <input type="url" name="social_linkedin" value="{{ $settings['social_linkedin'] ?? setting('social_linkedin', 'https://linkedin.com/company/newskit') }}" class="w-full bg-slate-50 text-sm rounded-xl px-4 py-2.5 border border-slate-200">
                </div>
            </div>

            <!-- WhatsApp Channel & QR Code Customization Block -->
            <div class="pt-6 border-t border-slate-200/80 space-y-4">
                <div class="flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-500 text-white flex items-center justify-center">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.81 13.47 3.81 11.91C3.81 7.37 7.5 3.67 12.05 3.67Z"/></svg>
                    </span>
                    <h4 class="font-bold text-sm text-slate-800">व्हाट्सएप चैनल एवं क्यूआर कोड सेटिंग्स (WhatsApp Channel & QR Code)</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">WhatsApp Channel URL *</label>
                        <input type="url" name="social_whatsapp" value="{{ $settings['social_whatsapp'] ?? setting('social_whatsapp', 'https://whatsapp.com/channel/newskit') }}" placeholder="https://whatsapp.com/channel/..." class="w-full bg-slate-50 text-sm rounded-xl px-4 py-2.5 border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">कार्ड हेडिंग (Title)</label>
                        <input type="text" name="whatsapp_channel_title" value="{{ $settings['whatsapp_channel_title'] ?? setting('whatsapp_channel_title', 'व्हाट्सएप चैनल से जुड़ें') }}" class="w-full bg-slate-50 text-sm rounded-xl px-4 py-2.5 border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">सब्सक्राइबर्स बैज (Followers Count)</label>
                        <input type="text" name="whatsapp_subscribers_count" value="{{ $settings['whatsapp_subscribers_count'] ?? setting('whatsapp_subscribers_count', '50,000+') }}" placeholder="उदा. 50,000+" class="w-full bg-slate-50 text-sm rounded-xl px-4 py-2.5 border border-slate-200">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">कस्टम QR कोड इमेज अपलोड (वैकल्पिक)</label>
                        <input type="file" name="whatsapp_qr_file" accept="image/*" class="w-full bg-slate-50 text-xs text-slate-600 rounded-xl px-4 py-2 border border-slate-200 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        <p class="text-[11px] text-slate-400 mt-1">यदि अपलोड नहीं करेंगे तो सिस्टम ऊपर दिए गए WhatsApp Channel URL से हाई-रेज़ोल्यूशन QR कोड स्वतः जनरेट कर देगा।</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">QR कोड इमेज URL (या डायरेक्ट लिंक)</label>
                        <input type="text" name="whatsapp_qr_url" value="{{ $settings['whatsapp_qr_url'] ?? setting('whatsapp_qr_url') }}" placeholder="https://.../qr.png" class="w-full bg-slate-50 text-sm rounded-xl px-4 py-2.5 border border-slate-200">
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. Footer & Legal -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                <span class="p-2 rounded-xl bg-slate-100 text-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                <div>
                    <h3 class="font-bold text-slate-900 text-base">६. फुटर व कॉपीराइट नोटिस</h3>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">फुटर अबाउट टेक्स्ट</label>
                <textarea name="footer_about" rows="3" class="w-full bg-slate-50 text-sm rounded-xl p-4 border border-slate-200">{{ $settings['footer_about'] ?? setting('footer_about') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">कॉपीराइट लाइन</label>
                <input type="text" name="copyright_text" value="{{ $settings['copyright_text'] ?? setting('copyright_text') }}" class="w-full bg-slate-50 text-sm rounded-xl px-4 py-2.5 border border-slate-200">
            </div>
        </div>

        <!-- Save Button Sticky Bar -->
        <div class="sticky bottom-4 z-30 p-4 rounded-2xl bg-slate-900/95 backdrop-blur-md text-white flex items-center justify-between shadow-2xl border border-slate-800">
            <div class="flex items-center gap-2 text-xs text-slate-300">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>बदलाव तुरंत पूरी साइट और CMS में सेव होंगे</span>
            </div>

            <button type="submit" class="bg-brand-primary hover:brightness-110 text-white font-extrabold px-8 py-3 rounded-xl transition-all shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                <span>सभी सेटिंग्स व ब्रांडिंग सेव करें</span>
            </button>
        </div>

    </form>

</div>
@endsection
