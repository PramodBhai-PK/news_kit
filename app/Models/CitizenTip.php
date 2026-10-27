<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CitizenTip extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location_name',
        'state_id',
        'city_id',
        'witness_name',
        'witness_phone',
        'witness_email',
        'is_anonymous',
        'media_path',
        'media_type',
        'status',
        'admin_notes',
        'converted_article_id',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
        ];
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function convertedArticle(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'converted_article_id');
    }

    public function getMediaUrlAttribute(): ?string
    {
        if (!$this->media_path) {
            return null;
        }
        if (str_starts_with($this->media_path, 'http://') || str_starts_with($this->media_path, 'https://')) {
            return $this->media_path;
        }
        return asset($this->media_path);
    }

    public function isImage(): bool
    {
        return $this->media_type === 'image';
    }

    public function isVideo(): bool
    {
        return $this->media_type === 'video';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'प्रतीक्षारत (Pending)',
            'verified' => 'सत्यापित / जांच जारी (Verified)',
            'converted_to_article' => 'ख़बर प्रकाशित (Converted)',
            'rejected' => 'अमान्य / अस्वीकृत (Rejected)',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
            'verified' => 'bg-blue-100 text-blue-800 border-blue-200',
            'converted_to_article' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'rejected' => 'bg-rose-100 text-rose-800 border-rose-200',
            default => 'bg-slate-100 text-slate-800 border-slate-200',
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
