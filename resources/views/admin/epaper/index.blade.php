@extends('layouts.admin')

@section('title', __('Digital E-Paper Manager'))
@section('page_title', __('E-Paper & Newspaper Editions'))

@section('content')
<div class="space-y-6">

    <!-- Metrics Strip -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Total Editions') }}</span>
            <span class="text-2xl sm:text-3xl font-black text-slate-900 mt-1 block">{{ $totalEditions }}</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Total Readers / Views') }}</span>
            <span class="text-2xl sm:text-3xl font-black text-blue-600 mt-1 block">{{ number_format($totalViews) }}</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('PDF Downloads') }}</span>
            <span class="text-2xl sm:text-3xl font-black text-emerald-600 mt-1 block">{{ number_format($totalDownloads) }}</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Live Web Reader') }}</span>
            <a href="{{ route('portal.epaper') }}" target="_blank" class="text-xs font-bold text-brand-primary hover:underline mt-2 inline-flex items-center gap-1">
                <span>{{ __('Open Reader') }}</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- 1. Publish New Edition Form (5 cols) -->
        <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs h-fit space-y-6">
            <div class="pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-900 text-base">{{ __('Publish Daily Edition') }}</h3>
                <p class="text-xs text-slate-500">{{ __('Upload daily newspaper pages or full edition PDF for web and mobile app readers.') }}</p>
            </div>

            <form action="{{ route('admin.epaper.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Edition Title -->
                <div>
                    <label for="edition_title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        {{ __('Edition Title *') }}
                    </label>
                    <input type="text" id="edition_title" name="title" required value="{{ old('title', 'NewsKit Daily Edition - ' . date('d M Y')) }}"
                           class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-3.5 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                </div>

                <!-- Edition Date -->
                <div>
                    <label for="edition_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        {{ __('Publication Date *') }}
                    </label>
                    <input type="date" id="edition_date" name="edition_date" required value="{{ old('edition_date', date('Y-m-d')) }}"
                           class="w-full bg-slate-50 text-slate-900 text-sm rounded-xl px-3.5 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                </div>

                <!-- City / Regional Filter -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="city_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            {{ __('City / District') }}
                        </label>
                        <select id="city_id" name="city_id" class="w-full bg-slate-50 text-slate-900 text-xs rounded-xl px-3 py-2.5 border border-slate-200 outline-none">
                            <option value="">{{ __('All / National') }}</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="state_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            {{ __('State') }}
                        </label>
                        <select id="state_id" name="state_id" class="w-full bg-slate-50 text-slate-900 text-xs rounded-xl px-3 py-2.5 border border-slate-200 outline-none">
                            <option value="">{{ __('All States') }}</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Cover Image -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        {{ __('Front Page / Cover Image') }}
                    </label>
                    <input type="file" name="cover_file" accept="image/*"
                           class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-slate-100 file:text-slate-800 hover:file:bg-slate-200">
                    <input type="url" name="cover_url" placeholder="{{ __('Or paste image URL (https://...)') }}"
                           class="mt-1.5 w-full bg-slate-50 text-slate-900 text-xs rounded-lg px-3 py-2 border border-slate-200 outline-none">
                </div>

                <!-- Full Edition PDF File -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        {{ __('Full Edition PDF (Downloadable)') }}
                    </label>
                    <input type="file" name="pdf_file" accept=".pdf"
                           class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-slate-100 file:text-slate-800 hover:file:bg-slate-200">
                    <input type="url" name="pdf_url" placeholder="{{ __('Or paste PDF URL (https://...)') }}"
                           class="mt-1.5 w-full bg-slate-50 text-slate-900 text-xs rounded-lg px-3 py-2 border border-slate-200 outline-none">
                </div>

                <!-- Multiple Page Images (Flipbook Pages) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        {{ __('Upload Newspaper Pages (Multiple Images)') }}
                    </label>
                    <input type="file" name="pages[]" multiple accept="image/*"
                           class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-brand-primary/10 file:text-brand-primary hover:file:bg-brand-primary/20">
                    <p class="text-[11px] text-slate-400 mt-1">{{ __('Select Page 1, Page 2, Page 3 in order. They will be sequenced automatically.') }}</p>
                </div>

                <!-- Publish Toggle -->
                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="is_published" name="is_published" value="1" checked class="rounded border-slate-300 text-brand-primary">
                    <label for="is_published" class="text-xs font-bold text-slate-700 cursor-pointer">
                        {{ __('Publish Immediately') }}
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-brand-primary hover:bg-brand-primary-hover text-white font-bold py-3 px-4 rounded-xl shadow-md transition-all text-sm">
                    {{ __('Publish E-Paper Edition') }}
                </button>
            </form>
        </div>

        <!-- 2. Existing Editions List (7 cols) -->
        <div class="lg:col-span-7 space-y-4">
            <div class="flex items-center justify-between pb-2">
                <h3 class="font-bold text-slate-900 text-base">{{ __('Published Editions Archive') }}</h3>
                <span class="text-xs text-slate-500">{{ $editions->total() }} {{ __('Total Editions') }}</span>
            </div>

            @if($editions->isEmpty())
                <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-400 text-sm">
                    {{ __('No digital editions created yet.') }}
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($editions as $edition)
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs flex flex-col justify-between">
                            <div>
                                <!-- Cover Preview -->
                                <div class="relative h-48 bg-slate-100 overflow-hidden">
                                    @if($edition->cover_image)
                                        <img src="{{ $edition->cover_image }}" alt="{{ $edition->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                        </div>
                                    @endif

                                    <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-black/75 text-white backdrop-blur-xs">
                                            {{ $edition->formatted_date }}
                                        </span>
                                        @if($edition->city)
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-brand-primary text-white">
                                                {{ $edition->city->name }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="absolute top-2.5 right-2.5">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $edition->is_published ? 'bg-emerald-500 text-white' : 'bg-slate-500 text-white' }}">
                                            {{ $edition->is_published ? __('Live') : __('Draft') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Body Info -->
                                <div class="p-4 space-y-2">
                                    <h4 class="font-bold text-slate-900 text-sm line-clamp-1">{{ $edition->title }}</h4>
                                    <div class="flex items-center gap-3 text-xs text-slate-500">
                                        <span>📑 {{ $edition->total_pages }} {{ __('Pages') }}</span>
                                        <span>👁️ {{ number_format($edition->views_count) }} {{ __('Views') }}</span>
                                        <span>📥 {{ number_format($edition->downloads_count) }} {{ __('PDF') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="p-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                                <a href="{{ route('portal.epaper', $edition->edition_date->format('Y-m-d')) }}" target="_blank"
                                   class="text-brand-primary font-bold hover:underline inline-flex items-center gap-1">
                                    <span>{{ __('View Reader') }}</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>

                                <div class="flex items-center gap-2">
                                    <form action="{{ route('admin.epaper.toggle', $edition) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 rounded-lg border text-[11px] font-semibold {{ $edition->is_published ? 'border-amber-200 text-amber-700 bg-amber-50 hover:bg-amber-100' : 'border-emerald-200 text-emerald-700 bg-emerald-50 hover:bg-emerald-100' }}">
                                            {{ $edition->is_published ? __('Unpublish') : __('Publish') }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.epaper.destroy', $edition) }}" method="POST" onsubmit="return confirm('{{ __('Delete this edition?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-2">
                    {{ $editions->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
