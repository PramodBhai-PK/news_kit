@extends('layouts.admin')

@section('title', __('My Account'))
@section('page_title', __('Account & Profile Settings'))

@section('content')
<div class="max-w-4xl space-y-8">

    <!-- 1. Profile Overview Header Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs flex flex-col sm:flex-row items-center gap-6">
        <img src="{{ $user->avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80' }}" 
             alt="{{ $user->name }}" 
             class="w-24 h-24 rounded-full object-cover ring-4 ring-slate-100 shadow-md">

        <div class="space-y-2 text-center sm:text-left flex-1">
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                <h2 class="text-xl font-black text-slate-900">{{ $user->name }}</h2>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $user->role_badge_class }}">
                    {{ __($user->role_label) }}
                </span>
            </div>

            <p class="text-xs text-slate-500">
                ✉️ {{ $user->email }} @if($user->phone) • 📞 {{ $user->phone }} @endif
            </p>

            <div class="pt-2 flex flex-wrap items-center justify-center sm:justify-start gap-6 text-xs text-slate-600 font-semibold border-t border-slate-100">
                <span>📰 {{ __('Total Articles:') }} <strong class="text-slate-900 text-sm font-black">{{ $myStats['total_articles'] }}</strong></span>
                <span>✅ {{ __('Published:') }} <strong class="text-emerald-600 text-sm font-black">{{ $myStats['published_articles'] }}</strong></span>
                <span>👁️ {{ __('Total Views:') }} <strong class="text-blue-600 text-sm font-black">{{ number_format($myStats['total_views']) }}</strong></span>
            </div>
        </div>
    </div>

    <!-- 2. Edit Profile Form & Change Password Form -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- Personal Details -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-5">
            <div class="pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-900 text-sm">{{ __('Personal Info') }}</h3>
                <p class="text-xs text-slate-400">{{ __('This information will appear in the author bio below your published stories.') }}</p>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">{{ __('Full Name') }} *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full bg-slate-50 text-slate-900 text-xs font-semibold rounded-xl px-3.5 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">{{ __('Email Address') }} *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full bg-slate-50 text-slate-900 text-xs font-semibold rounded-xl px-3.5 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">{{ __('Phone Number') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+1 555 123 4567"
                           class="w-full bg-slate-50 text-slate-900 text-xs font-semibold rounded-xl px-3.5 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">{{ __('Author Bio') }}</label>
                    <textarea name="bio" rows="3" placeholder="{{ __('Write a brief bio about yourself...') }}" 
                              class="w-full bg-slate-50 text-slate-900 text-xs rounded-xl p-3 border border-slate-200 focus:border-brand-primary outline-none">{{ old('bio', $user->bio) }}</textarea>
                </div>

                <!-- Avatar Upload / URL -->
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('Update Avatar') }}</label>
                    <input type="file" name="avatar_file" accept="image/*" class="text-xs text-slate-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-brand-primary cursor-pointer w-full">
                    <input type="url" name="avatar_url" value="{{ old('avatar_url', $user->avatar) }}" placeholder="{{ __('or Image URL: https://...') }}" class="w-full bg-white text-xs rounded-lg px-3 py-2 border border-slate-200 focus:border-brand-primary outline-none">
                </div>

                <button type="submit" class="w-full bg-brand-primary hover:brightness-110 text-white font-bold text-xs py-2.5 rounded-xl shadow-md transition-all">
                    {{ __('Save Changes') }}
                </button>
            </form>
        </div>

        <!-- Security / Password Change -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-5 h-fit">
            <div class="pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-900 text-sm">{{ __('Change Password') }}</h3>
                <p class="text-xs text-slate-400">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
            </div>

            <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">{{ __('Current Password') }} *</label>
                    <input type="password" name="current_password" required placeholder="••••••••" 
                           class="w-full bg-slate-50 text-slate-900 text-xs rounded-xl px-3.5 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">{{ __('New Password') }} *</label>
                    <input type="password" name="new_password" required placeholder="{{ __('Minimum 6 characters') }}" 
                           class="w-full bg-slate-50 text-slate-900 text-xs rounded-xl px-3.5 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">{{ __('Confirm New Password') }} *</label>
                    <input type="password" name="new_password_confirmation" required placeholder="{{ __('Confirm new password') }}" 
                           class="w-full bg-slate-50 text-slate-900 text-xs rounded-xl px-3.5 py-2.5 border border-slate-200 focus:border-brand-primary outline-none">
                </div>

                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs py-2.5 rounded-xl shadow-md transition-all">
                    {{ __('Update Password') }}
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
