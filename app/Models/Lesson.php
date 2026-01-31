<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'title',
        'slug',
        'type', // video/text
        'content', // Ini yang akan diisi WYSIWYG
        'sort_order',
        'video_source', 
        'file_path',
        'is_preview',
        'show_results',
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

        public function questions()
    {
        return $this->hasMany(Question::class);
    }
}