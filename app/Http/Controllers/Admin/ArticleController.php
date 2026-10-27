<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\City;
use App\Models\State;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['category', 'author', 'state', 'city']);

        // Scope to reporter's own articles if user is a reporter
        if (auth()->user()->isReporter()) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('excerpt', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('state_id')) {
            $query->where('state_id', $request->input('state_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $articles = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $states = State::where('is_active', true)->orderBy('order')->get();

        return view('admin.articles.index', compact('articles', 'categories', 'states'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $tags = Tag::orderBy('name')->get();
        $states = State::where('is_active', true)->with('cities')->orderBy('order')->get();

        return view('admin.articles.create', compact('categories', 'tags', 'states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'location_name' => ['nullable', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'featured_image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif', 'max:4096'],
            'featured_image_url' => ['nullable', 'url', 'max:500'],
            'image_caption' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:published,draft,archived'],
            'is_breaking' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_trending' => ['nullable', 'boolean'],
            'tags' => ['nullable', 'string'],
            'custom_ad_title' => ['nullable', 'string', 'max:255'],
            'custom_ad_type' => ['nullable', 'in:none,image,code'],
            'custom_ad_image_file' => ['nullable', 'image', 'max:4096'],
            'custom_ad_image' => ['nullable', 'url', 'max:500'],
            'custom_ad_link' => ['nullable', 'url', 'max:500'],
            'custom_ad_code' => ['nullable', 'string'],
            'video_type' => ['nullable', 'in:none,youtube,direct'],
            'video_url' => ['nullable', 'string', 'max:500'],
            'direct_video_url' => ['nullable', 'string', 'max:500'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,webm,ogg,mov', 'max:51200'],
        ]);

        $imagePath = $request->input('featured_image_url');
        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('articles', 'public');
            $imagePath = Storage::url($path);
        }

        $adImagePath = $request->input('custom_ad_image');
        if ($request->hasFile('custom_ad_image_file')) {
            $path = $request->file('custom_ad_image_file')->store('ads', 'public');
            $adImagePath = Storage::url($path);
        }

        $videoType = $validated['video_type'] ?? 'none';
        $videoUrl = $validated['video_url'] ?? null;
        if ($videoType === 'direct' && $request->filled('direct_video_url')) {
            $videoUrl = $request->input('direct_video_url');
        }
        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('videos', 'public');
            $videoUrl = Storage::url($path);
            $videoType = 'direct';
        }

        $wordCount = str_word_count(strip_tags($validated['content']));
        $readingTime = max(1, (int) ceil($wordCount / 200));

        $article = Article::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) ?: 'news-' . time(),
            'category_id' => $validated['category_id'],
            'state_id' => $validated['state_id'] ?? null,
            'city_id' => $validated['city_id'] ?? null,
            'location_name' => $validated['location_name'] ?? null,
            'user_id' => Auth::id(),
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'featured_image' => $imagePath,
            'image_caption' => $validated['image_caption'] ?? null,
            'status' => $validated['status'],
            'is_breaking' => $request->boolean('is_breaking'),
            'is_featured' => $request->boolean('is_featured'),
            'is_trending' => $request->boolean('is_trending'),
            'reading_time' => $readingTime,
            'published_at' => $validated['status'] === 'published' ? now() : null,
            'custom_ad_title' => $validated['custom_ad_title'] ?? null,
            'custom_ad_type' => $validated['custom_ad_type'] ?? 'none',
            'custom_ad_image' => $adImagePath,
            'custom_ad_link' => $validated['custom_ad_link'] ?? null,
            'custom_ad_code' => $validated['custom_ad_code'] ?? null,
            'video_type' => $videoType,
            'video_url' => $videoUrl,
        ]);

        // Process Tags
        if ($request->filled('tags')) {
            $tagNames = array_map('trim', explode(',', $request->input('tags')));
            $tagIds = [];
            foreach ($tagNames as $name) {
                if (!empty($name)) {
                    $tag = Tag::firstOrCreate(['name' => $name]);
                    $tagIds[] = $tag->id;
                }
            }
            $article->tags()->sync($tagIds);
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'ख़बर सफलतापूर्वक प्रकाशित/सहेज दी गई है!');
    }

    public function edit(Article $article)
    {
        // Prevent reporter from editing other authors' articles
        if (auth()->user()->isReporter() && $article->user_id !== auth()->id()) {
            abort(403, 'आपको केवल अपने द्वारा लिखे गए आर्टिकल्स एडिट करने की अनुमति है।');
        }
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $tags = Tag::orderBy('name')->get();
        $states = State::where('is_active', true)->with('cities')->orderBy('order')->get();
        $articleTagNames = $article->tags->pluck('name')->implode(', ');

        return view('admin.articles.edit', compact('article', 'categories', 'tags', 'states', 'articleTagNames'));
    }

    public function update(Request $request, Article $article)
    {
        // Prevent reporter from updating other authors' articles
        if (auth()->user()->isReporter() && $article->user_id !== auth()->id()) {
            abort(403, 'आपको केवल अपने आर्टिकल्स अपडेट करने की अनुमति है।');
        }
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'location_name' => ['nullable', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'featured_image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif', 'max:4096'],
            'featured_image_url' => ['nullable', 'url', 'max:500'],
            'image_caption' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:published,draft,archived'],
            'is_breaking' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_trending' => ['nullable', 'boolean'],
            'tags' => ['nullable', 'string'],
            'custom_ad_title' => ['nullable', 'string', 'max:255'],
            'custom_ad_type' => ['nullable', 'in:none,image,code'],
            'custom_ad_image_file' => ['nullable', 'image', 'max:4096'],
            'custom_ad_image' => ['nullable', 'url', 'max:500'],
            'custom_ad_link' => ['nullable', 'url', 'max:500'],
            'custom_ad_code' => ['nullable', 'string'],
            'video_type' => ['nullable', 'in:none,youtube,direct'],
            'video_url' => ['nullable', 'string', 'max:500'],
            'direct_video_url' => ['nullable', 'string', 'max:500'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,webm,ogg,mov', 'max:51200'],
        ]);

        $imagePath = $article->featured_image;
        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('articles', 'public');
            $imagePath = Storage::url($path);
        } elseif ($request->filled('featured_image_url')) {
            $imagePath = $request->input('featured_image_url');
        }

        $adImagePath = $article->custom_ad_image;
        if ($request->hasFile('custom_ad_image_file')) {
            $path = $request->file('custom_ad_image_file')->store('ads', 'public');
            $adImagePath = Storage::url($path);
        } elseif ($request->filled('custom_ad_image')) {
            $adImagePath = $request->input('custom_ad_image');
        }

        $videoType = $validated['video_type'] ?? $article->video_type;
        $videoUrl = $article->video_url;
        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('videos', 'public');
            $videoUrl = Storage::url($path);
            $videoType = 'direct';
        } elseif ($videoType === 'direct' && $request->filled('direct_video_url')) {
            $videoUrl = $request->input('direct_video_url');
        } elseif ($videoType === 'youtube' && $request->filled('video_url')) {
            $videoUrl = $request->input('video_url');
        } elseif ($videoType === 'none') {
            $videoUrl = null;
        }

        $wordCount = str_word_count(strip_tags($validated['content']));
        $readingTime = max(1, (int) ceil($wordCount / 200));

        $publishedAt = $article->published_at;
        if ($validated['status'] === 'published' && !$publishedAt) {
            $publishedAt = now();
        }

        $article->update([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'state_id' => $validated['state_id'] ?? null,
            'city_id' => $validated['city_id'] ?? null,
            'location_name' => $validated['location_name'] ?? null,
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'featured_image' => $imagePath,
            'image_caption' => $validated['image_caption'] ?? null,
            'status' => $validated['status'],
            'is_breaking' => $request->boolean('is_breaking'),
            'is_featured' => $request->boolean('is_featured'),
            'is_trending' => $request->boolean('is_trending'),
            'reading_time' => $readingTime,
            'published_at' => $publishedAt,
            'custom_ad_title' => $validated['custom_ad_title'] ?? null,
            'custom_ad_type' => $validated['custom_ad_type'] ?? 'none',
            'custom_ad_image' => $adImagePath,
            'custom_ad_link' => $validated['custom_ad_link'] ?? null,
            'custom_ad_code' => $validated['custom_ad_code'] ?? null,
            'video_type' => $videoType,
            'video_url' => $videoUrl,
        ]);

        if ($request->has('tags')) {
            $tagNames = array_map('trim', explode(',', $request->input('tags', '')));
            $tagIds = [];
            foreach ($tagNames as $name) {
                if (!empty($name)) {
                    $tag = Tag::firstOrCreate(['name' => $name]);
                    $tagIds[] = $tag->id;
                }
            }
            $article->tags()->sync($tagIds);
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'ख़बर सफलतापूर्वक अपडेट हो गई है!');
    }

    public function destroy(Article $article)
    {
        // Prevent reporter from deleting other authors' articles
        if (auth()->user()->isReporter() && $article->user_id !== auth()->id()) {
            abort(403, 'आपको केवल अपने आर्टिकल्स हटाने की अनुमति है।');
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'ख़बर सफलतापूर्वक हटा दी गई है।');
    }
}
