<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\CommentResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends ApiController
{
    public function index(string $slug, Request $request): JsonResponse
    {
        $article = Article::published()->where('slug', $slug)->first();

        if (!$article) {
            return $this->notFound('Article not found.');
        }

        $perPage = min(max((int) $request->query('per_page', 20), 1), 50);

        $comments = $article->approvedComments()->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => CommentResource::collection($comments),
            'meta' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'per_page' => $comments->perPage(),
                'total' => $comments->total(),
            ],
            'links' => [
                'prev' => $comments->previousPageUrl(),
                'next' => $comments->nextPageUrl(),
            ],
        ]);
    }

    public function store(string $slug, Request $request): JsonResponse
    {
        $article = Article::published()->where('slug', $slug)->first();

        if (!$article) {
            return $this->notFound('Article not found.');
        }

        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:100',
            'user_email' => 'required|email|max:150',
            'comment' => 'required|string|min:3|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $validated = $validator->validated();

        $comment = $article->comments()->create([
            'user_name' => $validated['user_name'],
            'user_email' => $validated['user_email'],
            'comment' => $validated['comment'],
            'is_approved' => true,
        ]);

        return $this->success(new CommentResource($comment), 'Your comment has been successfully published!', 201);
    }
}

