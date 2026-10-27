<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'hindi_name',
        'description',
        'color',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function publishedArticles(): HasMany
    {
        return $this->hasMany(Article::class)->where('status', 'published');
    }

    public function getDisplayNameAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'hi') {
            return $this->hindi_name ?: $this->name;
        }

        // Check translation dictionary for active locale (en, ur, ar, etc.)
        $translated = __($this->name);
        if ($translated !== $this->name) {
            return $translated;
        }
        if ($this->hindi_name) {
            $translatedHindi = __($this->hindi_name);
            if ($translatedHindi !== $this->hindi_name) {
                return $translatedHindi;
            }
        }

        if ($locale === 'en') {
            return $this->name ?: $this->hindi_name;
        }

        return $this->hindi_name ?: $this->name;
    }
}
