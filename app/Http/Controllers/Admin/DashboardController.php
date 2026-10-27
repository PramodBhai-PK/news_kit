<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Scoped metrics if user is a Reporter
        if ($user->isReporter()) {
            $myArticles = $user->articles();
            $myArticleIds = $user->articles()->pluck('id');

            $stats = [
                'total_articles' => $myArticles->count(),
                'published_articles' => $user->articles()->where('status', 'published')->count(),
                'draft_articles' => $user->articles()->where('status', 'draft')->count(),
                'total_views' => (int) $user->articles()->sum('views_count'),
                'total_comments' => Comment::whereIn('article_id', $myArticleIds)->count(),
                'pending_comments' => Comment::whereIn('article_id', $myArticleIds)->where('is_approved', false)->count(),
            ];

            $recentArticles = $user->articles()
                ->with(['category'])
                ->latest()
                ->take(6)
                ->get();

            $topArticles = $user->articles()
                ->with('category')
                ->orderByDesc('views_count')
                ->take(5)
                ->get();

            $teamStats = null;
        } else {
            // 2. Global metrics for Admin, Editor, Publisher
            $stats = [
                'total_articles' => Article::count(),
                'published_articles' => Article::where('status', 'published')->count(),
                'total_views' => (int) Article::sum('views_count'),
                'total_categories' => Category::count(),
                'total_comments' => Comment::count(),
                'breaking_news_count' => Article::where('is_breaking', true)->count(),
            ];

            $recentArticles = Article::with(['category', 'author'])
                ->latest()
                ->take(6)
                ->get();

            $topArticles = Article::with('category')
                ->orderByDesc('views_count')
                ->take(5)
                ->get();

            // Team statistics breakdown
            $teamStats = [
                'total_team' => User::count(),
                'editors' => User::where('role', 'editor')->count(),
                'reporters' => User::where('role', 'reporter')->count(),
                'publishers' => User::where('role', 'publisher')->count(),
                'admins' => User::where('role', 'admin')->count(),
            ];
        }

        return view('admin.dashboard', compact('stats', 'recentArticles', 'topArticles', 'teamStats'));
    }
}
