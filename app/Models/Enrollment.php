<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = 'enrollments';
    
    // Pastikan kolom ini dapat diisi jika diperlukan di masa depan
    protected $fillable = ['user_id', 'course_id', 'joined_at'];

    // Jika joined_at bukan default created_at, tambahkan casting
    protected $casts = [
        'joined_at' => 'datetime',
    ];
}