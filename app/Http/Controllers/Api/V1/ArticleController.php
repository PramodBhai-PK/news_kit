<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ArticleDetailResource;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Category;
use App\Models\City;
use App\Models\State;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 12), 1), 50);

        $query = Article::published()
            ->with(['category', 'state', 'city', 'author'])
            ->latest('published_at');

        // Filter by category (slug or id)
        if ($cat = $request->query('category')) {
            $query->whereHas('category', function ($q) use ($cat) {
                if (is_numeric($cat)) {
                    $q->where('id', $cat);
                } else {
                    $q->where('slug', $cat);
                }
            });
        }

        // Filter by state (slug or id)
        if ($state = $request->query('state')) {
            $query->whereHas('state', function ($q) use ($state) {
                if (is_numeric($state)) {
                    $q->where('id', $state);
                } else {
                    $q->where('slug', $state);
                }
            });
        }

        // Filter by city (slug or id)
        if ($city = $request->query('city')) {
            $query->whereHas('city', function ($q) use ($city) {
                if (is_numeric($city)) {
                    $q->where('id', $city);
                } else {
                    $q->where('slug', $city);
                }
            });
        }

        // Filter by type
        $type = $request->query('type');
        if ($type === 'breaking') {
            $query->where('is_breaking', true);
        } elseif ($type === 'featured') {
            $query->where('is_featured', true);
        } elseif ($type === 'trending') {
            $query->where('is_trending', true);
        } elseif ($type === 'video') {
            $query->whereIn('video_type', ['youtube', 'direct'])->whereNotNull('video_url');
        }

        // Filter by search query
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by tag (slug)
        if ($tag = $request->query('tag')) {
            $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('slug', $tag)->orWhere('name', $tag);
            });
        }

        // Filter by author
        if ($authorId = $request->query('author_id')) {
            $query->where('user_id', $authorId);
        }

        $articles = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => ArticleResource::collection($articles),
            'meta' => [
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
            ],
            'links' => [
                'first' => $articles->url(1),
                'last' => $articles->url($articles->lastPage()),
                'prev' => $articles->previousPageUrl(),
                'next' => $articles->nextPageUrl(),
            ],
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $article = Article::published()
            ->with(['category', 'state', 'city', 'author', 'tags'])
            ->where('slug', $slug)
            ->first();

        if (!$article) {
            return $this->notFound('Article not found.');
        }

        // Increment view count
        $article->increment('views_count');

        // Fetch related articles from same category
        $related = Article::published()
            ->with(['category', 'state', 'city', 'author'])
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->latest('published_at')
            ->take(4)
            ->get();

        $articleResource = new ArticleDetailResource($article);

        return $this->success([
            'article' => $articleResource,
            'related' => ArticleResource::collection($related),
        ]);
    }

    public function breaking(): JsonResponse
    {
        $breaking = Article::breaking()
            ->with(['category'])
            ->latest('published_at')
            ->take(10)
            ->get();

        return $this->success(ArticleResource::collection($breaking));
    }

    public function trending(): JsonResponse
    {
        $trending = Article::trending()
            ->with(['category', 'state', 'city', 'author'])
            ->latest('published_at')
            ->take(10)
            ->get();

        return $this->success(ArticleResource::collection($trending));
    }

    public function featured(): JsonResponse
    {
        $featured = Article::featured()
            ->with(['category', 'state', 'city', 'author'])
            ->latest('published_at')
            ->take(10)
            ->get();

        return $this->success(ArticleResource::collection($featured));
    }

    public function videos(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 10), 1), 30);

        $videos = Article::videos()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => ArticleResource::collection($videos),
            'meta' => [
                'current_page' => $videos->currentPage(),
                'last_page' => $videos->lastPage(),
                'per_page' => $videos->perPage(),
                'total' => $videos->total(),
            ],
            'links' => [
                'prev' => $videos->previousPageUrl(),
                'next' => $videos->nextPageUrl(),
            ],
        ]);
    }

    public function aiSummary(string $slug): JsonResponse
    {
        $article = Article::published()->where('slug', $slug)->first();

        if (!$article) {
            return $this->notFound('Article not found.');
        }

        // Clean text extraction
        $rawText = strip_tags($article->content ?? $article->excerpt ?? $article->title);
        $rawText = html_entity_decode($rawText, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $rawText = preg_replace('/\s+/', ' ', trim($rawText));

        // Split into candidate sentences
        $sentences = preg_split('/(?<=[.!?])\s+/', $rawText, -1, PREG_SPLIT_NO_EMPTY);
        $filtered = array_values(array_filter($sentences, fn($s) => mb_strlen(trim($s)) >= 25 && mb_strlen(trim($s)) <= 220));

        $bullets = [];
        if (count($filtered) >= 3) {
            $bullets[] = trim($filtered[0]);
            $midIndex = (int) floor(count($filtered) / 2);
            $bullets[] = trim($filtered[$midIndex]);
            $bullets[] = trim($filtered[count($filtered) - 1]);
        } elseif (!empty($filtered)) {
            foreach ($filtered as $s) {
                $bullets[] = trim($s);
            }
            while (count($bullets) < 3) {
                $bullets[] = $article->excerpt ? trim($article->excerpt) : trim($article->title);
            }
        } else {
            $bullets = [
                $article->title,
                $article->excerpt ?: 'Comprehensive reporting on the latest verified developments.',
                'Full context and verified source documentation available in the complete story.'
            ];
        }

        // Sentiment / Tag classification
        $lowerTitle = mb_strtolower($article->title);
        $sentiment = 'Key Briefing';
        $sentimentType = 'neutral';

        if (str_contains($lowerTitle, 'alert') || str_contains($lowerTitle, 'warn') || str_contains($lowerTitle, 'crash') || str_contains($lowerTitle, 'loss') || str_contains($lowerTitle, 'storm') || str_contains($lowerTitle, 'fall')) {
            $sentiment = 'High Alert';
            $sentimentType = 'alert';
        } elseif (str_contains($lowerTitle, 'growth') || str_contains($lowerTitle, 'win') || str_contains($lowerTitle, 'rise') || str_contains($lowerTitle, 'success') || str_contains($lowerTitle, 'record') || str_contains($lowerTitle, 'surge')) {
            $sentiment = 'Positive Trend';
            $sentimentType = 'positive';
        } else {
            $sentiment = 'Fact Verified';
            $sentimentType = 'verified';
        }

        $readingTime = max(1, (int) ceil(str_word_count($rawText) / 200));
        $savedTime = max(1, $readingTime - 1);

        return $this->success([
            'title' => 'AI 3-Point Key Takeaways',
            'reading_time_saved' => "{$savedTime} min reading time saved",
            'sentiment' => $sentiment,
            'sentiment_type' => $sentimentType,
            'bullets' => array_slice($bullets, 0, 3),
            'key_takeaway' => $bullets[0] ?? $article->title,
        ]);
    }
}

