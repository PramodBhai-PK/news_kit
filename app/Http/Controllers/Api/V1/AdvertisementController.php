<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\AdvertisementResource;
use App\Models\Advertisement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdvertisementController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Advertisement::where('is_active', true)->orderBy('order');

        if ($placement = $request->query('placement')) {
            $query->where('placement', $placement);
        }

        $ads = $query->get();

        return $this->success(AdvertisementResource::collection($ads));
    }

    public function click(int $id): JsonResponse
    {
        $ad = Advertisement::where('id', $id)->first();

        if (!$ad) {
            return $this->notFound('Advertisement not found.');
        }

        $ad->increment('clicks_count');

        return $this->success([
            'id' => $ad->id,
            'destination_url' => $ad->destination_url,
            'clicks_count' => $ad->clicks_count,
        ]);
    }
}
