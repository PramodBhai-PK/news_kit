<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EpaperPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'epaper_edition_id',
        'page_number',
        'page_title',
        'page_image',
        'pdf_url',
    ];

    protected $casts = [
        'page_number' => 'integer',
    ];

    public function getPageImageAttribute(?string $value): ?string
    {
        if (empty($value)) return $value;
        if (str_contains($value, '/demo/epaper/') || str_contains($value, '/storage/')) {
            $parsed = parse_url($value);
            return url($parsed['path'] ?? $value);
        }
        return $value;
    }

    public function getPdfUrlAttribute(?string $value): ?string
    {
        if (empty($value)) return $value;
        if (str_contains($value, '/demo/epaper/') || str_contains($value, '/storage/')) {
            $parsed = parse_url($value);
            return url($parsed['path'] ?? $value);
        }
        return $value;
    }

    public function edition(): BelongsTo
    {
        return $this->belongsTo(EpaperEdition::class, 'epaper_edition_id');
    }
}
