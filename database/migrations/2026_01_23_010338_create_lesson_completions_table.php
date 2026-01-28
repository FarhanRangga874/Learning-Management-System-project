<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_completions', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke User
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Relasi ke Lesson
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            
            // Relasi ke Course (INI YANG SEBELUMNYA HILANG)
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            
            $table->timestamps();
            
            // Mencegah duplikat (Satu user hanya bisa menyelesaikan satu materi sekali)
            $table->unique(['user_id', 'lesson_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_completions');
    }
};