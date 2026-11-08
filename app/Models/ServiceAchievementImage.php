<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceAchievementImage extends Model
{
    protected $fillable = [
        'service_achievement_id',
        'path',
        'order',
    ];

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(ServiceAchievement::class, 'service_achievement_id');
    }
}
