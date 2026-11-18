<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $imageUrl = $this->image_url;
        if ($imageUrl && !str_starts_with($imageUrl, 'http://') && !str_starts_with($imageUrl, 'https://')) {
            $imageUrl = url($imageUrl);
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'image_url' => $imageUrl,
            'destination_url' => $this->destination_url,
            'click_url' => route('portal.ad.click', $this->id),
            'html_code' => $this->html_code,
            'placement' => $this->placement,
            'order' => $this->order,
            'views_count' => $this->views_count,
            'clicks_count' => $this->clicks_count,
        ];
    }
}

