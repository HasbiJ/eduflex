<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectPost extends Model
{
    protected $fillable = [
    'subject_id', 
    'user_id', 
    'type', 
    'title', 
    'content'
    ];

    // satu postingan ditulis oleh satu guru
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}