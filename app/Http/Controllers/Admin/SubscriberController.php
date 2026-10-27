<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscriber::latest();

        if ($request->filled('q')) {
            $query->where('email', 'like', '%' . $request->input('q') . '%');
        }

        $subscribers = $query->paginate(20)->withQueryString();
        $totalCount = Subscriber::count();
        $activeCount = Subscriber::where('is_active', true)->count();

        return view('admin.subscribers.index', compact('subscribers', 'totalCount', 'activeCount'));
    }

    public function toggle(Subscriber $subscriber)
    {
        $subscriber->update(['is_active' => !$subscriber->is_active]);

        return back()->with('success', 'सब्सक्राइबर स्टेटस अपडेट हो गया है!');
    }

    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        return back()->with('success', 'ईमेल सब्सक्राइबर सूची से हटा दिया गया है।');
    }
}
