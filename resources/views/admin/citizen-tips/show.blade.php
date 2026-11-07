@extends('layouts.admin')

@section('title', __('Citizen Tip Details - #') . $citizenTip->id)
@section('page_title', __('Citizen Tip Details'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.citizen-tips.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition-colors">
            <span>← {{ __('Back to all tips') }}</span>
        </a>

        <div class="flex items-center gap-2">
            @if($citizenTip->status !== 'converted_to_article')
                <form action="{{ route('admin.citizen-tips.convert', $citizenTip->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to convert this citizen tip into a draft article?') }}');">
                    @csrf
                    <button type="submit" class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:brightness-110 text-white font-black text-xs px-5 py-2.5 rounded-xl shadow-md transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>⚡ {{ __('Convert to News Article') }}</span>
                    </button>
                </form>
            @else
                @if($citizenTip->convertedArticle)
                    <a href="{{ route('admin.articles.edit', $citizenTip->convertedArticle->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md transition-all flex items-center gap-1.5">
                        <span>{{ __('Edit Published Article') }} ↗</span>
                    </a>
                @endif
            @endif
        </div>
    </div>

    <!-- Main Detail Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-6">
        
        <!-- Header: Title, Status, Date -->
        <div class="space-y-3 pb-6 border-b border-slate-100">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase border {{ $citizenTip->status_badge_class }}">
                    {{ __($citizenTip->status_label) }}
                </span>
                <span class="text-xs text-slate-400">
                    {{ __('Received Date:') }} {{ $citizenTip->created_at->format('d M, Y | h:i A') }} ({{ $citizenTip->created_at->diffForHumans() }})
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                {{ $citizenTip->title }}
            </h1>

            <div class="flex items-center gap-3 text-xs text-slate-500 flex-wrap">
                @if($citizenTip->city)
                    <span class="font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200">
                        📍 {{ $citizenTip->city->display_name }} ({{ $citizenTip->state?->display_name }})
                    </span>
                @elseif($citizenTip->state)
                    <span class="font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200">
                        📍 {{ $citizenTip->state->display_name }}
                    </span>
                @endif

                @if($citizenTip->location_name)
                    <span class="bg-slate-100 px-2.5 py-1 rounded-lg font-semibold text-slate-700">
                        {{ __('Location:') }} {{ $citizenTip->location_name }}
                    </span>
                @endif

                <span class="text-slate-400">IP: {{ $citizenTip->ip_address ?: 'Unknown' }}</span>
            </div>
        </div>

        <!-- Description Body -->
        <div class="space-y-2">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">{{ __('Report / Statement:') }}</h3>
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 text-sm leading-relaxed whitespace-pre-line">
                {{ $citizenTip->description }}
            </div>
        </div>

        <!-- Media Evidence Viewer -->
        @if($citizenTip->media_path)
            <div class="space-y-3 pt-2">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">{{ __('Media Evidence:') }}</h3>
                
                @if($citizenTip->isImage())
                    <div class="rounded-2xl overflow-hidden border border-slate-200 max-w-lg bg-black">
                        <a href="{{ $citizenTip->media_url }}" target="_blank">
                            <img src="{{ $citizenTip->media_url }}" alt="Evidence" class="w-full h-auto object-cover hover:opacity-95 transition-opacity">
                        </a>
                        <div class="p-2 bg-slate-900 text-white text-[11px] flex justify-between items-center px-4">
                            <span>{{ __('Click photo to view full size') }}</span>
                            <a href="{{ $citizenTip->media_url }}" target="_blank" download class="text-brand-primary hover:underline font-bold">{{ __('Download') }} 📥</a>
                        </div>
                    </div>
                @elseif($citizenTip->isVideo())
                    <div class="rounded-2xl overflow-hidden border border-slate-200 max-w-lg bg-black">
                        <video controls class="w-full">
                            <source src="{{ $citizenTip->media_url }}">
                            {{ __('Your browser does not support video playback.') }}
                        </video>
                    </div>
                @else
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between max-w-md">
                        <div class="flex items-center gap-3">
                            <span class="p-2 bg-blue-100 text-blue-700 rounded-xl">📄</span>
                            <div>
                                <span class="block text-xs font-bold text-slate-800">{{ __('Attached Document') }}</span>
                                <span class="block text-[11px] text-slate-400">{{ basename($citizenTip->media_path) }}</span>
                            </div>
                        </div>
                        <a href="{{ $citizenTip->media_url }}" target="_blank" class="px-3 py-1.5 bg-slate-900 text-white rounded-lg text-xs font-bold">
                            {{ __('View') }} ↗
                        </a>
                    </div>
                @endif
            </div>
        @endif

        <!-- Citizen / Witness Identity Box -->
        <div class="p-5 rounded-2xl {{ $citizenTip->is_anonymous ? 'bg-purple-50 border-purple-200' : 'bg-slate-50 border-slate-200' }} border space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    @if($citizenTip->is_anonymous)
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-600 animate-pulse"></span>
                        <span class="text-xs font-black uppercase tracking-wider text-purple-900">🔒 {{ __('Whistleblower Citizen (Confidential Identity)') }}</span>
                    @else
                        <span class="text-xs font-black uppercase tracking-wider text-slate-700">{{ __('Eyewitness / Citizen Contact Source') }}</span>
                    @endif
                </div>
                @if($citizenTip->is_anonymous)
                    <span class="text-[11px] font-bold text-purple-700 bg-white px-2 py-0.5 rounded-full border border-purple-200">{{ __('Do Not Disclose Identity') }}</span>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                <div>
                    <span class="block text-[11px] text-slate-400 font-semibold">{{ __('Name:') }}</span>
                    <span class="block font-bold text-slate-800">{{ $citizenTip->witness_name }}</span>
                </div>
                <div>
                    <span class="block text-[11px] text-slate-400 font-semibold">{{ __('Mobile / WhatsApp:') }}</span>
                    @if($citizenTip->witness_phone)
                        <a href="tel:{{ $citizenTip->witness_phone }}" class="font-mono font-bold text-brand-primary hover:underline block">
                            📞 {{ $citizenTip->witness_phone }}
                        </a>
                    @else
                        <span class="text-slate-400">—</span>
                    @endif
                </div>
                <div>
                    <span class="block text-[11px] text-slate-400 font-semibold">{{ __('Email:') }}</span>
                    <span class="block text-slate-700 font-semibold">{{ $citizenTip->witness_email ?: '—' }}</span>
                </div>
            </div>
        </div>

        <!-- Moderation & Status Update Form -->
        <div class="pt-6 border-t border-slate-100">
            <form action="{{ route('admin.citizen-tips.status', $citizenTip->id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="flex items-center gap-2">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-700">{{ __('Status & Editorial Notes:') }}</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">{{ __('Change Status:') }}</label>
                        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold outline-none focus:border-brand-primary">
                            <option value="pending" {{ $citizenTip->status === 'pending' ? 'selected' : '' }}>{{ __('Pending Review') }}</option>
                            <option value="verified" {{ $citizenTip->status === 'verified' ? 'selected' : '' }}>{{ __('Verified / Under Review') }}</option>
                            <option value="converted_to_article" {{ $citizenTip->status === 'converted_to_article' ? 'selected' : '' }}>{{ __('Converted to Article') }}</option>
                            <option value="rejected" {{ $citizenTip->status === 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-600 mb-1">{{ __('Editorial Internal Notes:') }}</label>
                        <input type="text" name="admin_notes" value="{{ old('admin_notes', $citizenTip->admin_notes) }}" placeholder="{{ __('e.g., Reporter dispatched to ground zero...') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs outline-none focus:border-brand-primary">
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="bg-slate-900 hover:bg-black text-white text-xs font-extrabold px-6 py-2.5 rounded-xl shadow-sm transition-all">
                        {{ __('Update Status') }}
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection
