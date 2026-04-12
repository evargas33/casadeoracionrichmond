<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Serie extends Model
{
    protected $table = 'series';

    protected $fillable = ['title', 'slug', 'description', 'image', 'active', 'order'];

    protected $casts = ['active' => 'boolean'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn ($m) => $m->slug ??= Str::slug($m->title));
    }

    public function sermons(): HasMany
    {
        return $this->hasMany(Sermon::class, 'series_id')->orderBy('order');
    }
}
