<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Only Admin and Editor can access team management
        if (!auth()->user()->isAdmin() && !auth()->user()->isEditor()) {
            abort(403, 'This section can only be accessed by Admin or Editor.');
        }

        $query = User::withCount('articles');

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Search filter
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        $users = $query->latest()->paginate(12)->withQueryString();

        // Count metrics by role
        $roleCounts = [
            'all' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'editor' => User::where('role', 'editor')->count(),
            'reporter' => User::where('role', 'reporter')->count(),
            'publisher' => User::where('role', 'publisher')->count(),
        ];

        return view('admin.users.index', compact('users', 'roleCounts'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only Admin can add new team members.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,editor,publisher,reporter'],
            'phone' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'avatar_url' => ['nullable', 'url', 'max:500'],
        ]);

        $avatarPath = $request->input('avatar_url');
        if ($request->hasFile('avatar_file')) {
            $path = $request->file('avatar_file')->store('avatars', 'public');
            $avatarPath = Storage::url($path);
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'avatar' => $avatarPath,
            'is_active' => true,
        ]);

        return back()->with('success', 'New team member (' . ucfirst($validated['role']) . ') added successfully!');
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only Admin can update team members.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:admin,editor,publisher,reporter'],
            'phone' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'avatar_url' => ['nullable', 'url', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $avatarPath = $user->avatar;
        if ($request->hasFile('avatar_file')) {
            $path = $request->file('avatar_file')->store('avatars', 'public');
            $avatarPath = Storage::url($path);
        } elseif ($request->filled('avatar_url')) {
            $avatarPath = $request->input('avatar_url');
        }

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'avatar' => $avatarPath,
            'is_active' => $request->boolean('is_active', true),
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return back()->with('success', 'Team member details updated successfully!');
    }

    public function toggleStatus(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only Admin can change user status.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', 'User status updated successfully!');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only Admin can delete team members.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->email === 'admin@newskit.com') {
            return back()->with('error', 'The primary super-admin account cannot be deleted.');
        }

        $user->delete();

        return back()->with('success', 'Team member deleted successfully.');
    }

    public function pressCard(User $user)
    {
        $user->ensurePressCardInitialized();

        return view('admin.profile.press-card', compact('user'));
    }

    public function updatePressCard(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only Admin can update press card credentials.');
        }

        $validated = $request->validate([
            'press_id_code' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'designation' => ['required', 'string', 'max:255'],
            'blood_group' => ['nullable', 'string', 'max:10'],
            'bureau_location' => ['required', 'string', 'max:255'],
            'emergency_contact' => ['nullable', 'string', 'max:50'],
            'id_card_issued_at' => ['required', 'date'],
            'id_card_expires_at' => ['required', 'date', 'after_or_equal:id_card_issued_at'],
            'id_card_status' => ['required', 'in:active,suspended,expired'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Press card details updated successfully!');
    }
}
