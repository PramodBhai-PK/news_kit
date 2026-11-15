<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name_en ?: $this->name,
            'display_name' => $this->display_name,
            'slug' => $this->slug,
            'state_id' => $this->state_id,
            'state_name' => $this->state?->display_name,
            'order' => (int) $this->order,
            'articles_count' => $this->whenCounted('articles', $this->articles_count),
        ];
    }
}
