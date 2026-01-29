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
}