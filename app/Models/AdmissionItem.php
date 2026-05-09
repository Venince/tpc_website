<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionItem extends Model
{
    protected $fillable = [
        'admission_section_id',
        'title',
        'body',
        'order',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(AdmissionSection::class, 'admission_section_id');
    }
}
