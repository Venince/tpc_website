<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramAchievement extends Model
{
    protected $fillable = [
        'program_id',
        'title',
        'description',
        'year',
        'photo_path',
        'order',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
