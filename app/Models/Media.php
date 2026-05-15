<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'uploaded_by',
        'disk',
        'path',
        'original_name',
        'mime_type',
        'extension',
        'size',
        'thumbnail_path',
    ];

    // Relations
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function message()
    {
        return $this->hasOne(Message::class);
    }

    // Helpers
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return asset('storage/' . $this->thumbnail_path);
        }
        return null;
    }

    public function isImage()
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function isVideo()
    {
        return str_starts_with($this->mime_type, 'video/');
    }

    public function isAudio()
    {
        return str_starts_with($this->mime_type, 'audio/');
    }
}