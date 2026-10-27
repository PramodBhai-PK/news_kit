<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'image_caption',
        'video_type', // 'none', 'youtube', 'direct'
        'video_url',
        'category_id',
        'state_id',
        'city_id',
        'location_name',
        'user_id',
        'status',
        'is_breaking',
        'is_featured',
        'is_trending',
        'views_count',
        'reading_time',
        'published_at',
        'custom_ad_title',
        'custom_ad_type',
        'custom_ad_image',
        'custom_ad_link',
        'custom_ad_code',
        'custom_ad_views_count',
        'custom_ad_clicks_count',
    ];

    public function hasCustomAd(): bool
    {
        return in_array($this->custom_ad_type, ['image', 'code']) && 
            (!empty($this->custom_ad_image) || !empty($this->custom_ad_code));
    }

    public function getCustomAdCtrAttribute(): float
    {
        if ($this->custom_ad_views_count <= 0) {
            return 0.0;
        }
        return round(($this->custom_ad_clicks_count / $this->custom_ad_views_count) * 100, 1);
    }

    public function hasVideo(): bool
    {
        return in_array($this->video_type, ['youtube', 'direct']) && !empty($this->video_url);
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (empty($this->featured_image)) {
            return null;
        }

        if (Str::startsWith($this->featured_image, ['http://', 'https://'])) {
            return $this->featured_image;
        }

        return asset($this->featured_image);
    }

    public function isYoutubeVideo(): bool
    {
        return $this->video_type === 'youtube';
    }

    public function isDirectVideo(): bool
    {
        return $this->video_type === 'direct';
    }

    public function getYoutubeIdAttribute(): ?string
    {
        if (!$this->isYoutubeVideo() || empty($this->video_url)) {
            return null;
        }

        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/|youtube\.com/shorts/)([^"&?/ ]{11})%i', $this->video_url, $match);
        return $match[1] ?? null;
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        $id = $this->youtube_id;
        return $id ? "https://www.youtube.com/embed/{$id}?autoplay=1&rel=0" : $this->video_url;
    }

    public function scopeVideos($query)
    {
        return $query->published()
            ->whereIn('video_type', ['youtube', 'direct'])
            ->whereNotNull('video_url');
    }

    protected $casts = [
        'is_breaking' => 'boolean',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'views_count' => 'integer',
        'reading_time' => 'integer',
        'custom_ad_views_count' => 'integer',
        'custom_ad_clicks_count' => 'integer',
        'published_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $base = Str::slug($article->title);
                $article->slug = $base ?: 'news-' . time();
            }
            if (empty($article->reading_time)) {
                $wordCount = str_word_count(strip_tags($article->content));
                $article->reading_time = max(1, (int) ceil($wordCount / 200));
            }
            if (empty($article->published_at) && $article->status === 'published') {
                $article->published_at = now();
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function hasLocation(): bool
    {
        return !empty($this->state_id) || !empty($this->city_id) || !empty($this->location_name);
    }

    public function getLocationBadgeAttribute(): ?string
    {
        if (!empty($this->location_name)) {
            return $this->location_name;
        }
        if ($this->city) {
            return $this->city->display_name;
        }
        if ($this->state) {
            return $this->state->display_name;
        }
        return null;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('is_approved', true)->latest();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeBreaking($query)
    {
        return $query->published()->where('is_breaking', true);
    }

    public function scopeFeatured($query)
    {
        return $query->published()->where('is_featured', true);
    }

    public function scopeTrending($query)
    {
        return $query->published()->where('is_trending', true);
    }
}
