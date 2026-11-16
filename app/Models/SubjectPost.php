<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectPost extends Model
{
    protected $fillable = ['subject_id', 'user_id', 'type', 'title', 'content', 'deadline', 'max_score'];

    // supaya kolom deadline otomatis menjadi tanggal (Carbon)
    protected $casts = ['deadline' => 'date'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(SubjectComment::class);
    }
}
