@extends('layouts.admin')

@section('title', 'ख़बर एडिट करें')
@section('page_title', 'ख़बर संपादित करें (Edit News Story)')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">ख़बर का मुख्य शीर्षक (Headline / Title) *</label>
                <input type="text" id="title" name="title" required value="{{ old('title', $article->title) }}" 
                       class="w-full bg-slate-50 text-slate-900 text-base font-bold rounded-xl px-4 py-3 border border-slate-200 focus:border-brand-primary focus:bg-white outline-none">
            </div>

            <!-- Category & Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">श्रेणी (Category) *</label>
                    <select id="category_id" name="category_id" required class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->hindi_name ?: $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">प्रकाशन स्थिति (Status) *</label>
                    <select id="status" name="status" required class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none font-semibold">
                        <option value="published" {{ old('status', $article->status) === 'published' ? 'selected' : '' }}>प्रकाशित (Published)</option>
                        <option value="draft" {{ old('status', $article->status) === 'draft' ? 'selected' : '' }}>ड्राफ्ट (Draft)</option>
                        <option value="archived" {{ old('status', $article->status) === 'archived' ? 'selected' : '' }}>आर्काइव (Archive)</option>
                    </select>
                </div>
            </div>

            <!-- Geographical Location / Hyperlocal Scope -->
            <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-200/90 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200/60">
                    <div class="flex items-center gap-2">
                        <span class="p-1.5 rounded-lg bg-rose-100 text-brand-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <div>
                            <span class="block text-xs font-black text-slate-800 uppercase tracking-wider">समाचार का भौगोलिक क्षेत्र / लोकेशन (Location & Scope)</span>
                            <span class="block text-[11px] text-slate-500">राज्य व ज़िला चुनने पर यह खबर संबंधित राज्य व शहर के सेक्शन में दिखेगी</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- State Selection -->
                    <div>
                        <label for="state_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">राज्य (State / Region)</label>
                        <select id="state_id" name="state_id" class="w-full bg-white text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                            <option value="">🌐 राष्ट्रीय / सामान्य (कोई राज्य नहीं)</option>
                            @foreach($states as $st)
                                <option value="{{ $st->id }}" {{ old('state_id', $article->state_id) == $st->id ? 'selected' : '' }}>
                                    {{ $st->name }} ({{ $st->name_en }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- City / District Selection -->
                    <div>
                        <label for="city_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">ज़िला / शहर (District / City)</label>
                        <select id="city_id" name="city_id" class="w-full bg-white text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                            <option value="">सभी ज़िले / सामान्य</option>
                        </select>
                    </div>

                    <!-- Specific Landmark / Town -->
                    <div>
                        <label for="location_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">विशिष्ट कस्बा / स्थान (Optional)</label>
                        <input type="text" id="location_name" name="location_name" value="{{ old('location_name', $article->location_name) }}"
                               placeholder="उदा. हज़रतगंज, गोमती नगर, दशाश्वमेध"
                               class="w-full bg-white text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                    </div>
                </div>
            </div>

            <!-- Excerpt -->
            <div>
                <label for="excerpt" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">संक्षिप्त विवरण (Short Excerpt / Summary)</label>
                <textarea id="excerpt" name="excerpt" rows="2" class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl p-3 border border-slate-200 focus:border-brand-primary outline-none">{{ old('excerpt', $article->excerpt) }}</textarea>
            </div>

            <!-- Full Content -->
            <div>
                <label for="content" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">पूरी ख़बर का विवरण (Article Content) *</label>
                <textarea id="content" name="content" rows="12" required class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl p-4 border border-slate-200 focus:border-brand-primary outline-none font-normal leading-relaxed">{{ old('content', $article->content) }}</textarea>
            </div>

            <!-- Featured Image -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2 border-t border-slate-100">
                <div>
                    <label for="featured_image_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">मुख्य तस्वीर URL (Image URL)</label>
                    @if($article->featured_image)
                        <div class="mb-3">
                            <img src="{{ $article->featured_image }}" alt="" class="h-24 w-auto rounded-xl object-cover border border-slate-200">
                        </div>
                    @endif
                    <input type="url" id="featured_image_url" name="featured_image_url" value="{{ old('featured_image_url', $article->featured_image) }}"
                           class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                    
                    <div class="mt-3">
                        <span class="block text-xs text-slate-500 mb-1 font-medium">या नई तस्वीर फ़ाइल अपलोड करें:</span>
                        <input type="file" name="featured_image_file" accept="image/*" class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-brand-primary cursor-pointer">
                    </div>
                </div>

                <div>
                    <label for="image_caption" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">तस्वीर का कैप्शन (Photo Caption)</label>
                    <input type="text" id="image_caption" name="image_caption" value="{{ old('image_caption', $article->image_caption) }}"
                           class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                </div>
            </div>

            <!-- Editorial Badges & Flags -->
            <div class="pt-4 border-t border-slate-100">
                <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">विशेष प्राथमिकताएं (Special Flags)</span>
                <div class="flex flex-wrap items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_breaking" value="1" {{ old('is_breaking', $article->is_breaking) ? 'checked' : '' }} class="w-4 h-4 text-brand-primary rounded border-slate-300 focus:ring-0">
                        <span class="text-xs font-bold text-rose-600">🔴 ब्रेकिंग न्यूज़ टिकर में दिखाएं</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $article->is_featured) ? 'checked' : '' }} class="w-4 h-4 text-amber-500 rounded border-slate-300 focus:ring-0">
                        <span class="text-xs font-bold text-amber-700">⭐ होमपेज टॉप हीरो ग्रिड में पिन करें</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_trending" value="1" {{ old('is_trending', $article->is_trending) ? 'checked' : '' }} class="w-4 h-4 text-purple-600 rounded border-slate-300 focus:ring-0">
                        <span class="text-xs font-bold text-purple-700">🔥 ट्रेंडिंग सेक्शन में शामिल करें</span>
                    </label>
                </div>
            </div>

            <!-- Tags -->
            <div class="pt-4 border-t border-slate-100">
                <label for="tags" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">टैग्स (Tags - Comma Separated)</label>
                <input type="text" id="tags" name="tags" value="{{ old('tags', $articleTagNames) }}"
                       class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
            </div>

            <!-- Video Coverage (YouTube & Direct MP4 Video) -->
            <div class="pt-6 border-t border-slate-100 space-y-4">
                <div class="flex items-center gap-2">
                    <span class="p-1 rounded bg-rose-50 text-brand-primary">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                    </span>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">वीडियो कवरेज (Video Coverage - YouTube / Direct Video)</h4>
                        <p class="text-[11px] text-slate-400">इस ख़बर के साथ यूट्यूब वीडियो या सीधे कंप्यूटर से MP4 वीडियो जोड़ें।</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">वीडियो का प्रकार (Video Type)</label>
                        <select name="video_type" id="article_video_type" onchange="toggleArticleVideo(this.value)" class="w-full bg-slate-50 text-xs rounded-xl px-3 py-2.5 border border-slate-200 focus:border-brand-primary outline-none font-semibold">
                            <option value="none" {{ old('video_type', $article->video_type) === 'none' ? 'selected' : '' }}>कोई वीडियो नहीं (No Video)</option>
                            <option value="youtube" {{ old('video_type', $article->video_type) === 'youtube' ? 'selected' : '' }}>YouTube वीडियो (URL / Shorts)</option>
                            <option value="direct" {{ old('video_type', $article->video_type) === 'direct' ? 'selected' : '' }}>डायरेक्ट MP4 वीडियो (Upload / URL)</option>
                        </select>
                    </div>
                </div>

                <!-- YouTube Video Box -->
                <div id="art_video_youtube_box" class="{{ old('video_type', $article->video_type) === 'youtube' ? '' : 'hidden' }} p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                    <label class="block text-xs font-bold text-slate-700">YouTube वीडियो URL (Link या Shorts):</label>
                    <input type="text" name="video_url" id="yt_video_url" value="{{ old('video_url', $article->video_type === 'youtube' ? $article->video_url : '') }}" 
                           placeholder="https://www.youtube.com/watch?v=... या https://youtu.be/..." 
                           class="w-full bg-white text-xs rounded-lg px-3 py-2 border border-slate-200 outline-none">
                    @if($article->isYoutubeVideo() && $article->youtube_id)
                        <div class="mt-2 text-[11px] text-emerald-600 font-bold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>मान्य YouTube ID: {{ $article->youtube_id }}</span>
                        </div>
                    @endif
                    <span class="block text-[11px] text-slate-400">यह खबर के शीर्ष पर और होमपेज वीडियो गैलरी में स्वतः एम्बेड हो जाएगा।</span>
                </div>

                <!-- Direct Video Upload / URL Box -->
                <div id="art_video_direct_box" class="{{ old('video_type', $article->video_type) === 'direct' ? '' : 'hidden' }} p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                    @if($article->isDirectVideo() && $article->video_url)
                        <div class="mb-2">
                            <span class="block text-[11px] text-slate-500 mb-1 font-semibold">वर्तमान वीडियो:</span>
                            <video controls class="max-h-36 rounded-lg bg-black">
                                <source src="{{ $article->video_url }}">
                            </video>
                        </div>
                    @endif
                    <div>
                        <span class="block text-xs font-bold text-slate-700 mb-1">नई MP4/WebM वीडियो फ़ाइल अपलोड करें (Max 50MB):</span>
                        <input type="file" name="video_file" accept="video/mp4,video/webm,video/ogg,video/quicktime" class="text-xs text-slate-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-brand-primary cursor-pointer w-full">
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-700 mb-1">या डायरेक्ट वीडियो MP4 लिंक (URL):</span>
                        <input type="url" name="direct_video_url" value="{{ old('direct_video_url', $article->isDirectVideo() ? $article->video_url : '') }}" placeholder="https://example.com/video.mp4" class="w-full bg-white text-xs rounded-lg px-3 py-2 border border-slate-200 outline-none">
                    </div>
                </div>
            </div>

            <!-- Custom Article Advertisement (Optional) -->
            <div class="pt-6 border-t border-slate-100 space-y-4">
                <div class="flex items-center gap-2">
                    <span class="p-1 rounded bg-amber-50 text-amber-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    </span>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">इस ख़बर के लिए विशेष विज्ञापन (Custom Article Ad - Optional)</h4>
                        <p class="text-[11px] text-slate-400">रिपोर्टर चाहे तो इस खबर के राइट साइड के लिए अपना कस्टम इमेज बैनर या गूगल ऐडसेंस कोड डाल सकता है।</p>
                    </div>
                </div>

                @if($article->hasCustomAd())
                    <div class="flex flex-wrap items-center gap-3 p-3 rounded-xl bg-amber-50/80 border border-amber-200 text-xs">
                        <span class="font-bold text-amber-900">📊 विज्ञापन ट्रैकिंग आंकड़े:</span>
                        <span class="text-slate-700">इम्प्रेशन्स: <strong class="text-slate-900">{{ number_format($article->custom_ad_views_count) }}</strong></span>
                        <span>•</span>
                        <span class="text-slate-700">क्लिक्स: <strong class="text-emerald-700 font-extrabold">{{ number_format($article->custom_ad_clicks_count) }}</strong></span>
                        <span>•</span>
                        <span class="px-2 py-0.5 rounded-full font-extrabold bg-amber-200/70 text-amber-900 text-[11px]">{{ $article->custom_ad_ctr }}% CTR</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">विज्ञापन का प्रकार (Ad Type)</label>
                        <select name="custom_ad_type" id="article_ad_type" onchange="toggleArticleAd(this.value)" class="w-full bg-slate-50 text-xs rounded-xl px-3 py-2 border border-slate-200 focus:border-brand-primary outline-none">
                            <option value="none" {{ old('custom_ad_type', $article->custom_ad_type) === 'none' ? 'selected' : '' }}>कोई विशेष विज्ञापन नहीं (No Ad / Use Global)</option>
                            <option value="image" {{ old('custom_ad_type', $article->custom_ad_type) === 'image' ? 'selected' : '' }}>कस्टम इमेज बैनर (Upload / URL)</option>
                            <option value="code" {{ old('custom_ad_type', $article->custom_ad_type) === 'code' ? 'selected' : '' }}>Google AdSense / Custom HTML</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">विज्ञापन शीर्षक (Optional)</label>
                        <input type="text" name="custom_ad_title" value="{{ old('custom_ad_title', $article->custom_ad_title) }}" placeholder="प्रायोजित / स्पॉन्सर..." class="w-full bg-slate-50 text-xs rounded-xl px-3 py-2 border border-slate-200 outline-none">
                    </div>
                </div>

                <!-- Custom Ad Image Fields -->
                <div id="art_ad_image_box" class="{{ $article->custom_ad_type === 'image' ? '' : 'hidden' }} p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                    @if($article->custom_ad_image)
                        <div class="mb-2">
                            <span class="block text-[11px] text-slate-400 mb-1">वर्तमान विज्ञापन इमेज:</span>
                            <img src="{{ $article->custom_ad_image }}" alt="" class="h-20 w-auto rounded-lg object-contain border border-slate-200 bg-white">
                        </div>
                    @endif
                    <div>
                        <span class="block text-xs font-bold text-slate-700 mb-1">नई विज्ञापन इमेज अपलोड करें:</span>
                        <input type="file" name="custom_ad_image_file" accept="image/*" class="text-xs text-slate-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-brand-primary cursor-pointer w-full">
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-700 mb-1">या इमेज URL:</span>
                        <input type="url" name="custom_ad_image" value="{{ old('custom_ad_image', $article->custom_ad_image) }}" placeholder="https://..." class="w-full bg-white text-xs rounded-lg px-3 py-2 border border-slate-200 outline-none">
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-700 mb-1">क्लिक लिंक (Target URL):</span>
                        <input type="url" name="custom_ad_link" value="{{ old('custom_ad_link', $article->custom_ad_link) }}" placeholder="https://..." class="w-full bg-white text-xs rounded-lg px-3 py-2 border border-slate-200 outline-none">
                    </div>
                </div>

                <!-- Custom Ad Code Field -->
                <div id="art_ad_code_box" class="{{ $article->custom_ad_type === 'code' ? '' : 'hidden' }} p-4 rounded-xl bg-slate-900 border border-slate-800 space-y-2">
                    <span class="block text-xs font-bold text-amber-400">Google AdSense स्क्रिप्ट / HTML कोड:</span>
                    <textarea name="custom_ad_code" rows="4" placeholder="<script ...></script>" class="w-full bg-slate-950 font-mono text-xs rounded-lg p-3 border border-slate-700 text-emerald-400 outline-none">{{ old('custom_ad_code', $article->custom_ad_code) }}</textarea>
                </div>
            </div>
        </div>

        <script>
            function toggleArticleVideo(val) {
                const ytBox = document.getElementById('art_video_youtube_box');
                const directBox = document.getElementById('art_video_direct_box');
                ytBox.classList.add('hidden');
                directBox.classList.add('hidden');
                if (val === 'youtube') ytBox.classList.remove('hidden');
                if (val === 'direct') directBox.classList.remove('hidden');
            }

            function toggleArticleAd(val) {
                const imgBox = document.getElementById('art_ad_image_box');
                const codeBox = document.getElementById('art_ad_code_box');
                imgBox.classList.add('hidden');
                codeBox.classList.add('hidden');
                if (val === 'image') imgBox.classList.remove('hidden');
                if (val === 'code') codeBox.classList.remove('hidden');
            }

            // Dynamic Cascading Cities Dropdown
            const statesWithCities = @json($states);
            const stateSelect = document.getElementById('state_id');
            const citySelect = document.getElementById('city_id');
            const initialCityId = @json(old('city_id', $article->city_id));

            function populateCities(stateId, selectedCityId = null) {
                citySelect.innerHTML = '<option value="">सभी ज़िले / सामान्य</option>';
                if (!stateId) return;

                const stateObj = statesWithCities.find(s => s.id == stateId);
                if (stateObj && stateObj.cities) {
                    stateObj.cities.forEach(city => {
                        const opt = document.createElement('option');
                        opt.value = city.id;
                        opt.textContent = `${city.name} (${city.name_en})`;
                        if (selectedCityId && city.id == selectedCityId) {
                            opt.selected = true;
                        }
                        citySelect.appendChild(opt);
                    });
                }
            }

            stateSelect.addEventListener('change', function () {
                populateCities(this.value);
            });

            if (stateSelect.value) {
                populateCities(stateSelect.value, initialCityId);
            }
        </script>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.articles.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">
                ← वापस जाएं
            </a>

            <div class="flex items-center gap-3">
                <button type="button" 
                        class="open-news-poster-btn px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-md flex items-center gap-2 transition-all cursor-pointer"
                        title="ऑटोमैटिक सोशल मीडिया पोस्टर बनाएं"
                        data-title="{{ $article->title }}"
                        data-category="{{ $article->category->hindi_name ?: $article->category->name }}"
                        data-category-color="{{ $article->category->color }}"
                        data-location="{{ $article->location_badge ?? '' }}"
                        data-author="{{ $article->author->name }}"
                        data-author-avatar="{{ $article->author->avatar ?: '' }}"
                        data-image="{{ $article->featured_image ? asset($article->featured_image) : '' }}"
                        data-date="{{ $article->published_at ? $article->published_at->format('d M, Y') : now()->format('d M, Y') }}"
                        data-url="{{ route('portal.article', $article->slug) }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>📸 पोस्टर बनाएं (Graphic Card)</span>
                </button>

                <a href="{{ route('portal.article', $article->slug) }}" target="_blank" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs hover:bg-slate-50">
                    लाइव व्यू देखें
                </a>
                <button type="submit" class="bg-brand-primary hover:brightness-110 text-white font-extrabold px-8 py-3 rounded-xl transition-all shadow-md flex items-center gap-2">
                    <span>अपडेट करें (Update Story)</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
