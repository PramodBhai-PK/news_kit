<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Category;
use App\Models\City;
use App\Models\Comment;
use App\Models\State;
use App\Models\Tag;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index(Request $request)
    {
        // 1. Breaking News items for Ticker
        $breakingNews = Article::published()
            ->where('is_breaking', true)
            ->latest('published_at')
            ->take(8)
            ->get();

        // 2. Featured Lead Story for Hero
        $mainHero = Article::published()
            ->with(['category', 'author'])
            ->where('is_featured', true)
            ->latest('published_at')
            ->first();

        if (!$mainHero) {
            $mainHero = Article::published()
                ->with(['category', 'author'])
                ->latest('published_at')
                ->first();
        }

        // Sub-Heroes for the right side (Fetch 4 stories excluding the main hero)
        $subHeroes = Article::published()
            ->with(['category', 'author'])
            ->when($mainHero, fn($q) => $q->where('id', '!=', $mainHero->id))
            ->latest('published_at')
            ->take(4)
            ->get();

        // 3. Trending Articles (Top 6)
        $trendingArticles = Article::published()
            ->with(['category'])
            ->orderByDesc('views_count')
            ->take(6)
            ->get();

        // 4. Latest News Feed (Paginated)
        $latestArticles = Article::published()
            ->with(['category', 'author', 'tags'])
            ->latest('published_at')
            ->paginate(9);

        // 4.5. Featured Video Coverage (YouTube & Direct Video Stories)
        $videoArticles = Article::published()
            ->videos()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->take(5)
            ->get();

        // 5. Active Categories for Navigation & Filter
        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->withCount(['publishedArticles as articles_count'])
            ->get();

        $popularTags = Tag::withCount('articles')
            ->orderByDesc('articles_count')
            ->take(10)
            ->get();

        // 6. Homepage Advertisements (Top Header Leaderboard, In-feed Strip, and Sidebar)
        $headerAd = Advertisement::header()->first();
        $inFeedAd = Advertisement::inFeed()->first();
        $homepageSidebarAds = Advertisement::sidebar()->take(2)->get();

        // Increment impressions for displayed homepage ads
        $adIdsToIncrement = collect([$headerAd?->id, $inFeedAd?->id])
            ->filter()
            ->concat($homepageSidebarAds->pluck('id'))
            ->unique()
            ->toArray();

        if (!empty($adIdsToIncrement)) {
            Advertisement::whereIn('id', $adIdsToIncrement)->increment('views_count');
        }

        // 7. Hyperlocal Regional Stories (State & District News Hub)
        $hyperlocalArticles = Article::published()
            ->whereNotNull('state_id')
            ->with(['category', 'author', 'state', 'city'])
            ->latest('published_at')
            ->take(12)
            ->get();

        // 8. User Selected Preferred City Stories
        $selectedCitySlug = session('user_city_slug');
        $selectedCity = $selectedCitySlug ? City::where('slug', $selectedCitySlug)->with('state')->first() : null;
        $myCityArticles = collect();
        if ($selectedCity) {
            $myCityArticles = Article::published()
                ->where('city_id', $selectedCity->id)
                ->with(['category', 'author', 'state', 'city'])
                ->latest('published_at')
                ->take(4)
                ->get();
        }

        return view('portal.index', compact(
            'breakingNews',
            'mainHero',
            'subHeroes',
            'trendingArticles',
            'videoArticles',
            'latestArticles',
            'categories',
            'popularTags',
            'headerAd',
            'inFeedAd',
            'homepageSidebarAds',
            'hyperlocalArticles',
            'selectedCity',
            'myCityArticles'
        ));
    }

    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $articles = Article::published()
            ->where('category_id', $category->id)
            ->with(['author', 'tags'])
            ->latest('published_at')
            ->paginate(12);

        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $trendingArticles = Article::published()->orderByDesc('views_count')->take(5)->get();

        return view('portal.category', compact('category', 'articles', 'categories', 'trendingArticles'));
    }

    public function tag(string $slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $articles = $tag->articles()
            ->published()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate(12);

        $categories = Category::where('is_active', true)->orderBy('order')->get();

        return view('portal.tag', compact('tag', 'articles', 'categories'));
    }

    public function article(string $slug)
    {
        $article = Article::published()
            ->where('slug', $slug)
            ->with(['category', 'author', 'tags', 'approvedComments'])
            ->firstOrFail();

        // Increment view count safely
        $article->increment('views_count');

        // Related stories in the same category + fallback to recommended stories
        $relatedArticles = Article::published()
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->with(['category', 'author'])
            ->take(4)
            ->get();

        if ($relatedArticles->count() < 4) {
            $excludeIds = $relatedArticles->pluck('id')->push($article->id)->toArray();
            $moreArticles = Article::published()
                ->whereNotIn('id', $excludeIds)
                ->with(['category', 'author'])
                ->latest('published_at')
                ->take(4 - $relatedArticles->count())
                ->get();
            $relatedArticles = $relatedArticles->concat($moreArticles);
        }

        $trendingArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        $categories = Category::where('is_active', true)->orderBy('order')->get();

        // Fetch active sidebar advertisements (Custom Images & Google AdSense HTML)
        $sidebarAds = Advertisement::sidebar()->get();

        // Increment ad impressions
        if ($sidebarAds->isNotEmpty()) {
            Advertisement::whereIn('id', $sidebarAds->pluck('id'))->increment('views_count');
        }

        // Increment reporter's custom ad impressions if active on this article
        if ($article->hasCustomAd()) {
            $article->increment('custom_ad_views_count');
        }

        return view('portal.article', compact(
            'article',
            'relatedArticles',
            'trendingArticles',
            'categories',
            'sidebarAds'
        ));
    }

    public function commentStore(Request $request, string $slug)
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'user_name' => ['required', 'string', 'max:100'],
            'user_email' => ['required', 'email', 'max:150'],
            'comment' => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        $article->comments()->create([
            'user_name' => $validated['user_name'],
            'user_email' => $validated['user_email'],
            'comment' => $validated['comment'],
            'is_approved' => true,
        ]);

        return back()->with('success', 'आपकी टिप्पणी सफलतापूर्वक प्रकाशित हो गई है!');
    }

    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));

        $articles = Article::published()
            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('title', 'like', "%{$query}%")
                        ->orWhere('excerpt', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%");
                });
            })
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate(12)
            ->appends(['q' => $query]);

        $categories = Category::where('is_active', true)->orderBy('order')->get();

        return view('portal.search', compact('articles', 'query', 'categories'));
    }

    public function adClick(Advertisement $advertisement)
    {
        $advertisement->increment('clicks_count');

        $destination = $advertisement->destination_url ?: route('portal.index');
        return redirect()->away($destination);
    }

    public function articleAdClick(Article $article)
    {
        $article->increment('custom_ad_clicks_count');

        $destination = $article->custom_ad_link ?: route('portal.article', $article->slug);
        return redirect()->away($destination);
    }

    public function setLocale(string $locale)
    {
        if (in_array($locale, ['hi', 'en', 'ur', 'ar'], true)) {
            session(['locale' => $locale]);
        }

        return back();
    }

    public function state(string $slug)
    {
        $state = State::where('slug', $slug)
            ->where('is_active', true)
            ->with(['cities' => fn($q) => $q->where('is_active', true)->orderBy('name')])
            ->firstOrFail();

        $articles = Article::published()
            ->where('state_id', $state->id)
            ->with(['category', 'author', 'city'])
            ->latest('published_at')
            ->paginate(12);

        $categories = Category::where('is_active', true)->orderBy('order')->get();

        $trendingArticles = Article::published()
            ->where('state_id', $state->id)
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        if ($trendingArticles->count() < 3) {
            $trendingArticles = Article::published()
                ->orderByDesc('views_count')
                ->take(5)
                ->get();
        }

        $sidebarAds = Advertisement::sidebar()->get();

        return view('portal.state', compact('state', 'articles', 'categories', 'trendingArticles', 'sidebarAds'));
    }

    public function city(string $slug)
    {
        $city = City::where('slug', $slug)
            ->where('is_active', true)
            ->with('state')
            ->firstOrFail();

        $siblingCities = City::where('state_id', $city->state_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $articles = Article::published()
            ->where('city_id', $city->id)
            ->with(['category', 'author', 'state'])
            ->latest('published_at')
            ->paginate(12);

        $categories = Category::where('is_active', true)->orderBy('order')->get();

        $trendingArticles = Article::published()
            ->where(function ($q) use ($city) {
                $q->where('city_id', $city->id)
                  ->orWhere('state_id', $city->state_id);
            })
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        if ($trendingArticles->count() < 3) {
            $trendingArticles = Article::published()
                ->orderByDesc('views_count')
                ->take(5)
                ->get();
        }

        $sidebarAds = Advertisement::sidebar()->get();

        return view('portal.city', compact('city', 'articles', 'siblingCities', 'categories', 'trendingArticles', 'sidebarAds'));
    }

    public function setCity(Request $request, string $slug)
    {
        if ($slug === 'all' || $slug === 'clear' || $slug === 'reset') {
            session()->forget(['user_city_slug', 'user_city_name']);
            return back()->with('city_success', 'सभी क्षेत्रों की खबरें सक्रिय हैं।');
        }

        $city = City::where('slug', $slug)->where('is_active', true)->with('state')->firstOrFail();

        session([
            'user_city_slug' => $city->slug,
            'user_city_name' => $city->display_name,
        ]);

        return back()->with('city_success', "आपका चुना हुआ शहर '{$city->display_name}' सेट कर दिया गया है!");
    }

    public function sitemap()
    {
        $articles = Article::published()
            ->latest('published_at')
            ->take(1000)
            ->get();

        $categories = Category::where('is_active', true)->get();
        $tags = Tag::all();
        $states = State::where('is_active', true)->get();
        $cities = City::where('is_active', true)->get();

        return response()
            ->view('portal.sitemap', compact('articles', 'categories', 'tags', 'states', 'cities'))
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }

    public function newsSitemap()
    {
        $articles = Article::published()
            ->where('published_at', '>=', now()->subHours(48))
            ->latest('published_at')
            ->take(100)
            ->get();

        if ($articles->count() < 5) {
            $articles = Article::published()
                ->latest('published_at')
                ->take(25)
                ->get();
        }

        return response()
            ->view('portal.news-sitemap', compact('articles'))
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }

    public function feed()
    {
        $articles = Article::published()
            ->with(['author', 'category'])
            ->latest('published_at')
            ->take(30)
            ->get();

        return response()
            ->view('portal.feed', compact('articles'))
            ->header('Content-Type', 'application/rss+xml; charset=utf-8');
    }
}

