<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectComment extends Model
{
    protected $fillable = ['subject_post_id', 'user_id', 'comment'];

    public function post()
    {
        return $this->belongsTo(SubjectPost::class, 'subject_post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
