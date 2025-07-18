<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug']; // veya protected $guarded = [];

    /**
     * Kategorideki kurslar
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Aktif kurs sayısı
     * @return int
     */
    public function getActiveCoursesCountAttribute()
    {
        return $this->courses()->published()->count();
    }
}
