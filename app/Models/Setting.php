<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
    ];

    /**
     * Get a setting by key, fallback to config/branding.php or default
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever('app_setting_' . $key, function () use ($key, $default) {
            $record = static::where('key', $key)->first();
            if ($record && !is_null($record->value)) {
                return $record->value;
            }

            // Fallback to config/branding.php
            $brandingVal = config('branding.' . $key);
            if (!is_null($brandingVal)) {
                return $brandingVal;
            }

            return $default;
        });
    }

    /**
     * Set / Update a setting
     */
    public static function set(string $key, mixed $value, string $group = 'branding', string $type = 'text', ?string $label = null): static
    {
        Cache::forget('app_setting_' . $key);

        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type' => $type,
                'label' => $label ?: ucwords(str_replace('_', ' ', $key)),
            ]
        );
    }
}
