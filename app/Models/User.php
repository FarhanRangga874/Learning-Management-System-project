<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',   // <-- WAJIB ADA: Agar kita bisa set user sebagai admin/student
        'avatar', // <-- WAJIB ADA: Untuk foto profil
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==========================================
    // TAMBAHAN KHUSUS LMS (WAJIB DITAMBAHKAN)
    // ==========================================

    /**
     * Relasi: User bisa mengikuti banyak Kursus (Enrollment)
     */
    public function courses()
        {
            return $this->belongsToMany(Course::class, 'enrollments', 'user_id', 'course_id')
                        ->withPivot('joined_at'); // <-- WAJIB: Tambahkan ini agar tanggal bisa dibaca
        }

    /**
     * Helper: Cek apakah user sudah mendaftar kursus ini?
     * Fungsi inilah yang dipanggil oleh Controller Anda.
     */
    public function hasJoined(Course $course)
    {
        return $this->courses()->where('course_id', $course->id)->exists();
    }

        public function answers()
    {
        return $this->hasMany(UserAnswer::class);
    }
}