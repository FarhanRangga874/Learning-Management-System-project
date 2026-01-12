<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseKeypoint extends Model
{
    protected $fillable = [
        'name', 
        'course_id'
    ];
}

