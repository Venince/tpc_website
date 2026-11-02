<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ServiceAttachment extends Model
{
    /**
     * File types a browser can display inline, so the visitor can print them
     * straight from the viewer. Everything else is download-only.
     */
    public const PRINTABLE_TYPES = [
        'pdf'  => 'application/pdf',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
    ];

    protected $fillable = [
        'service_id',
        'label',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'sort_order',
    ];

    protected $casts = [
        'file_size'  => 'integer',
        'sort_order' => 'integer',
    ];

    // ── Accessors ─────────────────────────────────────────────────────

    public function getExtensionAttribute(): string
    {
        return strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION));
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = (int) $this->file_size;

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 0) . ' KB';
        }

        return $bytes . ' B';
    }

    /**
     * Friendly file name used when the visitor saves the file,
     * e.g. "Enrollment Form" + pdf => "Enrollment-Form.pdf".
     */
    public function getDownloadNameAttribute(): string
    {
        $base = Str::slug($this->label) ?: 'file';

        return $this->extension ? $base . '.' . $this->extension : $base;
    }

    // ── Helpers ───────────────────────────────────────────────────────

    public function isPrintable(): bool
    {
        return array_key_exists($this->extension, self::PRINTABLE_TYPES);
    }

    public function inlineMimeType(): ?string
    {
        return self::PRINTABLE_TYPES[$this->extension] ?? null;
    }

    // ── Relationships ─────────────────────────────────────────────────

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
