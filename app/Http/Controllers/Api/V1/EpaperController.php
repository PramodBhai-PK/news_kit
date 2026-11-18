<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\EpaperEditionResource;
use App\Models\EpaperEdition;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EpaperController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = EpaperEdition::published()
            ->with(['city', 'state'])
            ->latest('edition_date');

        if ($date = $request->query('date')) {
            $query->whereDate('edition_date', $date);
        }

        if ($city = $request->query('city')) {
            $query->whereHas('city', function ($q) use ($city) {
                if (is_numeric($city)) {
                    $q->where('id', $city);
                } else {
                    $q->where('slug', $city);
                }
            });
        }

        $perPage = min(max((int) $request->query('per_page', 10), 1), 30);
        $editions = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => EpaperEditionResource::collection($editions),
            'meta' => [
                'current_page' => $editions->currentPage(),
                'last_page' => $editions->lastPage(),
                'per_page' => $editions->perPage(),
                'total' => $editions->total(),
            ],
        ]);
    }

    public function today(Request $request): JsonResponse
    {
        $today = Carbon::today();

        $query = EpaperEdition::published()
            ->with(['pages', 'city', 'state']);

        if ($city = $request->query('city')) {
            $query->whereHas('city', function ($q) use ($city) {
                if (is_numeric($city)) {
                    $q->where('id', $city);
                } else {
                    $q->where('slug', $city);
                }
            });
        }

        // Try to get today's edition, otherwise fallback to latest available edition
        $edition = (clone $query)->whereDate('edition_date', $today)->first()
            ?: (clone $query)->latest('edition_date')->first();

        if (!$edition) {
            return $this->notFound('No digital edition available.');
        }

        return $this->success(new EpaperEditionResource($edition));
    }

    public function show(string $idOrSlug): JsonResponse
    {
        $edition = EpaperEdition::published()
            ->with(['pages', 'city', 'state'])
            ->where(function ($q) use ($idOrSlug) {
                if (is_numeric($idOrSlug)) {
                    $q->where('id', (int) $idOrSlug);
                } else {
                    $q->where('slug', $idOrSlug)->orWhereDate('edition_date', $idOrSlug);
                }
            })
            ->first();

        if (!$edition) {
            return $this->notFound('E-paper edition not found.');
        }

        return $this->success(new EpaperEditionResource($edition));
    }

    public function view(int $id): JsonResponse
    {
        $edition = EpaperEdition::find($id);
        if (!$edition) {
            return $this->notFound('Edition not found.');
        }

        $edition->increment('views_count');

        return $this->success([
            'id' => $edition->id,
            'views_count' => $edition->views_count,
        ]);
    }

    public function download(int $id): JsonResponse
    {
        $edition = EpaperEdition::find($id);
        if (!$edition) {
            return $this->notFound('Edition not found.');
        }

        $edition->increment('downloads_count');

        return $this->success([
            'id' => $edition->id,
            'downloads_count' => $edition->downloads_count,
            'pdf_file' => $edition->pdf_file,
        ]);
    }
}
