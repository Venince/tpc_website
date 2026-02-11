<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Program extends Model
{
    protected $fillable = [
        'code','name','slug','description','logo_path','department','is_active'
    ];

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }
}
