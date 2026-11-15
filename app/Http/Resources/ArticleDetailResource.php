<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $base = (new ArticleResource($this->resource))->toArray($request);

        return array_merge($base, [
            'content' => $this->content,
            'clean_text' => trim(preg_replace('/\s+/', ' ', strip_tags($this->content))),
            'youtube_embed_url' => $this->youtube_embed_url,
            'tags' => $this->tags->map(fn ($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ]),
            'custom_ad' => $this->hasCustomAd() ? [
                'type' => $this->custom_ad_type,
                'title' => $this->custom_ad_title,
                'image' => $this->custom_ad_image ? (str_starts_with($this->custom_ad_image, 'http') ? $this->custom_ad_image : asset($this->custom_ad_image)) : null,
                'link' => route('portal.article.ad.click', $this->slug),
                'html_code' => $this->custom_ad_code,
            ] : null,
            'comments_count' => $this->approved_comments_count ?? $this->approvedComments()->count(),
        ]);
    }
}

