<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'image_url',
        'destination_url',
        'html_code',
        'placement',
        'order',
        'is_active',
        'views_count',
        'clicks_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'views_count' => 'integer',
        'clicks_count' => 'integer',
    ];

    public function getCtrAttribute(): float
    {
        if ($this->views_count <= 0) {
            return 0.0;
        }
        return round(($this->clicks_count / $this->views_count) * 100, 1);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSidebar($query)
    {
        return $query->active()->where('placement', 'sidebar')->orderBy('order');
    }

    public function scopeHeader($query)
    {
        return $query->active()->where('placement', 'header')->orderBy('order');
    }

    public function scopeInFeed($query)
    {
        return $query->active()->where('placement', 'in_feed')->orderBy('order');
    }
}
