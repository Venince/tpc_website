<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceContent extends Model
{
    const TYPE_TEXT  = 'text';
    const TYPE_IMAGE = 'image';

    protected $fillable = [
        'service_id',
        'type',
        'heading',
        'body',
        'image_path',
        'image_caption',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    // ── Helpers ───────────────────────────────────────────────────────

    public function isText(): bool
    {
        return $this->type === self::TYPE_TEXT;
    }

    public function isImage(): bool
    {
        return $this->type === self::TYPE_IMAGE;
    }

    // ── Relationships ─────────────────────────────────────────────────

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
