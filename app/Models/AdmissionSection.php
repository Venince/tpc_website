<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdmissionSection extends Model
{
    protected $fillable = [
        'key',
        'type',
        'label',
        'note',
        'is_visible',
        'order',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    // Section type constants
    const TYPE_LIST     = 'list';
    const TYPE_STEPS    = 'steps';
    const TYPE_SCHEDULE = 'schedule';
    const TYPE_NOTE     = 'note';

    public function items(): HasMany
    {
        return $this->hasMany(AdmissionItem::class)->orderBy('order');
    }
}
