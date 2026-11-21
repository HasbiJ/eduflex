<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningVideo extends Model
{
    protected $fillable = [
        'title',
        'description',
        'video_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
