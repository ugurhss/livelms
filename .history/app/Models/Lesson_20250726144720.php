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

        // SIMPLIFIED YOUTUBE URL PARSING
        if (str_contains($this->video_url, 'youtube.com') || str_contains($this->video_url, 'youtu.be')) {
            $this->video_type = 'youtube';
            $this->video_id = $this->parseYoutubeId($this->video_url);
        } else {
            $this->video_type = 'external';
            $this->video_id = null;
        }
    }

    /**
     * Robust YouTube ID parser
     */
    protected function parseYoutubeId($url)
    {
        // Check for standard watch URL
        if (preg_match('/[?&]v=([^&]+)/', $url, $matches)) {
            return $matches[1];
        }
        // Check for youtu.be short URL
        if (preg_match('/youtu\.be\/([^\?]+)/', $url, $matches)) {
            return $matches[1];
        }
        // Check for embed URL
        if (preg_match('/\/embed\/([^\?]+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Set video URL attribute
     */
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
