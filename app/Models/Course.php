<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    // 1. Izinkan kolom ini diisi
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'description',
        'thumbnail',
        'access_type',
        'access_code',
        'status', // Opsional, jika nanti dipakai
        'certificate_policy',
    ];

    // 2. Definisi Relasi: Kursus milik satu Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function students()
    {
        // Relasi many-to-many ke user melalui tabel pivot enrollments
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'user_id')
                    ->withPivot('joined_at');
    }

    public function keypoints()
    {
        return $this->hasMany(CourseKeypoint::class);
    }

    protected $casts = [
    'course_keypoints' => 'array', // <--- Wajib ada
    ];
}
