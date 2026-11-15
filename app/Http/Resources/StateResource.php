<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name_en ?: $this->name,
            'display_name' => $this->display_name,
            'slug' => $this->slug,
            'code' => $this->code,
            'order' => (int) $this->order,
            'cities_count' => $this->whenCounted('cities', $this->cities_count),
            'cities' => CityResource::collection($this->whenLoaded('cities')),
        ];
    }
}
