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
                'message' => 'Thank you! Your email (' . $subscriber->email . ') has been successfully subscribed to our newsletter.',
            ]);
        }

        return back()->with('success', 'Thank you! Your email has been successfully subscribed to our newsletter.');
    }
}
