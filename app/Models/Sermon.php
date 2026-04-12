<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Sermon extends Model
{
    protected $fillable = [
        'series_id', 'title', 'slug', 'speaker', 'date',
        'description', 'bible_passage',
        'audio_url', 'video_url', 'image',
        'duration_minutes', 'published', 'published_at', 'order',
    ];

    protected $casts = [
        'date'         => 'date',
        'published_at' => 'datetime',
        'published'    => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn ($m) => $m->slug ??= Str::slug($m->title));
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(Serie::class, 'series_id');
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeRecent($query, int $limit = 6)
    {
        return $query->published()->orderByDesc('date')->limit($limit);
    }

    public function getThumbnailAttribute(): ?string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        if ($this->video_url) {
            if (preg_match('/(?:youtube\.com\/(?:watch\?v=|live\/|shorts\/|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $this->video_url, $m)) {
                return 'https://img.youtube.com/vi/' . $m[1] . '/hqdefault.jpg';
            }
        }

        return null;
    }
}
