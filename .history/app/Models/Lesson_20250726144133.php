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
    $this->extractVideoInfo();
}

// YouTube ID çıkarma
public function extractYouTubeId($url)
{
    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
    return $matches[1] ?? null;
}
public function extractVideoInfo()
{
    if (empty($this->video_url)) {
        $this->video_type = null;
        $this->video_id = null;
        return;
    }

    // Tüm YouTube URL formatlarını destekleyen regex
    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=|youtu\.be\/)([^"&?\/\s]{11})/', $this->video_url, $matches)) {
        $this->video_type = 'youtube';
        $this->video_id = $matches[1];
    } else {
        $this->video_type = 'external';
        $this->video_id = null;
    }
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
