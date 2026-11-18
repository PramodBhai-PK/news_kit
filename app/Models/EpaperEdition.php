<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class EpaperEdition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'edition_date',
        'state_id',
        'city_id',
        'cover_image',
        'pdf_file',
        'total_pages',
        'views_count',
        'downloads_count',
        'is_published',
    ];

    protected $casts = [
        'edition_date' => 'date',
        'total_pages' => 'integer',
        'views_count' => 'integer',
        'downloads_count' => 'integer',
        'is_published' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $baseSlug = Str::slug($model->title ?: 'epaper-' . $model->edition_date->format('Y-m-d'));
                $slug = $baseSlug;
                $count = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-{$count}";
                    $count++;
                }
                $model->slug = $slug;
            }
        });
    }

    public function pages(): HasMany
    {
        return $this->hasMany(EpaperPage::class)->orderBy('page_number');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('edition_date', $date);
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->edition_date ? $this->edition_date->format('d M, Y') : '';
    }
}
