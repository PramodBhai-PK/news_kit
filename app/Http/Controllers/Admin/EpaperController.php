<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\EpaperEdition;
use App\Models\EpaperPage;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EpaperController extends Controller
{
    public function index()
    {
        $editions = EpaperEdition::with(['city', 'pages'])
            ->latest('edition_date')
            ->paginate(12);

        $totalEditions = EpaperEdition::count();
        $totalViews = EpaperEdition::sum('views_count');
        $totalDownloads = EpaperEdition::sum('downloads_count');

        $states = State::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('admin.epaper.index', compact('editions', 'totalEditions', 'totalViews', 'totalDownloads', 'states', 'cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'edition_date' => ['required', 'date'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'cover_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'cover_url' => ['nullable', 'url', 'max:500'],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'max:51200'], // 50MB
            'pdf_url' => ['nullable', 'url', 'max:500'],
            'is_published' => ['nullable', 'boolean'],
            'pages' => ['nullable', 'array'],
            'pages.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:6144'],
        ]);

        $coverPath = $request->input('cover_url');
        if ($request->hasFile('cover_file')) {
            $path = $request->file('cover_file')->store('epaper/covers', 'public');
            $coverPath = Storage::url($path);
        }

        $pdfPath = $request->input('pdf_url');
        if ($request->hasFile('pdf_file')) {
            $path = $request->file('pdf_file')->store('epaper/pdfs', 'public');
            $pdfPath = Storage::url($path);
        }

        $uploadedPages = $request->file('pages', []);
        $totalPages = max(1, count($uploadedPages));

        $edition = EpaperEdition::create([
            'title' => $validated['title'],
            'edition_date' => $validated['edition_date'],
            'state_id' => $validated['state_id'] ?? null,
            'city_id' => $validated['city_id'] ?? null,
            'cover_image' => $coverPath,
            'pdf_file' => $pdfPath,
            'total_pages' => $totalPages,
            'is_published' => $request->boolean('is_published', true),
        ]);

        if (!empty($uploadedPages)) {
            $pageNum = 1;
            foreach ($uploadedPages as $pageFile) {
                $pagePath = $pageFile->store('epaper/pages', 'public');
                EpaperPage::create([
                    'epaper_edition_id' => $edition->id,
                    'page_number' => $pageNum,
                    'page_title' => "Page {$pageNum}",
                    'page_image' => Storage::url($pagePath),
                ]);
                $pageNum++;
            }
        } else {
            // Default page using cover
            EpaperPage::create([
                'epaper_edition_id' => $edition->id,
                'page_number' => 1,
                'page_title' => 'Page 1 - Front Page',
                'page_image' => $coverPath ?: 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=1400&auto=format&fit=crop&q=80',
            ]);
        }

        return back()->with('success', 'E-Paper Edition created successfully!');
    }

    public function toggle(EpaperEdition $epaper)
    {
        $epaper->update(['is_published' => !$epaper->is_published]);

        return back()->with('success', 'Edition status updated successfully!');
    }

    public function destroy(EpaperEdition $epaper)
    {
        $epaper->delete();

        return back()->with('success', 'Edition deleted successfully!');
    }
}
