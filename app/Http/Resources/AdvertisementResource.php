<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'image_url' => $this->image_url,
            'destination_url' => $this->destination_url,
            'click_url' => route('portal.ad.click', $this->id),
            'html_code' => $this->html_code,
            'placement' => $this->placement,
        ];
    }
}
