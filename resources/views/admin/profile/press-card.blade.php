@extends('layouts.admin')

@section('title', __('Digital Press ID Card - ') . $user->name)
@section('page_title', __('Official Digital Press ID Card'))

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Action & Navigation Bar -->
    <div class="flex flex-wrap items-center justify-between gap-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ auth()->user()->isAdmin() && request()->routeIs('admin.users.*') ? route('admin.users.index') : route('admin.profile.edit') }}" 
               class="p-2 text-slate-400 hover:text-slate-800 rounded-xl hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-extrabold text-slate-900">{{ $user->name }}</h2>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase border {{ $user->press_card_status_badge_class }}">
                        {{ __($user->press_card_status_label) }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-mono">{{ __('ID Code:') }} #{{ $user->press_id_code }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <!-- Public Verification Live Link -->
            <a href="{{ $user->press_card_verification_url }}" target="_blank" 
               class="px-4 py-2.5 rounded-xl border border-slate-300 hover:border-brand-primary text-slate-700 hover:text-brand-primary text-xs font-bold transition-all flex items-center gap-1.5 shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ __('View Live QR Verification Page') }} ↗</span>
            </a>

            <!-- Print Card Button -->
            <button type="button" onclick="window.print()" 
                    class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-black text-white text-xs font-extrabold transition-all shadow-md flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>🖨️ {{ __('Print Press Card (Print / PDF)') }}</span>
            </button>
        </div>
    </div>

    <!-- Printable ID Card Preview Container -->
    <div id="printable-press-card-wrapper" class="bg-slate-100 p-6 sm:p-10 rounded-3xl border border-slate-200">
        
        <div class="text-center mb-6 no-print">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-rose-100 text-brand-primary border border-rose-200">
                {{ __('Official Digital Press ID Card (Standard Lanyard & CR-80 PVC Format)') }}
            </span>
            <p class="text-xs text-slate-500 mt-1">{{ __('Both front and back sides can be printed together on A4 / PVC card sheet') }}</p>
        </div>

        <!-- 2 SIDES: Front & Back Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto items-center justify-center">
            
            <!-- ========================================== -->
            <!-- 1. FRONT SIDE CARD                         -->
            <!-- ========================================== -->
            <div class="press-card-box w-full max-w-[360px] mx-auto bg-white rounded-3xl overflow-hidden shadow-2xl border border-slate-300 relative flex flex-col justify-between text-slate-900 min-h-[560px]">
                
                <!-- Lanyard Clip Punch Indicator -->
                <div class="absolute top-2 left-1/2 -translate-x-1/2 w-10 h-2 bg-slate-200 rounded-full z-20"></div>

                <!-- Top Header Band: Brand Logo & Press Alert -->
                <div class="bg-gradient-to-r from-slate-950 via-rose-950 to-slate-950 text-white p-4 pt-5 border-b-2 border-brand-primary relative">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <img src="{{ setting('logo_url', '/images/logo.svg') }}" alt="Logo" class="h-8 w-auto">
                            <div>
                                <span class="block font-black text-sm tracking-tight leading-none text-white">{{ setting('site_name', 'NewsKit Portal') }}</span>
                                <span class="block text-[9px] font-semibold text-rose-300 mt-0.5">{{ __('National Digital News Media') }}</span>
                            </div>
                        </div>
                        <div class="px-2.5 py-1 rounded-lg bg-rose-600 text-white font-black text-xs uppercase tracking-widest shadow-xs">
                            PRESS
                        </div>
                    </div>
                </div>

                <!-- Card Body: Photo, Name, Designation, QR -->
                <div class="p-5 flex-1 flex flex-col items-center text-center space-y-3">
                    
                    <!-- Reporter Photo with Ring -->
                    <div class="relative mt-1">
                        <img src="{{ $user->avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=250&auto=format&fit=crop&q=80' }}" 
                             alt="{{ $user->name }}" 
                             class="w-28 h-28 rounded-2xl object-cover ring-3 ring-brand-primary shadow-lg">
                        <span class="absolute -bottom-1 -right-1 p-1 bg-emerald-600 text-white rounded-full shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                    </div>

                    <!-- Name & Designation -->
                    <div class="space-y-0.5">
                        <h3 class="font-black text-lg text-slate-900 tracking-tight leading-tight">{{ $user->name }}</h3>
                        <p class="text-xs font-bold text-brand-primary leading-tight">{{ $user->designation ?: __($user->role_label) }}</p>
                        <p class="text-[11px] font-semibold text-slate-500">📍 {{ $user->bureau_location ?: __('National News Bureau') }}</p>
                    </div>

                    <!-- Press ID Pill & Blood Group -->
                    <div class="flex items-center gap-2 pt-0.5">
                        <span class="font-mono text-xs font-extrabold bg-slate-900 text-emerald-400 px-3 py-1 rounded-lg shadow-2xs">
                            {{ $user->press_id_code }}
                        </span>
                        @if($user->blood_group)
                            <span class="text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 px-2 py-1 rounded-lg">
                                BG: {{ $user->blood_group }}
                            </span>
                        @endif
                    </div>

                    <!-- Live Dynamic QR Code Section -->
                    <div class="pt-2 flex flex-col items-center">
                        <div class="p-2 bg-white rounded-xl border border-slate-200 shadow-xs">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($user->press_card_verification_url) }}" 
                                 alt="QR Code" 
                                 class="w-20 h-20 object-contain">
                        </div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-1">
                            {{ __('Scan for live verification (Live QR)') }}
                        </span>
                    </div>

                </div>

                <!-- Front Card Footer -->
                <div class="bg-slate-50 border-t border-slate-200 px-4 py-2.5 flex items-center justify-between text-[10px] text-slate-600">
                    <div>
                        <span class="block font-semibold">{{ __('Valid Till:') }}</span>
                        <span class="font-extrabold text-slate-900">{{ $user->id_card_expires_at ? $user->id_card_expires_at->format('d M, Y') : '31 Dec, 2027' }}</span>
                    </div>
                    <div class="text-right">
                        <span class="block text-[8px] font-bold text-slate-400 uppercase">{{ __('Authorized Signature') }}</span>
                        <span class="font-serif italic font-bold text-slate-800 text-xs">Editor-in-Chief</span>
                    </div>
                </div>

            </div>

            <!-- ========================================== -->
            <!-- 2. BACK SIDE CARD                          -->
            <!-- ========================================== -->
            <div class="press-card-box w-full max-w-[360px] mx-auto bg-white rounded-3xl overflow-hidden shadow-2xl border border-slate-300 relative flex flex-col justify-between text-slate-900 min-h-[560px]">
                
                <!-- Lanyard Clip Punch Indicator -->
                <div class="absolute top-2 left-1/2 -translate-x-1/2 w-10 h-2 bg-slate-200 rounded-full z-20"></div>

                <!-- Back Header -->
                <div class="bg-slate-900 text-white p-4 pt-5 border-b-2 border-brand-primary text-center">
                    <span class="block text-[10px] font-black uppercase tracking-widest text-amber-400">MEDIA ACCREDITATION CARD</span>
                    <span class="block text-xs font-extrabold text-white">{{ __('Press Rules & Legal Accreditation') }}</span>
                </div>

                <!-- Back Content -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-3 text-xs leading-relaxed">
                    
                    <!-- Constitutional Rights -->
                    <div class="p-3 rounded-xl bg-amber-50/70 border border-amber-200 text-amber-950 text-[11px] space-y-1">
                        <span class="font-extrabold block text-amber-900 uppercase">{{ __('Freedom of Speech & Press Freedom:') }}</span>
                        <p class="leading-tight">
                            {{ __('The cardholder has legal accreditation for gathering news, press conferences, and field reporting. Cooperation from law enforcement authorities is requested.') }}
                        </p>
                    </div>

                    <!-- Instructions Table -->
                    <div class="space-y-2 text-[11px] border-y border-slate-100 py-3">
                        <div class="flex justify-between">
                            <span class="text-slate-400 font-semibold">{{ __('Issued Date:') }}</span>
                            <span class="font-bold text-slate-800">{{ $user->id_card_issued_at ? $user->id_card_issued_at->format('d M, Y') : '01 Jan, 2026' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 font-semibold">{{ __('Phone / Mobile:') }}</span>
                            <span class="font-bold text-slate-800 font-mono">{{ $user->phone ?: '+1 555 123 4567' }}</span>
                        </div>
                        @if($user->emergency_contact)
                        <div class="flex justify-between">
                            <span class="text-slate-400 font-semibold">{{ __('Emergency Contact:') }}</span>
                            <span class="font-bold text-rose-600 font-mono">{{ $user->emergency_contact }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-slate-400 font-semibold">{{ __('Head Office:') }}</span>
                            <span class="font-semibold text-slate-700 text-right truncate max-w-[180px]">{{ __('Central Newsroom Bureau') }}</span>
                        </div>
                    </div>

                    <!-- Anti-Fraud Barcode Pattern -->
                    <div class="text-center pt-1">
                        <div class="h-6 w-full bg-slate-900 rounded flex items-center justify-around px-2">
                            @for($i = 0; $i < 38; $i++)
                                <span class="h-full bg-white {{ $i % 3 === 0 ? 'w-1' : ($i % 2 === 0 ? 'w-0.5' : 'w-1.5') }}"></span>
                            @endfor
                        </div>
                        <span class="font-mono text-[9px] text-slate-400 mt-1 block">SECURITY SERIAL: {{ md5($user->id . $user->press_id_code) }}</span>
                    </div>

                    <p class="text-[9px] text-slate-400 text-center leading-tight">
                        {{ __('This card is non-transferable. If found, please return to the nearest police station or office address.') }}
                    </p>

                </div>

                <!-- Back Footer -->
                <div class="bg-slate-900 text-slate-400 px-4 py-2.5 text-center text-[10px]">
                    <span class="text-white font-bold">{{ setting('site_name', 'NewsKit Portal') }}</span> • Digital Media Desk
                </div>

            </div>

        </div>

    </div>

    <!-- Admin Credential Editor (Visible to Super Admin) -->
    @if(auth()->user()->isAdmin())
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4 no-print">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="p-1.5 rounded-lg bg-amber-100 text-amber-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </span>
                <h3 class="font-extrabold text-sm text-slate-900">{{ __('Edit Press ID Card Credentials (Admin Only)') }}</h3>
            </div>
            <span class="text-xs text-slate-400">{{ __('Administrator can modify or suspend any staff member press card') }}</span>
        </div>

        <form action="{{ route('admin.users.press-card.update', $user->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            @csrf
            
            <div>
                <label class="block font-bold text-slate-700 mb-1">{{ __('Official Press ID Code') }} *</label>
                <input type="text" name="press_id_code" value="{{ old('press_id_code', $user->press_id_code) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono font-bold outline-none focus:border-brand-primary">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">{{ __('Designation') }} *</label>
                <input type="text" name="designation" value="{{ old('designation', $user->designation ?: $user->role_label) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold outline-none focus:border-brand-primary">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">{{ __('Bureau / Region') }} *</label>
                <input type="text" name="bureau_location" value="{{ old('bureau_location', $user->bureau_location ?: 'Central News Bureau') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs outline-none focus:border-brand-primary">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">{{ __('Blood Group') }}</label>
                <input type="text" name="blood_group" value="{{ old('blood_group', $user->blood_group) }}" placeholder="O+, B+, A+..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs outline-none focus:border-brand-primary">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">{{ __('Issued Date') }} *</label>
                <input type="date" name="id_card_issued_at" value="{{ old('id_card_issued_at', $user->id_card_issued_at ? $user->id_card_issued_at->format('Y-m-d') : now()->format('Y-m-d')) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs outline-none focus:border-brand-primary">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">{{ __('Valid Till') }} *</label>
                <input type="date" name="id_card_expires_at" value="{{ old('id_card_expires_at', $user->id_card_expires_at ? $user->id_card_expires_at->format('Y-m-d') : now()->addYears(2)->format('Y-m-d')) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs outline-none focus:border-brand-primary">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">{{ __('Emergency Contact Phone') }}</label>
                <input type="text" name="emergency_contact" value="{{ old('emergency_contact', $user->emergency_contact) }}" placeholder="+1 ..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs outline-none focus:border-brand-primary">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">{{ __('Card Status') }} *</label>
                <select name="id_card_status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold outline-none focus:border-brand-primary">
                    <option value="active" {{ old('id_card_status', $user->id_card_status) === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="suspended" {{ old('id_card_status', $user->id_card_status) === 'suspended' ? 'selected' : '' }}>{{ __('Suspended') }}</option>
                    <option value="expired" {{ old('id_card_status', $user->id_card_status) === 'expired' ? 'selected' : '' }}>{{ __('Expired') }}</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-brand-primary hover:brightness-110 text-white font-extrabold py-2.5 px-4 rounded-xl shadow-sm transition-all">
                    {{ __('Save Credentials') }}
                </button>
            </div>
        </form>
    </div>
    @endif

</div>

<!-- Specific Print CSS to isolate ID card on clean page -->
<style>
@media print {
    body * {
        visibility: hidden;
    }
    aside, header, .no-print {
        display: none !important;
    }
    #printable-press-card-wrapper, #printable-press-card-wrapper * {
        visibility: visible;
    }
    #printable-press-card-wrapper {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
    }
    .press-card-box {
        box-shadow: none !important;
        border: 1px solid #cbd5e1 !important;
        page-break-inside: avoid;
    }
}
</style>
@endsection
