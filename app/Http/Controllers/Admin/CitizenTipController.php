<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\CitizenTip;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CitizenTipController extends Controller
{
    public function index(Request $request)
    {
        $query = CitizenTip::with(['state', 'city', 'convertedArticle']);

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Search Filter
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('witness_name', 'like', "%{$q}%")
                    ->orWhere('location_name', 'like', "%{$q}%");
            });
        }

        $tips = $query->latest()->paginate(15)->withQueryString();

        $statusCounts = [
            'all' => CitizenTip::count(),
            'pending' => CitizenTip::where('status', 'pending')->count(),
            'verified' => CitizenTip::where('status', 'verified')->count(),
            'converted_to_article' => CitizenTip::where('status', 'converted_to_article')->count(),
            'rejected' => CitizenTip::where('status', 'rejected')->count(),
        ];

        return view('admin.citizen-tips.index', compact('tips', 'statusCounts'));
    }

    public function show(CitizenTip $citizenTip)
    {
        $citizenTip->load(['state', 'city', 'convertedArticle']);
        return view('admin.citizen-tips.show', compact('citizenTip'));
    }

    public function updateStatus(Request $request, CitizenTip $citizenTip)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,verified,converted_to_article,rejected'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $citizenTip->update($validated);

        return back()->with('success', 'नागरिक टिप की स्थिति सफलतापूर्वक अपडेट कर दी गई है!');
    }

    /**
     * 1-Click Convert Citizen Tip into a Draft News Article
     */
    public function convertToArticle(CitizenTip $citizenTip)
    {
        // Choose default or first category
        $category = Category::first();
        if (!$category) {
            $category = Category::create([
                'name' => 'General News',
                'hindi_name' => 'सामान्य समाचार',
                'slug' => 'general-news',
                'color' => '#E11D48',
            ]);
        }

        $baseSlug = Str::slug($citizenTip->title);
        $slug = $baseSlug ?: 'citizen-tip-' . $citizenTip->id;
        $counter = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $witnessAttribution = $citizenTip->is_anonymous 
            ? 'विशेष नागरिक संवाददाता (पहचान गोपनीय)' 
            : ($citizenTip->witness_name ?: 'सिटीज़न रिपोर्टर');

        $articleContent = "<p>" . nl2br(e($citizenTip->description)) . "</p>" . 
            "<p><strong>स्रोत (नागरिक पत्रकारिता डेस्क):</strong> " . e($witnessAttribution) . "</p>";

        $featuredImage = null;
        if ($citizenTip->isImage() && $citizenTip->media_path) {
            $featuredImage = $citizenTip->media_path;
        }

        $article = Article::create([
            'title' => $citizenTip->title,
            'slug' => $slug,
            'excerpt' => Str::limit($citizenTip->description, 180),
            'content' => $articleContent,
            'category_id' => $category->id,
            'user_id' => auth()->id(),
            'status' => 'draft',
            'is_featured' => false,
            'is_breaking' => false,
            'featured_image' => $featuredImage,
            'location_name' => $citizenTip->location_name,
            'state_id' => $citizenTip->state_id,
            'city_id' => $citizenTip->city_id,
            'views_count' => 0,
            'reading_time' => 2,
        ]);

        $citizenTip->update([
            'status' => 'converted_to_article',
            'converted_article_id' => $article->id,
            'admin_notes' => 'कन्वर्टेड बाय ' . auth()->user()->name . ' ऑन ' . now()->format('d M, Y h:i A'),
        ]);

        return redirect()->route('admin.articles.edit', $article->id)
            ->with('success', 'नागरिक टिप को सफलतापूर्वक न्यूज़ आर्टिकल (ड्राफ्ट) में बदल दिया गया है! अब आप इसे एडिट कर प्रकाशित कर सकते हैं।');
    }

    public function destroy(CitizenTip $citizenTip)
    {
        $citizenTip->delete();
        return redirect()->route('admin.citizen-tips.index')
            ->with('success', 'नागरिक टिप को सफलतापूर्वक हटा दिया गया है।');
    }
}
