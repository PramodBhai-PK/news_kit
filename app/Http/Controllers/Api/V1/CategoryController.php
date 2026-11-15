<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ArticleResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function index(): JsonResponse
    {
        $categories = Category::where('is_active', true)
            ->withCount(['articles' => function ($q) {
                $q->published();
            }])
            ->orderBy('order')
            ->get();

        return $this->success(CategoryResource::collection($categories));
    }

    public function show(string $slug, Request $request): JsonResponse
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->withCount(['articles' => function ($q) {
                $q->published();
            }])
            ->first();

        if (!$category) {
            return $this->notFound('Category not found.');
        }

        $perPage = min(max((int) $request->query('per_page', 12), 1), 50);

        $articles = $category->articles()
            ->published()
            ->with(['author', 'state', 'city'])
            ->latest('published_at')
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'category' => new CategoryResource($category),
            'articles' => ArticleResource::collection($articles),
            'meta' => [
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
            ],
            'links' => [
                'prev' => $articles->previousPageUrl(),
                'next' => $articles->nextPageUrl(),
            ],
        ]);
    }
}
