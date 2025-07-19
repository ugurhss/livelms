<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;


    public function initials()
{
    return strtoupper(substr($this->name, 0, 1));
}
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'bio',
        'level'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

 /**
     * Oluşturulan kurslar (Eğitmen için)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Kayıtlı olduğu kurslar (Öğrenci için)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
                   ->withPivot('progress', 'completed_at')
                   ->withTimestamps();
    }

    /**
     * Tamamladığı dersler
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_completions')
                   ->withTimestamps();
    }

    /**
     * Eğitmen mi kontrolü
     * @return bool
     */
    public function isInstructor()
    {
        return $this->role === 'instructor';
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


}
