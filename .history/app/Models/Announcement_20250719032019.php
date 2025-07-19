<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['title', 'description'];
    public function course()
{
    return $this->belongsTo(Course::class);
}
}
