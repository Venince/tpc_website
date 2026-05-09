<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramPerson extends Model
{
    protected $fillable = [
        'program_id',
        'role',
        'name',
        'position',
        'photo_path',
        'order',
    ];

    const ROLE_HEAD        = 'head';
    const ROLE_COORDINATOR = 'coordinator';
    const ROLE_INSTRUCTOR  = 'instructor';

    public static array $roleLabels = [
        'head'        => 'Program Head',
        'coordinator' => 'Program Coordinator',
        'instructor'  => 'Instructor',
    ];

    public function getRoleLabelAttribute(): string
    {
        return static::$roleLabels[$this->role] ?? ucfirst($this->role);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
