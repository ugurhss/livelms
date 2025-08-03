<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
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
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

        public function initials()
{
    return strtoupper(substr($this->name, 0, 1));
}
      public function courses()
    {
        return $this->hasMany(Course::class);
    }

public function enrollments()
{
    return $this->hasMany(Enrollment::class);
}
public function enrolledCourses()
{
    return $this->belongsToMany(Course::class, 'enrollments')
                ->using(Enrollment::class)
                ->withPivot('progress', 'enrolled_at')
                ->withTimestamps();
}
        public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_completions')
                   ->withTimestamps();
    }
     public function isInstructor()
    {
        return $this->role === 'instructor';
    }
public function isAdmin(): bool
{
    return $this->role === 'admin';
}
public function isStudent(): bool
{
    return $this->role === 'student';
}
}
