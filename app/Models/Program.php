<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    protected $fillable = [
        'code', 'name', 'slug', 'description', 'logo_path', 'department', 'is_active',
    ];

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function people(): HasMany
    {
        return $this->hasMany(ProgramPerson::class)->orderBy('order');
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(ProgramAchievement::class)->orderBy('order');
    }

    // Convenience scopes per role
    public function head(): HasMany
    {
        return $this->hasMany(ProgramPerson::class)
            ->where('role', ProgramPerson::ROLE_HEAD)
            ->orderBy('order');
    }

    public function coordinators(): HasMany
    {
        return $this->hasMany(ProgramPerson::class)
            ->where('role', ProgramPerson::ROLE_COORDINATOR)
            ->orderBy('order');
    }

    public function instructors(): HasMany
    {
        return $this->hasMany(ProgramPerson::class)
            ->where('role', ProgramPerson::ROLE_INSTRUCTOR)
            ->orderBy('order');
    }
}
