<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceUsher extends Model
{
    protected $fillable = [
        'service_plan_id',
        'user_id',
        'name',
        'assignment',
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

    public function getAssignmentLabelAttribute(): string
    {
        return match($this->assignment) {
            'entrada'  => 'Entrada',
            'ofrendas' => 'Ofrendas',
            'general'  => 'General',
            'apoyo'    => 'Apoyo',
            default    => ucfirst($this->assignment),
        };
    }
}
