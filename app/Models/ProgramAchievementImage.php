<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramAchievementImage extends Model
{
    protected $fillable = [
        'program_achievement_id',
        'path',
        'order',
    ];

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(ProgramAchievement::class, 'program_achievement_id');
    }
}
