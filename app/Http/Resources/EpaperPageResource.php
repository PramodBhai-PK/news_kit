<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EpaperPageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $imageUrl = $this->page_image;
        if ($imageUrl && !str_starts_with($imageUrl, 'http://') && !str_starts_with($imageUrl, 'https://')) {
            $imageUrl = url($imageUrl);
        }

        $pdfUrl = $this->pdf_url;
        if ($pdfUrl && !str_starts_with($pdfUrl, 'http://') && !str_starts_with($pdfUrl, 'https://')) {
            $pdfUrl = url($pdfUrl);
        }

        return [
            'id' => $this->id,
            'page_number' => $this->page_number,
            'page_title' => $this->page_title ?: "Page {$this->page_number}",
            'page_image' => $imageUrl,
            'pdf_url' => $pdfUrl,
        ];
    }
}
