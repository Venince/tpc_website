<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsPost extends Model
{
    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DECLINED = 'declined';

    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'body',
        'image_path',
        'likes_count',
        'is_published',
        'published_at',
        'status',
        'review_note',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'reviewed_at'  => 'datetime',
    ];

    public function galleryImages(): HasMany
    {
        return $this->hasMany(NewsPostImage::class)->orderBy('order');
    }

    // ── Relationships ──────────────────────────────────────────────

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ── Scopes ─────────────────────────────────────────────────────

    /** Posts visible to the public */
    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_APPROVED)
                 ->where('is_published', true)
                 ->whereNotNull('published_at');
    }

    public function scopePending(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_APPROVED);
    }

    public function scopeDeclined(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_DECLINED);
    }

    // ── Helpers ────────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isDeclined(): bool
    {
        return $this->status === self::STATUS_DECLINED;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_DECLINED => 'Declined',
            default               => 'Pending Review',
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'green',
            self::STATUS_DECLINED => 'red',
            default               => 'yellow',
        };
    }
}
