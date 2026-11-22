<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'subject',
        'phone',
        'address',
        'date_of_birth',
        'religion',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}