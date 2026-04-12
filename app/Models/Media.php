<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    protected $fillable = [
        'user_id', 'name', 'disk_name', 'path', 'url',
        'mime_type', 'size', 'mediable_type', 'mediable_id',
        'width', 'height', 'duration_seconds', 'alt_text',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getTypeAttribute(): string
    {
        return match (true) {
            str_starts_with($this->mime_type, 'image/') => 'image',
            str_starts_with($this->mime_type, 'audio/') => 'audio',
            str_starts_with($this->mime_type, 'video/') => 'video',
            default                                      => 'file',
        };
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1_048_576) return round($bytes / 1_048_576, 1) . ' MB';
        if ($bytes >= 1_024)    return round($bytes / 1_024, 1) . ' KB';
        return $bytes . ' B';
    }

    public function getPublicUrlAttribute(): string
    {
        return $this->url ?? asset('storage/' . $this->path);
    }
}
