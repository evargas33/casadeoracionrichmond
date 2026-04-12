<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'type', 'description', 'color', 'active'];

    protected $casts = ['active' => 'boolean'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn ($m) => $m->slug ??= Str::slug($m->name));
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function scopeForEvents($query)
    {
        return $query->where('type', 'event');
    }

    public function scopeForPages($query)
    {
        return $query->where('type', 'page');
    }
}
