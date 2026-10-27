<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'name',
        'name_en',
        'slug',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
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
        static::creating(function ($city) {
            if (empty($city->slug)) {
                $city->slug = Str::slug($city->name_en ?: $city->name);
            }
        });
    }
}
