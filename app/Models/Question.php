<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    // Izinkan semua kolom diisi
    protected $guarded = [];

    // PENTING: Casting otomatis array <-> json
    protected $casts = [
        'options' => 'array', 
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Relasi untuk mengambil jawaban user yang sedang login saja.
     * Biasanya digunakan di halaman Quiz siswa.
     */
    public function user_answer()
    {
        return $this->hasOne(UserAnswer::class)->where('user_id', auth()->id());
    }

    /**
     * PERBAIKAN UTAMA:
     * Relasi untuk mengambil SEMUA jawaban dari semua user.
     * Ini yang dicari oleh Controller Report/CourseController (userAnswers).
     */
    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }
}