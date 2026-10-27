<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role', // 'admin', 'editor', 'reporter', 'publisher'
        'is_active',
        'bio',
        'avatar',
        'press_id_code',
        'designation',
        'blood_group',
        'bureau_location',
        'emergency_contact',
        'id_card_issued_at',
        'id_card_expires_at',
        'id_card_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'id_card_issued_at' => 'date',
            'id_card_expires_at' => 'date',
        ];
    }

    public function ensurePressCardInitialized(): void
    {
        $dirty = false;
        if (empty($this->press_id_code)) {
            $prefix = match ($this->role) {
                'admin' => 'PRESS-HQ',
                'editor' => 'PRESS-ED',
                'publisher' => 'PRESS-PUB',
                default => 'PRESS-REP',
            };
            $this->press_id_code = sprintf('%s-2026-%04d', $prefix, $this->id);
            $dirty = true;
        }

        if (empty($this->designation)) {
            $this->designation = $this->role_label;
            $dirty = true;
        }

        if (empty($this->bureau_location)) {
            $this->bureau_location = 'राष्ट्रीय समाचार ब्यूरो (National Bureau)';
            $dirty = true;
        }

        if (empty($this->id_card_issued_at)) {
            $this->id_card_issued_at = $this->created_at ?: now();
            $dirty = true;
        }

        if (empty($this->id_card_expires_at)) {
            $this->id_card_expires_at = now()->addYears(2)->endOfYear();
            $dirty = true;
        }

        if (empty($this->id_card_status)) {
            $this->id_card_status = 'active';
            $dirty = true;
        }

        if ($dirty) {
            $this->save();
        }
    }

    public function hasValidPressCard(): bool
    {
        if ($this->id_card_status !== 'active') {
            return false;
        }
        if ($this->id_card_expires_at && $this->id_card_expires_at->isPast()) {
            return false;
        }
        return true;
    }

    public function getPressCardVerificationUrlAttribute(): string
    {
        return route('press.verify', ['code' => $this->press_id_code]);
    }

    public function getPressCardStatusLabelAttribute(): string
    {
        if ($this->id_card_status === 'suspended') {
            return 'निलंबित (Suspended)';
        }
        if ($this->id_card_expires_at && $this->id_card_expires_at->isPast()) {
            return 'अवधि समाप्त (Expired)';
        }
        return 'सक्रिय व वैध (Active)';
    }

    public function getPressCardStatusBadgeClassAttribute(): string
    {
        if ($this->id_card_status === 'suspended') {
            return 'bg-rose-100 text-rose-800 border-rose-200';
        }
        if ($this->id_card_expires_at && $this->id_card_expires_at->isPast()) {
            return 'bg-amber-100 text-amber-800 border-amber-200';
        }
        return 'bg-emerald-100 text-emerald-800 border-emerald-200';
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEditor(): bool
    {
        return in_array($this->role, ['admin', 'editor']);
    }

    public function isPublisher(): bool
    {
        return in_array($this->role, ['admin', 'editor', 'publisher']);
    }

    public function isReporter(): bool
    {
        return $this->role === 'reporter';
    }

    public function canManageAllArticles(): bool
    {
        return in_array($this->role, ['admin', 'editor', 'publisher']);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'प्रधान संपादक (Admin)',
            'editor' => 'वरिष्ठ संपादक (Editor)',
            'publisher' => 'डिजिटल प्रकाशक (Publisher)',
            'reporter' => 'न्यूज़ रिपोर्टर (Reporter)',
            default => ucfirst($this->role),
        };
    }

    public function getRoleBadgeClassAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'bg-rose-100 text-rose-800 border-rose-200',
            'editor' => 'bg-purple-100 text-purple-800 border-purple-200',
            'publisher' => 'bg-blue-100 text-blue-800 border-blue-200',
            'reporter' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            default => 'bg-slate-100 text-slate-800 border-slate-200',
        };
    }
}
