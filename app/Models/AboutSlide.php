<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSlide extends Model
{
    protected $fillable = [
        'title',
        'caption',
        'image_path',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope: only active slides, ordered by sort_order.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
