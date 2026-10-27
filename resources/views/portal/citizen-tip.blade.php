@extends('layouts.portal')

@section('title', 'जनता से न्यूज़ टिप / सिटीज़न जर्नलिज्म - ' . setting('site_name', 'The LallanTop News'))
@section('meta_description', 'अपने शहर, मोहल्ले या गाँव की ख़बर, समस्या या ब्रेकिंग न्यूज़ सीधे न्यूज़ डेस्क को भेजें। आपकी आवाज़, हमारी ताक़त।')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">

    <!-- Top Breadcrumb -->
    <div class="mb-6 flex items-center justify-between text-xs">
        <a href="{{ route('portal.index') }}" class="font-bold text-slate-500 hover:text-brand-primary flex items-center gap-1.5 transition-colors">
            <span>← होमपेज पर वापस जाएं</span>
        </a>
        <span class="font-bold text-rose-600 bg-rose-50 dark:bg-rose-950/60 px-3 py-1 rounded-full border border-rose-200 dark:border-rose-900">
            📢 जनता की आवाज़ • सिटीज़न रिपोर्टर
        </span>
    </div>

    <!-- Header Hero Card -->
    <div class="relative bg-gradient-to-r from-slate-900 via-rose-950 to-slate-950 text-white rounded-3xl p-6 sm:p-10 shadow-xl overflow-hidden border border-slate-800 mb-8">
        <div class="relative z-10 max-w-2xl space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-rose-600 text-white shadow-xs">
                <span>🔴</span>
                <span>सिटीज़न जर्नलिज्म बॉक्स (Citizen News Tip)</span>
            </span>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white leading-tight tracking-tight">
                अपने शहर की ताज़ा ख़बर या जन-समस्या सीधे न्यूज़ डेस्क को भेजें
            </h1>
            <p class="text-sm sm:text-base text-slate-300 leading-relaxed">
                क्या आपने कोई दुर्घटना, जन-मुद्दा, सरकारी धांधली या कोई प्रेरणादायक घटना देखी है? सबूत, फोटो या वीडियो के साथ हमें बताएं। हमारे पत्रकार जांच के बाद इसे प्रमुखता से प्रकाशित करेंगे।
            </p>
            <div class="pt-2 flex flex-wrap items-center gap-4 text-xs text-amber-300 font-semibold">
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>100% सुरक्षित व गोपनीय</span>
                </span>
                <span>•</span>
                <span>त्वरित संपादकीय समीक्षा</span>
                <span>•</span>
                <span>नागरिक पत्रकारिता का खुला मंच</span>
            </div>
        </div>
        <!-- Decorative Glow -->
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-brand-primary/20 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Flash Success Alert -->
    @if(session('success'))
        <div class="p-6 rounded-3xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-900 dark:text-emerald-100 flex items-start gap-4 mb-8 shadow-sm">
            <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="space-y-1">
                <h4 class="font-extrabold text-base">सफलतापूर्वक प्राप्त!</h4>
                <p class="text-sm font-medium leading-relaxed">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Submission Form Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-10 border border-slate-200 dark:border-slate-800 shadow-xl space-y-8">
        
        <form action="{{ route('portal.citizen-tip.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- 1. Headline / Issue Title -->
            <div>
                <label for="title" class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                    1. क्या घटना हुई? / मुख्य शीर्षक (Headline) *
                </label>
                <input type="text" id="title" name="title" required value="{{ old('title') }}" 
                       placeholder="उदाहरण: लखनऊ के हजरतगंज में मुख्य सड़क पर बड़ा गड्ढा, कई वाहन क्षतिग्रस्त..." 
                       class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-base font-bold rounded-2xl px-4 py-3.5 border border-slate-200 dark:border-slate-700 focus:border-brand-primary focus:bg-white outline-none transition-all">
                @error('title')
                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- 2. Detailed Description -->
            <div>
                <label for="description" class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                    2. पूरी घटना का विवरण (Detailed Information) *
                </label>
                <textarea id="description" name="description" rows="5" required 
                          placeholder="विस्तार से बताएं कि घटना कब हुई, कहाँ हुई, और इसका क्या असर हुआ है..." 
                          class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-sm rounded-2xl p-4 border border-slate-200 dark:border-slate-700 focus:border-brand-primary focus:bg-white outline-none transition-all leading-relaxed">{{ old('description') }}</textarea>
                <span class="block text-[11px] text-slate-400 mt-1">न्यूनतम 20 अक्षर आवश्यक हैं।</span>
                @error('description')
                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- 3. Geographical Location: State & City -->
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 space-y-4">
                <div class="flex items-center gap-2">
                    <span class="text-rose-500">📍</span>
                    <span class="text-xs font-black uppercase tracking-wider text-slate-800 dark:text-slate-200">घटना स्थल / भौगोलिक क्षेत्र (Location)</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- State Selection -->
                    <div>
                        <label for="tip_state_id" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">राज्य (State)</label>
                        <select id="tip_state_id" name="state_id" class="w-full bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-bold rounded-xl px-3 py-2.5 border border-slate-200 dark:border-slate-700 outline-none focus:border-brand-primary">
                            <option value="">राज्य चुनें...</option>
                            @foreach($states as $st)
                                <option value="{{ $st->id }}" {{ old('state_id') == $st->id ? 'selected' : '' }}>
                                    {{ $st->name }} ({{ $st->name_en }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- City Selection -->
                    <div>
                        <label for="tip_city_id" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">ज़िला / शहर (City)</label>
                        <select id="tip_city_id" name="city_id" class="w-full bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-bold rounded-xl px-3 py-2.5 border border-slate-200 dark:border-slate-700 outline-none focus:border-brand-primary">
                            <option value="">ज़िला चुनें...</option>
                        </select>
                    </div>

                    <!-- Landmark / Exact Spot -->
                    <div>
                        <label for="location_name" class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1.5">स्थान / लैंडमार्क (Exact Spot)</label>
                        <input type="text" id="location_name" name="location_name" value="{{ old('location_name') }}" placeholder="जैसे: कलेक्ट्रेट के सामने, बस स्टैंड..." class="w-full bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs rounded-xl px-3 py-2.5 border border-slate-200 dark:border-slate-700 outline-none focus:border-brand-primary">
                    </div>
                </div>
            </div>

            <!-- 4. Photo / Video Proof Upload -->
            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                    4. फ़ोटो, वीडियो या दस्तावेज़ सबूत (Photo / Video Evidence - Max 25MB)
                </label>
                <div class="relative border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-brand-primary rounded-2xl p-6 text-center transition-colors bg-slate-50/50 dark:bg-slate-800/40 cursor-pointer">
                    <input type="file" name="media_file" id="media_file" accept="image/*,video/mp4,video/webm,video/quicktime,application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="space-y-2 pointer-events-none">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-brand-primary mx-auto flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="text-xs font-bold text-slate-700 dark:text-slate-300">
                            <span class="text-brand-primary">फ़ाइल चुनें</span> या यहाँ ड्रैग करें
                        </div>
                        <p class="text-[11px] text-slate-400">सपोर्टेड: JPG, PNG, MP4, WebM, PDF (अधिकतम 25 MB)</p>
                        <p id="tip-file-name" class="text-xs font-bold text-emerald-600 hidden"></p>
                    </div>
                </div>
            </div>

            <!-- 5. Whistleblower Anonymity Toggle -->
            <div class="p-4 rounded-2xl bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-900/60 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div>
                        <span class="block text-xs font-black text-purple-950 dark:text-purple-200">मेरी पहचान पूरी तरह गोपनीय रखें (Keep Anonymous)</span>
                        <span class="block text-[11px] text-purple-800 dark:text-purple-300">खबर प्रकाशित होने पर आपका नाम या संपर्क कभी भी सार्वजनिक नहीं किया जाएगा</span>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                    <input type="checkbox" name="is_anonymous" value="1" {{ old('is_anonymous') ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-300 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                </label>
            </div>

            <!-- 6. Witness / Citizen Contact Info (For Editorial Verification Only) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                <div>
                    <label for="witness_name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">आपका नाम (Your Name)</label>
                    <input type="text" id="witness_name" name="witness_name" value="{{ old('witness_name') }}" placeholder="श्री / सुश्री..." class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs rounded-xl px-3 py-2.5 border border-slate-200 dark:border-slate-700 outline-none focus:border-brand-primary">
                </div>

                <div>
                    <label for="witness_phone" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">मोबाइल / WhatsApp नंबर *</label>
                    <input type="tel" id="witness_phone" name="witness_phone" value="{{ old('witness_phone') }}" placeholder="+91 98765 43210" class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs rounded-xl px-3 py-2.5 border border-slate-200 dark:border-slate-700 outline-none focus:border-brand-primary font-mono">
                    <span class="block text-[10px] text-slate-400 mt-0.5">संपादकीय डेस्क द्वारा खबर की पुष्टि हेतु</span>
                </div>

                <div>
                    <label for="witness_email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">ईमेल (वैकल्पिक)</label>
                    <input type="email" id="witness_email" name="witness_email" value="{{ old('witness_email') }}" placeholder="name@example.com" class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs rounded-xl px-3 py-2.5 border border-slate-200 dark:border-slate-700 outline-none focus:border-brand-primary">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-gradient-to-r from-rose-600 to-brand-primary hover:brightness-110 text-white font-black text-sm py-4 px-6 rounded-2xl shadow-xl shadow-rose-600/25 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span>संपादकीय डेस्क को सुरक्षित भेजें (Submit News Tip)</span>
                </button>
            </div>

        </form>

    </div>

</div>

<!-- Cascading State & City Dropdown Script -->
<script>
    const statesWithCities = @json($states);
    const stateSelect = document.getElementById('tip_state_id');
    const citySelect = document.getElementById('tip_city_id');
    const initialCityId = @json(old('city_id'));

    function populateTipCities(stateId, selectedCityId = null) {
        citySelect.innerHTML = '<option value="">ज़िला चुनें...</option>';
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

    if (stateSelect) {
        stateSelect.addEventListener('change', function() {
            populateTipCities(this.value);
        });

        if (stateSelect.value) {
            populateTipCities(stateSelect.value, initialCityId);
        }
    }

    // File Name Display
    const fileInput = document.getElementById('media_file');
    const fileNameEl = document.getElementById('tip-file-name');
    if (fileInput && fileNameEl) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                fileNameEl.textContent = 'चयनित फ़ाइल: ' + this.files[0].name + ' (' + (this.files[0].size / 1024 / 1024).toFixed(2) + ' MB)';
                fileNameEl.classList.remove('hidden');
            } else {
                fileNameEl.classList.add('hidden');
            }
        });
    }
</script>
@endsection
