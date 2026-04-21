<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServicePlan extends Model
{
    protected $fillable = [
        'date',
        'title',
        'service_type',
        'status',
        'sermon_topic',
        'bible_passage',
        'sermon_notes_path',
        'bible_citations_path',
        'worship_uniform_color',
        'worship_uniform_notes',
        'usher_uniform_color',
        'usher_uniform_notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function songs(): HasMany
    {
        return $this->hasMany(ServiceSong::class)->orderBy('order');
    }

    public function ushers(): HasMany
    {
        return $this->hasMany(ServiceUsher::class);
    }

    public function technicians(): HasMany
    {
        return $this->hasMany(ServiceTechnician::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'publicado');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->startOfDay());
    }

    public function getServiceTypeLabelAttribute(): string
    {
        return match($this->service_type) {
            'domingo'  => 'Domingo',
            'sabado'   => 'Sábado',
            'viernes'  => 'Viernes',
            'especial' => 'Especial',
            default    => ucfirst($this->service_type),
        };
    }
}
