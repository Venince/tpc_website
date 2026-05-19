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
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    // ── Scopes ───────────────────────────────────────────────────────

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('order')->orderBy('title');
    }

    // ── Relationships ─────────────────────────────────────────────────

    public function contents(): HasMany
    {
        return $this->hasMany(ServiceContent::class)->orderBy('order');
    }

    // ── Route model binding ───────────────────────────────────────────

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
