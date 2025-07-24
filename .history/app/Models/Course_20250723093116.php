<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    /**
     * JSON olarak kaydedilecek alanlar
     * @var array
     */


   protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'level',
        'category',
        'user_id',
        'price',
        'status',
        'outcomes',
        'prerequisites',
    ];
      protected $casts = [
        'outcomes' => 'array',       // Kazanımlar array olarak
        'prerequisites' => 'array',  // Önkoşullar array olarak
        'price' => 'decimal:2',     // Fiyat decimal
        'original_price' => 'decimal:2'
    ];
    /**
     * Kursun eğitmeni
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kursun dersleri (sıralı)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    /**
     * Kursa kayıtlı öğrenciler
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments')
                   ->withPivot('progress', 'completed_at');
    }

    /**
     * Kursun kategorisi
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Yayında olan kurslar için scope
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function instructor()
{
    return $this->belongsTo(User::class, 'user_id');
}

//yorum kısmı
public function reviews()
{
    return $this->hasMany(Review::class);
}

public function enrollments()
{
    return $this->hasMany(\App\Models\Enrollment::class);
}

}

