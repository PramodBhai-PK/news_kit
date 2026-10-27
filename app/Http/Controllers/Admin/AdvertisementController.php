<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    public function index()
    {
        $ads = Advertisement::orderBy('placement')->orderBy('order')->latest()->paginate(15);
        $totalViews = Advertisement::sum('views_count');
        $totalClicks = Advertisement::sum('clicks_count');
        $overallCtr = $totalViews > 0 ? round(($totalClicks / $totalViews) * 100, 1) : 0;

        return view('admin.advertisements.index', compact('ads', 'totalViews', 'totalClicks', 'overallCtr'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:image,code'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif,svg', 'max:4096'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'destination_url' => ['nullable', 'url', 'max:500'],
            'html_code' => ['nullable', 'string'],
            'placement' => ['required', 'string', 'max:50'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $imagePath = $request->input('image_url');
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('ads', 'public');
            $imagePath = Storage::url($path);
        }

        Advertisement::create([
            'title' => $validated['title'] ?? null,
            'type' => $validated['type'],
            'image_url' => $imagePath,
            'destination_url' => $validated['destination_url'] ?? null,
            'html_code' => $validated['html_code'] ?? null,
            'placement' => $validated['placement'] ?? 'sidebar',
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'विज्ञापन (Advertisement) सफलतापूर्वक जोड़ दिया गया है!');
    }

    public function update(Request $request, Advertisement $advertisement)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:image,code'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif,svg', 'max:4096'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'destination_url' => ['nullable', 'url', 'max:500'],
            'html_code' => ['nullable', 'string'],
            'placement' => ['required', 'string', 'max:50'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $imagePath = $advertisement->image_url;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('ads', 'public');
            $imagePath = Storage::url($path);
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }

        $advertisement->update([
            'title' => $validated['title'] ?? null,
            'type' => $validated['type'],
            'image_url' => $imagePath,
            'destination_url' => $validated['destination_url'] ?? null,
            'html_code' => $validated['html_code'] ?? null,
            'placement' => $validated['placement'] ?? 'sidebar',
            'order' => $validated['order'] ?? $advertisement->order,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'विज्ञापन सफलतापूर्वक अपडेट हो गया है!');
    }

    public function toggle(Advertisement $advertisement)
    {
        $advertisement->update(['is_active' => !$advertisement->is_active]);

        return back()->with('success', 'विज्ञापन स्टेटस अपडेट हो गया है!');
    }

    public function destroy(Advertisement $advertisement)
    {
        $advertisement->delete();

        return back()->with('success', 'विज्ञापन सफलतापूर्वक हटा दिया गया है।');
    }
}
