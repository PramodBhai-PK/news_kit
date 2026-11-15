<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\CitizenTip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CitizenTipController extends ApiController
{
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'location_name' => 'nullable|string|max:255',
            'state_id' => 'nullable|exists:states,id',
            'city_id' => 'nullable|exists:cities,id',
            'witness_name' => 'nullable|string|max:100',
            'witness_phone' => 'nullable|string|max:30',
            'witness_email' => 'nullable|email|max:100',
            'is_anonymous' => 'nullable|boolean',
            'media' => 'nullable|file|mimes:jpeg,jpg,png,webp,mp4,mov,avi|max:51200', // Max 50MB
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $data = $validator->validated();
        $isAnonymous = filter_var($request->input('is_anonymous', false), FILTER_VALIDATE_BOOLEAN);

        $mediaPath = null;
        $mediaType = null;

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $path = $file->store('citizen_tips', 'public');
            $mediaPath = Storage::url($path);

            $mime = $file->getMimeType();
            $mediaType = str_starts_with($mime, 'video/') ? 'video' : 'image';
        }

        $tip = CitizenTip::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'location_name' => $data['location_name'] ?? null,
            'state_id' => $data['state_id'] ?? null,
            'city_id' => $data['city_id'] ?? null,
            'witness_name' => $isAnonymous ? 'Anonymous Citizen' : ($data['witness_name'] ?? null),
            'witness_phone' => $isAnonymous ? null : ($data['witness_phone'] ?? null),
            'witness_email' => $isAnonymous ? null : ($data['witness_email'] ?? null),
            'is_anonymous' => $isAnonymous,
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
            'status' => 'pending',
            'ip_address' => $request->ip(),
        ]);

        return $this->success([
            'id' => $tip->id,
            'status' => $tip->status,
            'created_at' => $tip->created_at->toIso8601String(),
        ], 'Citizen tip submitted successfully. Our editorial desk will verify it.', 201);
    }
}

