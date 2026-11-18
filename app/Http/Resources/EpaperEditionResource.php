<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EpaperEditionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $coverImage = $this->cover_image;
        if ($coverImage && !str_starts_with($coverImage, 'http://') && !str_starts_with($coverImage, 'https://')) {
            $coverImage = url($coverImage);
        }

        $pdfFile = $this->pdf_file;
        if ($pdfFile && !str_starts_with($pdfFile, 'http://') && !str_starts_with($pdfFile, 'https://')) {
            $pdfFile = url($pdfFile);
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'edition_date' => $this->edition_date?->format('Y-m-d'),
            'formatted_date' => $this->formatted_date,
            'cover_image' => $coverImage,
            'pdf_file' => $pdfFile,
            'total_pages' => $this->total_pages,
            'views_count' => $this->views_count,
            'downloads_count' => $this->downloads_count,
            'city' => $this->city ? [
                'id' => $this->city->id,
                'name' => $this->city->name,
                'slug' => $this->city->slug,
            ] : null,
            'pages' => EpaperPageResource::collection($this->whenLoaded('pages')),
        ];
    }
}
