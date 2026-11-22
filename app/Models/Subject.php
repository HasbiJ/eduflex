<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    // kolom yang boleh diisi lewat Subject::create()
    protected $fillable = [
        'user_id', 
        'name', 
        'class_name', 
        'description', 
        'banner_image'
        ];

    // satu kelas dimiliki satu guru
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // satu kelas punya banyak postingan
    public function posts()
    {
        return $this->hasMany(SubjectPost::class);
    }
}