<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();

        // Stats for current user
        $myStats = [
            'total_articles' => $user->articles()->count(),
            'published_articles' => $user->articles()->where('status', 'published')->count(),
            'total_views' => $user->articles()->sum('views_count'),
        ];

        return view('admin.profile.edit', compact('user', 'myStats'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'avatar_url' => ['nullable', 'url', 'max:500'],
        ]);

        $avatarPath = $user->avatar;
        if ($request->hasFile('avatar_file')) {
            $path = $request->file('avatar_file')->store('avatars', 'public');
            $avatarPath = Storage::url($path);
        } elseif ($request->filled('avatar_url')) {
            $avatarPath = $request->input('avatar_url');
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'avatar' => $avatarPath,
        ]);

        return back()->with('success', 'आपकी प्रोफ़ाइल जानकारी सफलतापूर्वक अपडेट कर दी गई है!');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'वर्तमान पासवर्ड ग़लत है। कृपया सही पासवर्ड दर्ज करें।']);
        }

        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return back()->with('success', 'आपका पासवर्ड सफलतापूर्वक बदल दिया गया है!');
    }

    public function pressCard()
    {
        $user = auth()->user();
        $user->ensurePressCardInitialized();

        return view('admin.profile.press-card', compact('user'));
    }
}
