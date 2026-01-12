<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Chapter extends Model
{
    public function before($user, $ability)
    {
        if ($user->role === 'admin') {
            return true;
        }
    }
    /** @use HasFactory<\Database\Factories\ChapterFactory> */
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'type', // Ini request Anda (video/quiz/text)
        'sort_order',
    ];

    // Relasi kebalikan: Bab milik Kursus
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    // Relasi ke Lessons (Materi) - Persiapan untuk next step
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
