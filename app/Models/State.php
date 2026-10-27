<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'code',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class)->where('is_active', true)->orderBy('order');
    }

    public function allCities(): HasMany
    {
        return $this->hasMany(City::class)->orderBy('order');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function getDisplayNameAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        return $this->name;
    }

    protected static function booted()
    {
        static::creating(function ($state) {
            if (empty($state->slug)) {
                $state->slug = Str::slug($state->name_en ?: $state->name);
            }
        });
    }
}
