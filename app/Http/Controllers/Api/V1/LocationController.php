<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ArticleResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\StateResource;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends ApiController
{
    public function states(): JsonResponse
    {
        $states = State::where('is_active', true)
            ->withCount(['cities', 'articles' => function ($q) {
                $q->published();
            }])
            ->orderBy('order')
            ->get();

        return $this->success(StateResource::collection($states));
    }

    public function state(string $slug): JsonResponse
    {
        $state = State::where('slug', $slug)
            ->where('is_active', true)
            ->with(['cities' => function ($q) {
                $q->where('is_active', true)->withCount(['articles' => function ($sq) {
                    $sq->published();
                }]);
            }])
            ->withCount(['articles' => function ($q) {
                $q->published();
            }])
            ->first();

        if (!$state) {
            return $this->notFound('State not found.');
        }

        return $this->success(new StateResource($state));
    }

    public function cities(Request $request): JsonResponse
    {
        $query = City::where('is_active', true)
            ->with('state')
            ->withCount(['articles' => function ($q) {
                $q->published();
            }])
            ->orderBy('order');

        if ($stateId = $request->query('state_id')) {
            $query->where('state_id', $stateId);
        } elseif ($stateSlug = $request->query('state_slug')) {
            $query->whereHas('state', fn ($q) => $q->where('slug', $stateSlug));
        }

        $cities = $query->get();

        return $this->success(CityResource::collection($cities));
    }

    public function city(string $slug, Request $request): JsonResponse
    {
        $city = City::where('slug', $slug)
            ->where('is_active', true)
            ->with('state')
            ->withCount(['articles' => function ($q) {
                $q->published();
            }])
            ->first();

        if (!$city) {
            return $this->notFound('City not found.');
        }

        $perPage = min(max((int) $request->query('per_page', 12), 1), 50);

        $articles = $city->articles()
            ->published()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'city' => new CityResource($city),
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
