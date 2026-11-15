<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'featured_image' => $this->featured_image_url,
            'image_caption' => $this->image_caption,
            'has_video' => $this->hasVideo(),
            'video_type' => $this->video_type,
            'video_url' => $this->video_url,
            'youtube_id' => $this->youtube_id,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->display_name,
                'slug' => $this->category->slug,
                'color' => $this->category->color ?? '#E11D48',
            ] : null,
            'location' => [
                'badge' => $this->location_badge,
                'state' => $this->state?->display_name,
                'city' => $this->city?->display_name,
            ],
            'author' => $this->author ? [
                'id' => $this->author->id,
                'name' => $this->author->name,
                'designation' => $this->author->designation ?? $this->author->role,
                'avatar' => $this->author->avatar_url ?? null,
            ] : null,
            'is_breaking' => (bool) $this->is_breaking,
            'is_featured' => (bool) $this->is_featured,
            'is_trending' => (bool) $this->is_trending,
            'views_count' => (int) $this->views_count,
            'reading_time' => (int) $this->reading_time,
            'published_at' => $this->published_at?->toIso8601String(),
            'published_at_human' => $this->published_at?->diffForHumans(),
            'share_url' => route('portal.article', $this->slug),
        ];
    }
}
