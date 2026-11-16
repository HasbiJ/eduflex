<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['user_id', 'name', 'class_name', 'description', 'class_code', 'banner_image'];

    // Satu kelas dimiliki satu guru
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Satu kelas punya banyak postingan (stream & classwork)
    public function posts()
    {
        return $this->hasMany(SubjectPost::class);
    }

    // URL gambar banner (null kalau belum ada gambar)
    public function bannerUrl()
    {
        return $this->banner_image ? asset('storage/' . $this->banner_image) : null;
    }
}
