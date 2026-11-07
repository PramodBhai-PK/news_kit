@extends('layouts.admin')

@section('title', __('Write New Article'))
@section('page_title', __('Publish News Article'))

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Headline / Title *') }}</label>
                <input type="text" id="title" name="title" required value="{{ old('title') }}" 
                       placeholder="{{ __('Write an engaging, clear headline...') }}"
                       class="w-full bg-slate-50 text-slate-900 text-base font-bold rounded-xl px-4 py-3 border border-slate-200 focus:border-brand-primary focus:bg-white outline-none">
            </div>

            <!-- Category & Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Category *') }}</label>
                    <select id="category_id" name="category_id" required class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                        <option value="">{{ __('Select Category') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->display_name ?? $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Publication Status *') }}</label>
                    <select id="status" name="status" required class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none font-semibold">
                        <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>{{ __('Publish Now') }}</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>{{ __('Save Draft') }}</option>
                        <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>{{ __('Archive') }}</option>
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
                            <span class="block text-xs font-black text-slate-800 uppercase tracking-wider">{{ __('Location & Geographical Scope') }}</span>
                            <span class="block text-[11px] text-slate-500">{{ __('Assigning a state and district allows the article to appear in hyperlocal sections') }}</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- State Selection -->
                    <div>
                        <label for="state_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('State / Region') }}</label>
                        <select id="state_id" name="state_id" class="w-full bg-white text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                            <option value="">{{ __('🌐 National / General (No State)') }}</option>
                            @foreach($states as $st)
                                <option value="{{ $st->id }}" {{ old('state_id') == $st->id ? 'selected' : '' }}>
                                    {{ $st->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- City / District Selection -->
                    <div>
                        <label for="city_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('District / City') }}</label>
                        <select id="city_id" name="city_id" class="w-full bg-white text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                            <option value="">{{ __('All Districts / General') }}</option>
                        </select>
                    </div>

                    <!-- Specific Landmark / Town -->
                    <div>
                        <label for="location_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Specific Town / Landmark (Optional)') }}</label>
                        <input type="text" id="location_name" name="location_name" value="{{ old('location_name') }}"
                               placeholder="{{ __('e.g., Downtown, Central Market, City Square...') }}"
                               class="w-full bg-white text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                    </div>
                </div>
            </div>

            <!-- Excerpt -->
            <div>
                <label for="excerpt" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Short Excerpt / Summary') }}</label>
                <textarea id="excerpt" name="excerpt" rows="2" placeholder="{{ __('Core summary of the story (1-2 sentences)...') }}"
                          class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl p-3 border border-slate-200 focus:border-brand-primary focus:bg-white outline-none">{{ old('excerpt') }}</textarea>
            </div>

            <!-- Full Content -->
            <div>
                <label for="content" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Article Content Body *') }}</label>
                <textarea id="content" name="content" rows="12" required placeholder="{{ __('Write the full article content in detail...') }}"
                          class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl p-4 border border-slate-200 focus:border-brand-primary focus:bg-white outline-none font-normal leading-relaxed">{{ old('content') }}</textarea>
                <span class="block text-[11px] text-slate-400 mt-1">{{ __('You can use HTML formatting or plain text paragraphs.') }}</span>
            </div>

            <!-- Featured Image -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2 border-t border-slate-100">
                <div>
                    <label for="featured_image_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Featured Image URL') }}</label>
                    <input type="url" id="featured_image_url" name="featured_image_url" value="{{ old('featured_image_url') }}"
                           placeholder="https://images.unsplash.com/..."
                           class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                    
                    <div class="mt-3">
                        <span class="block text-xs text-slate-500 mb-1 font-medium">{{ __('Or upload image from computer:') }}</span>
                        <input type="file" name="featured_image_file" accept="image/*" class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-brand-primary cursor-pointer">
                    </div>
                </div>

                <div>
                    <label for="image_caption" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Photo Caption') }}</label>
                    <input type="text" id="image_caption" name="image_caption" value="{{ old('image_caption') }}"
                           placeholder="{{ __('e.g., File Photo / Reuters / Staff...') }}"
                           class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                </div>
            </div>

            <!-- Editorial Badges & Flags -->
            <div class="pt-4 border-t border-slate-100">
                <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">{{ __('Special Priority Flags') }}</span>
                <div class="flex flex-wrap items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_breaking" value="1" {{ old('is_breaking') ? 'checked' : '' }} class="w-4 h-4 text-brand-primary rounded border-slate-300 focus:ring-0">
                        <span class="text-xs font-bold text-rose-600">🔴 {{ __('Show in Breaking News Ticker') }}</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 text-amber-500 rounded border-slate-300 focus:ring-0">
                        <span class="text-xs font-bold text-amber-700">⭐ {{ __('Pin to Homepage Top Hero Grid') }}</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_trending" value="1" {{ old('is_trending') ? 'checked' : '' }} class="w-4 h-4 text-purple-600 rounded border-slate-300 focus:ring-0">
                        <span class="text-xs font-bold text-purple-700">🔥 {{ __('Include in Trending Section') }}</span>
                    </label>
                </div>
            </div>

            <!-- Tags -->
            <div class="pt-4 border-t border-slate-100">
                <label for="tags" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ __('Tags (Comma Separated)') }}</label>
                <input type="text" id="tags" name="tags" value="{{ old('tags') }}"
                       placeholder="BreakingNews, Politics, Technology, World"
                       class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-4 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
            </div>

            <!-- Video Coverage (YouTube & Direct MP4 Video) -->
            <div class="pt-6 border-t border-slate-100 space-y-4">
                <div class="flex items-center gap-2">
                    <span class="p-1 rounded bg-rose-50 text-brand-primary">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                    </span>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">{{ __('Video Coverage (YouTube / Direct Video)') }}</h4>
                        <p class="text-[11px] text-slate-400">{{ __('Attach a YouTube link or upload an MP4 video with this article.') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">{{ __('Video Type') }}</label>
                        <select name="video_type" id="article_video_type" onchange="toggleArticleVideo(this.value)" class="w-full bg-slate-50 text-xs rounded-xl px-3 py-2.5 border border-slate-200 focus:border-brand-primary outline-none font-semibold">
                            <option value="none" {{ old('video_type', 'none') === 'none' ? 'selected' : '' }}>{{ __('No Video') }}</option>
                            <option value="youtube" {{ old('video_type') === 'youtube' ? 'selected' : '' }}>{{ __('YouTube Video (URL / Shorts)') }}</option>
                            <option value="direct" {{ old('video_type') === 'direct' ? 'selected' : '' }}>{{ __('Direct MP4 Video (Upload / URL)') }}</option>
                        </select>
                    </div>
                </div>

                <!-- YouTube Video Box -->
                <div id="art_video_youtube_box" class="{{ old('video_type') === 'youtube' ? '' : 'hidden' }} p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                    <label class="block text-xs font-bold text-slate-700">{{ __('YouTube Video URL (Link or Shorts):') }}</label>
                    <input type="text" name="video_url" id="yt_video_url" value="{{ old('video_url') }}" 
                           placeholder="https://www.youtube.com/watch?v=..." 
                           class="w-full bg-white text-xs rounded-lg px-3 py-2 border border-slate-200 outline-none">
                    <span class="block text-[11px] text-slate-400">{{ __('This will automatically embed at the top of the article and appear in the video gallery.') }}</span>
                </div>

                <!-- Direct Video Upload / URL Box -->
                <div id="art_video_direct_box" class="{{ old('video_type') === 'direct' ? '' : 'hidden' }} p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                    <div>
                        <span class="block text-xs font-bold text-slate-700 mb-1">{{ __('Upload MP4/WebM video file (Max 50MB):') }}</span>
                        <input type="file" name="video_file" accept="video/mp4,video/webm,video/ogg,video/quicktime" class="text-xs text-slate-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-brand-primary cursor-pointer w-full">
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-700 mb-1">{{ __('Or direct MP4 video link (URL):') }}</span>
                        <input type="url" name="direct_video_url" placeholder="https://example.com/video.mp4" class="w-full bg-white text-xs rounded-lg px-3 py-2 border border-slate-200 outline-none">
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
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">{{ __('Custom Article Advertisement (Optional)') }}</h4>
                        <p class="text-[11px] text-slate-400">{{ __('Optionally display a custom image banner or AdSense code specifically alongside this story.') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">{{ __('Ad Type') }}</label>
                        <select name="custom_ad_type" id="article_ad_type" onchange="toggleArticleAd(this.value)" class="w-full bg-slate-50 text-xs rounded-xl px-3 py-2 border border-slate-200 focus:border-brand-primary outline-none">
                            <option value="none" selected>{{ __('No Custom Ad / Use Global') }}</option>
                            <option value="image">{{ __('Custom Image Banner (Upload / URL)') }}</option>
                            <option value="code">Google AdSense / Custom HTML</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">{{ __('Ad Title (Optional)') }}</label>
                        <input type="text" name="custom_ad_title" placeholder="{{ __('Sponsored / Partner...') }}" class="w-full bg-slate-50 text-xs rounded-xl px-3 py-2 border border-slate-200 outline-none">
                    </div>
                </div>

                <!-- Custom Ad Image Fields -->
                <div id="art_ad_image_box" class="hidden p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                    <div>
                        <span class="block text-xs font-bold text-slate-700 mb-1">{{ __('Upload Ad Image:') }}</span>
                        <input type="file" name="custom_ad_image_file" accept="image/*" class="text-xs text-slate-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-brand-primary cursor-pointer w-full">
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-700 mb-1">{{ __('Or Ad Image URL:') }}</span>
                        <input type="url" name="custom_ad_image" placeholder="https://..." class="w-full bg-white text-xs rounded-lg px-3 py-2 border border-slate-200 outline-none">
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-700 mb-1">{{ __('Destination URL:') }}</span>
                        <input type="url" name="custom_ad_link" placeholder="https://..." class="w-full bg-white text-xs rounded-lg px-3 py-2 border border-slate-200 outline-none">
                    </div>
                </div>

                <!-- Custom Ad Code Field -->
                <div id="art_ad_code_box" class="hidden p-4 rounded-xl bg-slate-900 border border-slate-800 space-y-2">
                    <span class="block text-xs font-bold text-amber-400">{{ __('Google AdSense Script / HTML Code:') }}</span>
                    <textarea name="custom_ad_code" rows="4" placeholder="<script ...></script>" class="w-full bg-slate-950 font-mono text-xs rounded-lg p-3 border border-slate-700 text-emerald-400 outline-none"></textarea>
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
            const oldCityId = @json(old('city_id'));

            function populateCities(stateId, selectedCityId = null) {
                citySelect.innerHTML = '<option value="">' + "{{ __('All Districts / General') }}" + '</option>';
                if (!stateId) return;

                const stateObj = statesWithCities.find(s => s.id == stateId);
                if (stateObj && stateObj.cities) {
                    stateObj.cities.forEach(city => {
                        const opt = document.createElement('option');
                        opt.value = city.id;
                        opt.textContent = (city.name_en || city.name);
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
                populateCities(stateSelect.value, oldCityId);
            }
        </script>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.articles.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">
                {{ __('← Cancel & Return') }}
            </a>

            <button type="submit" class="bg-brand-primary hover:brightness-110 text-white font-extrabold px-8 py-3 rounded-xl transition-all shadow-md flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ __('Save & Publish Article') }}</span>
            </button>
        </div>
    </form>
</div>
@endsection
