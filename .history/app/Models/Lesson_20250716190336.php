<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory;
protected $fillable = [
    'title', 'description', 'duration_minutes', 'video_url', 'order'
];
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
