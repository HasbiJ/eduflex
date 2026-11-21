<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // kolom yang boleh diisi lewat User::create()
    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    // PENTING: cast 'password' => 'hashed' sengaja DIHAPUS,
    // karena kita sudah memakai Hash::make() manual (kalau tidak, password ter-hash dua kali dan login gagal)
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function learningVideos()
    {
        return $this->hasMany(LearningVideo::class);
    }
}
