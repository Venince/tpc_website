<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name','email','subject','message',
        'ip','user_agent',
        'is_read','read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
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
}
