<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceSong extends Model
{
    protected $fillable = [
        'service_plan_id',
        'title',
        'artist',
        'song_key',
        'order',
        'notes',
        'onsong_path',
        'pdf_path',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(ServicePlan::class, 'service_plan_id');
    }
}
