<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'slug' => $this->slug,
            'color' => $this->color ?? '#E11D48',
            'icon' => $this->icon,
            'order' => (int) $this->order,
            'articles_count' => $this->whenCounted('articles', $this->articles_count),
        ];
    }
}

