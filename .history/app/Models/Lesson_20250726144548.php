<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory;
  public function extractVideoInfo()
    {
        if (empty($this->video_url)) {
            $this->video_type = null;
            $this->video_id = null;
            return;
        }

        // DÜZELTİLMİŞ ve TEST EDİLMİŞ YouTube regex
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[\?&]v=|youtu\.be\/)([^"&?\/\s]{11})/', $this->video_url, $matches)) {
            $this->video_type = 'youtube';
            $this->video_id = $matches[1];
        } else {
            $this->video_type = 'external';
            $this->video_id = null;
        }
    }

    /**
     * Video URL set edildiğinde bilgileri çıkar
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
