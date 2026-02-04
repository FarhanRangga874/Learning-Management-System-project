<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
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
        'status', 
        'certificate_policy',
    ];

    protected $casts = [
        'course_keypoints' => 'array', 
    ];

    // 2. Definisi Relasi
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    /**
     * PERBAIKAN PENTING:
     * Mengambil Lesson melalui Chapter.
     * Ini digunakan untuk menjembatani Course -> Questions (karena Question ada di Lesson).
     */
    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Chapter::class);
    }

    // Relasi questions() DIHAPUS karena tidak ada course_id di tabel questions.
    // Kita akses questions melalui: $course->lessons->flatMap->questions

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'user_id')
                    ->withPivot('joined_at');
    }

    public function keypoints()
    {
        return $this->hasMany(CourseKeypoint::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}