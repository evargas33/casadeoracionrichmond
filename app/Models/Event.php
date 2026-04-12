<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'title', 'slug', 'description', 'short_description',
        'start_date', 'end_date', 'all_day',
        'location', 'address', 'maps_url',
        'image', 'capacity', 'published', 'featured', 'requires_registration',
    ];

    protected $casts = [
        'start_date'            => 'datetime',
        'end_date'              => 'datetime',
        'all_day'               => 'boolean',
        'published'             => 'boolean',
        'featured'              => 'boolean',
        'requires_registration' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn ($m) => $m->slug ??= Str::slug($m->title));
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function registrations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->published()->where('start_date', '>=', now())->orderBy('start_date');
    }

    public function scopeFeatured($query)
    {
        return $query->published()->where('featured', true);
    }
}
