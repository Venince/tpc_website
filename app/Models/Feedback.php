<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    public const CATEGORIES = [
        'facilities' => 'Facilities',
        'faculty'    => 'Faculty & Instruction',
        'admission'  => 'Admission',
        'website'    => 'Website / Online Services',
        'other'      => 'Other',
    ];

    protected $fillable = [
        'name', 'email', 'rating', 'category', 'message',
        'page_url', 'ip', 'user_agent',
        'is_read', 'read_at',
        'admin_response', 'responded_at',
    ];

    protected $casts = [
        'rating'        => 'integer',
        'is_read'       => 'boolean',
        'read_at'       => 'datetime',
        'responded_at'  => 'datetime',
    ];

    public function markAsRead(): void
    {
        if ($this->is_read) return;

        $this->forceFill([
            'is_read' => true,
            'read_at' => now(),
        ])->save();
    }

    public function markAsUnread(): void
    {
        $this->forceFill([
            'is_read' => false,
            'read_at' => null,
        ])->save();
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? 'General';
    }
}
