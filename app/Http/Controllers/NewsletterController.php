<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $subscriber = Subscriber::firstOrCreate(
            ['email' => strtolower(trim($validated['email']))],
            [
                'is_active' => true,
                'ip_address' => $request->ip(),
            ]
        );

        if (!$subscriber->wasRecentlyCreated && !$subscriber->is_active) {
            $subscriber->update(['is_active' => true]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'धन्यवाद! आपका ईमेल (' . $subscriber->email . ') दैनिक न्यूज़लेटर के लिए सफलतापूर्वक पंजीकृत हो गया है।',
            ]);
        }

        return back()->with('success', 'धन्यवाद! आपका ईमेल सफलतापूर्वक दैनिक न्यूज़लेटर के लिए पंजीकृत हो गया है।');
    }
}
