<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceTechnician extends Model
{
    protected $fillable = [
        'service_plan_id',
        'user_id',
        'name',
        'position',
        'notes',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(ServicePlan::class, 'service_plan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPositionLabelAttribute(): string
    {
        return match($this->position) {
            'mixer'      => 'Mixer / Sonido',
            'proyeccion' => 'Proyección (ProPresenter)',
            'streaming'  => 'Streaming',
            'apoyo'      => 'Apoyo técnico',
            default      => ucfirst($this->position),
        };
    }
}
