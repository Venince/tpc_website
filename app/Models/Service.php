<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'featured_image_path',
        'is_active',
        'social_links',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'social_links' => 'array',
    ];

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('created_at');
    }

    // ── Relationships ─────────────────────────────────────────────────

    public function contents(): HasMany
    {
        return $this->hasMany(ServiceContent::class)->orderBy('order');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ServiceAttachment::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(ServiceAchievement::class)
            ->orderBy('order')
            ->orderBy('id');
    }

    // ── Route model binding ───────────────────────────────────────────

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
