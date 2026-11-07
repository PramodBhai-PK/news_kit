@extends('layouts.portal')

@section('title', __('Digital Press Card Verification') . ' - ' . $user->name . ' (' . $user->press_id_code . ')')
@section('meta_description', __('Official Digital Press Credential QR Live Verification Portal') . ' - ' . setting('site_name', 'The LallanTop News'))

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">
    
    <!-- Top Back Link -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('portal.index') }}" class="text-xs font-bold text-slate-500 hover:text-brand-primary flex items-center gap-1.5 transition-colors">
            <span>{{ __('← Return to Main Portal') }}</span>
        </a>
        <span class="text-[11px] font-mono text-slate-400">ID VERIFY: #{{ $user->press_id_code }}</span>
    </div>

    <!-- Official Verification Certificate Box -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden relative">
        
        <!-- Security Pattern Ribbon -->
        <div class="h-3 bg-gradient-to-r from-brand-primary via-amber-500 to-purple-600"></div>

        <!-- 1. Live Verification Status Banner -->
        @if($isValid)
            <div class="p-6 bg-emerald-50 dark:bg-emerald-950/40 border-b border-emerald-200 dark:border-emerald-800/60 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-lg shadow-emerald-600/30">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-600 text-white shadow-2xs">{{ __('VERIFIED • OFFICIAL') }}</span>
                        <span class="text-xs font-bold text-emerald-800 dark:text-emerald-300">{{ __('Authorized Digital Press Representative') }}</span>
                    </div>
                    <h2 class="text-base sm:text-lg font-black text-emerald-950 dark:text-emerald-100 leading-tight mt-0.5">
                        {{ __('Active & Valid Press Credential') }}
                    </h2>
                </div>
            </div>
        @else
            <div class="p-6 bg-rose-50 dark:bg-rose-950/40 border-b border-rose-200 dark:border-rose-800/60 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-lg shadow-rose-600/30">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-rose-600 text-white">{{ __('ALERT • INVALID') }}</span>
                    </div>
                    <h2 class="text-base sm:text-lg font-black text-rose-950 dark:text-rose-100 leading-tight mt-0.5">
                        {{ __('This press credential is currently inactive or expired') }}
                    </h2>
                </div>
            </div>
        @endif

        <!-- 2. Reporter Profile Card -->
        <div class="p-6 sm:p-8 space-y-6">
            
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                <!-- Reporter Photo with Hologram Ring -->
                <div class="relative shrink-0">
                    <img src="{{ $user->avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&auto=format&fit=crop&q=80' }}" 
                         alt="{{ $user->name }}" 
                         class="w-32 h-32 sm:w-36 sm:h-36 rounded-2xl object-cover ring-4 ring-brand-primary shadow-xl">
                    <span class="absolute -bottom-2 -right-2 p-1.5 rounded-xl bg-emerald-600 text-white shadow-md" title="{{ __('Biometric Photo Verified') }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </span>
                </div>

                <!-- Info Details -->
                <div class="space-y-2 flex-1">
                    <div class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                        {{ $user->role_label }}
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white leading-tight">
                        {{ $user->name }}
                    </h1>

                    <p class="text-sm font-bold text-brand-primary">
                        {{ $user->designation ?: $user->role_label }}
                    </p>

                    <div class="pt-2 flex flex-wrap items-center justify-center sm:justify-start gap-2">
                        <span class="text-xs font-mono font-bold px-3 py-1.5 rounded-xl bg-slate-900 text-emerald-400 border border-slate-800">
                            {{ __('Press ID Code:') }} {{ $user->press_id_code }}
                        </span>
                        @if($user->blood_group)
                            <span class="text-xs font-bold px-2.5 py-1.5 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 border border-rose-200 dark:border-rose-900">
                                {{ __('Blood Group:') }} {{ $user->blood_group }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Credentials Grid Table -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-100 dark:border-slate-800 text-xs">
                
                <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1">
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Bureau Location:') }}</span>
                    <span class="block font-bold text-slate-800 dark:text-slate-200 text-sm">
                        📍 {{ $user->bureau_location ?: __('National News Bureau') }}
                    </span>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1">
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('News Organization:') }}</span>
                    <span class="block font-bold text-slate-800 dark:text-slate-200 text-sm">
                        {{ $siteName }}
                    </span>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1">
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Issue Date:') }}</span>
                    <span class="block font-semibold text-slate-700 dark:text-slate-300">
                        {{ $user->id_card_issued_at ? $user->id_card_issued_at->format('d M, Y') : ($user->created_at ? $user->created_at->format('d M, Y') : '01 Jan, 2026') }}
                    </span>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1">
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Valid Till / Expiry:') }}</span>
                    <span class="block font-bold text-slate-800 dark:text-slate-200">
                        {{ $user->id_card_expires_at ? $user->id_card_expires_at->format('d M, Y') : '31 Dec, 2027' }}
                    </span>
                </div>

                @if($user->emergency_contact)
                <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-1 sm:col-span-2">
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Emergency Contact:') }}</span>
                    <span class="block font-mono font-bold text-slate-800 dark:text-slate-200">
                        📞 {{ $user->emergency_contact }}
                    </span>
                </div>
                @endif
            </div>

            <!-- Anti-Fraud Dynamic Timestamp Live Ticker -->
            <div class="p-4 rounded-2xl bg-slate-900 text-slate-300 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                    <span class="font-bold text-white">{{ __('Live Server Verification Timestamp:') }}</span>
                </div>
                <div class="font-mono text-emerald-400 font-bold" id="live-verify-clock">
                    {{ now()->format('d M, Y | h:i:s A') }} UTC
                </div>
            </div>

            <!-- Legal Authorities & Police Instructions Box -->
            <div class="p-5 rounded-2xl bg-amber-50/70 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/60 space-y-2 text-xs text-amber-950 dark:text-amber-200 leading-relaxed">
                <div class="flex items-center gap-2 font-black text-amber-900 dark:text-amber-100 uppercase tracking-wide">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ __('Important Advisory for Law Enforcement & Public Authorities:') }}</span>
                </div>
                <p>
                    {{ __('The cardholder :name is an authorized credentialed media representative of :site. The bearer is authorized to gather information, conduct interviews, and report on public events.', ['name' => $user->name, 'site' => $siteName]) }}
                </p>
                <p class="text-[11px] text-amber-800 dark:text-amber-300">
                    {{ __('For any verification queries, please contact the central news desk directly.') }}
                </p>
            </div>

        </div>

        <!-- Card Footer -->
        <div class="p-4 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-200 dark:border-slate-800 text-center text-xs text-slate-500">
            <span>© {{ date('Y') }} {{ $siteName }} • {{ __('Official Press Verification System') }}</span>
        </div>

    </div>

</div>

<!-- Real-time Live Clock Script to Prevent Static Screenshot Spoofing -->
<script>
    setInterval(function() {
        const el = document.getElementById('live-verify-clock');
        if (el) {
            const now = new Date();
            el.textContent = now.toLocaleDateString(undefined, { day: '2-digit', month: 'short', year: 'numeric' }) + ' | ' + now.toLocaleTimeString() + ' UTC';
        }
    }, 1000);
</script>
@endsection
