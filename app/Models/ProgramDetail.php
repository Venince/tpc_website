<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramDetail extends Model
{
    protected $fillable = [
        'program_id',
        'type',
        'heading',
        'body',
        'items',
        'image_path',
        'caption',
        'order',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    // Section type constants
    const TYPE_TEXT    = 'text';
    const TYPE_LIST    = 'list';
    const TYPE_GALLERY = 'gallery';

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
