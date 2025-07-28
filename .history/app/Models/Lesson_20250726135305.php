<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory;
protected $fillable = [
    'title','slug', 'description', 'duration_minutes', 'video_url', 'order','is_free', 'course_id',
    'video_type', // 'youtube', 'vimeo', 'html5', 'external'
    'video_id', // YouTube/Vimeo ID'si
    'video_format', // mp4, webm vb.
    'embed_code', // Harici embed kodu
];

public function setVideoUrlAttribute($value)
{
    $this->attributes['video_url'] = $value;

    if (empty($value)) {
        $this->attributes['video_type'] = null;
        $this->attributes['video_id'] = null;
        return;
    }

    if (str_contains($value, 'youtube.com') || str_contains($value, 'youtu.be')) {
        $this->attributes['video_type'] = 'youtube';
        $this->attributes['video_id'] = $this->extractYouTubeId($value);
    }
    elseif (str_contains($value, 'vimeo.com')) {
        $this->attributes['video_type'] = 'vimeo';
        $this->attributes['video_id'] = $this->extractVimeoId($value);
    }
    elseif (preg_match('/\.(mp4|webm|ogg)$/i', $value)) {
        $this->attributes['video_type'] = 'html5';
    }
    else {
        $this->attributes['video_type'] = 'external';
    }
}

// YouTube ID çıkarma
protected function extractYouTubeId($url)
{
    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
    return $matches[1] ?? null;
}

public function getEmbedUrlAttribute()
{
    if (str_contains($this->video_url, 'youtube.com')) {
        $videoId = $this->extractYouTubeId($this->video_url);
        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
    }
    return $this->video_url;
}
// Vimeo ID çıkarma
protected function extractVimeoId($url)
{
    preg_match('/vimeo.com\/(?:channels\/|groups\/[^\/]*\/videos\/|album\/\d+\/video\/|)(\d+)(?:$|\/|\?)/', $url, $matches);
    return $matches[1] ?? null;
}
    /**
     * Dersin bağlı olduğu kurs
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Dersi tamamlayan öğrenciler
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function completedBy()
    {
        return $this->belongsToMany(User::class, 'lesson_completions')
                   ->withTimestamps();
    }

    /**
     * Süreyi dakika:formatında getirir
     * @return string
     */
    public function getFormattedDurationAttribute()
    {
        return $this->duration_minutes . ' dakika';
    }
    // App\Models\Lesson.php
public function completions()
{
    return $this->hasMany(LessonCompletion::class);
}

}
