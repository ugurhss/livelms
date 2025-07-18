<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    /**
     * Kaydın ait olduğu öğrenci
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kaydın ait olduğu kurs
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */


    /**
     * Tamamlanmış kursları getir
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    public function course()
{
    return $this->belongsTo(\App\Models\Course::class);
}
}
