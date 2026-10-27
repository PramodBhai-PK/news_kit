@extends('layouts.admin')

@section('title', 'श्रेणियां (Categories)')
@section('page_title', 'समाचार श्रेणियां (Categories Management)')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Add Category Form -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs h-fit space-y-4">
        <div class="pb-3 border-b border-slate-100">
            <h3 class="font-bold text-slate-900 text-sm">नई श्रेणी जोड़ें (Add Category)</h3>
            <p class="text-xs text-slate-400">पोर्टल पर नई बीट/कैटेगरी तुरंत प्रदर्शित होगी।</p>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">अंग्रेज़ी नाम (Slug Base) *</label>
                <input type="text" name="name" required placeholder="Politics, Science..." class="w-full bg-slate-50 text-sm rounded-xl px-3 py-2 border border-slate-200 focus:border-brand-primary outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">हिंदी नाम (Display Name) *</label>
                <input type="text" name="hindi_name" placeholder="राजनीति, विज्ञान..." class="w-full bg-slate-50 text-sm rounded-xl px-3 py-2 border border-slate-200 focus:border-brand-primary outline-none font-semibold">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">कैटेगरी कलर (Badge Color)</label>
                <div class="flex items-center gap-3">
                    <input type="color" name="color" value="#E11D48" class="w-10 h-10 rounded-lg border-0 cursor-pointer">
                    <span class="text-xs text-slate-400 font-mono">कार्ड्स और बैज के लिए</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">क्रम संख्या (Sort Order)</label>
                <input type="number" name="order" value="0" class="w-full bg-slate-50 text-sm rounded-xl px-3 py-2 border border-slate-200 focus:border-brand-primary outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">संक्षिप्त विवरण (Description)</label>
                <textarea name="description" rows="2" class="w-full bg-slate-50 text-sm rounded-xl p-2.5 border border-slate-200 focus:border-brand-primary outline-none"></textarea>
            </div>

            <button type="submit" class="w-full bg-brand-primary text-white font-bold text-xs py-3 rounded-xl hover:brightness-110 shadow-sm">
                + श्रेणी जोड़ें (Save Category)
            </button>
        </form>
    </div>

    <!-- Categories List Table -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-sm">उपलब्ध श्रेणियां (Active Categories)</h3>
            <span class="text-xs text-slate-500 font-semibold">{{ $categories->count() }} श्रेणियां</span>
        </div>

        <div class="divide-y divide-slate-100">
            @foreach($categories as $cat)
                <div class="p-4 flex items-center justify-between gap-4 hover:bg-slate-50/80 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="w-4 h-4 rounded-full shrink-0 shadow-xs" style="background-color: {{ $cat->color }}"></span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-slate-900 text-sm">{{ $cat->hindi_name ?: $cat->name }}</h4>
                                <span class="text-xs text-slate-400">({{ $cat->name }})</span>
                            </div>
                            <p class="text-xs text-slate-400 line-clamp-1 mt-0.5">{{ $cat->description ?: 'कोई विवरण नहीं' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 shrink-0">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                            {{ $cat->articles_count }} ख़बरें
                        </span>

                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('क्या आप इस श्रेणी को हटाना चाहते हैं?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-slate-100" title="हटाएं">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
