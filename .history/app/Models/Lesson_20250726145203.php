<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory;
        protected $fillable = [
        'title','slug', 'description', 'duration_minutes', 'video_url', 'order','is_free', 'course_id',
        'video_type', 'video_id', 'video_format', 'embed_code'
    ];
   public function extractVideoInfo()
    {
        if (empty($this->video_url)) {
            $this->video_type = null;
            $this->video_id = null;
            return;
        }

        // YouTube link kontrolü (tüm formatlar için)
        $this->video_id = $this->extractYouTubeId($this->video_url);
        $this->video_type = $this->video_id ? 'youtube' : 'external';
    }

    /**
     * Tüm YouTube URL formatlarından ID çıkarır
     */
    protected function extractYouTubeId($url)
    {
        // 1. ?v=ID formatı
        if (preg_match('/[?&]v=([^&#]+)/', $url, $matches)) {
            return $matches[1];
        }

        // 2. youtu.be/ID formatı
        if (preg_match('/youtu\.be\/([^\/\?]+)/', $url, $matches)) {
            return $matches[1];
        }

        // 3. /embed/ID formatı
        if (preg_match('/\/embed\/([^\/\?]+)/', $url, $matches)) {
            return $matches[1];
        }

        // 4. /v/ID formatı
        if (preg_match('/\/v\/([^\/\?]+)/', $url, $matches)) {
            return $matches[1];
        }

        // 5. /shorts/ID formatı
        if (preg_match('/\/shorts\/([^\/\?]+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function setVideoUrlAttribute($value)
    {
        $this->attributes['video_url'] = $value;
        $this->extractVideoInfo();
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function completedBy()
    {
        return $this->belongsToMany(User::class, 'lesson_completions')
                   ->withTimestamps();
    }

    public function getFormattedDurationAttribute()
    {
        return $this->duration_minutes . ' dakika';
    }

    public function completions()
    {
        return $this->hasMany(LessonCompletion::class);
    }
}
