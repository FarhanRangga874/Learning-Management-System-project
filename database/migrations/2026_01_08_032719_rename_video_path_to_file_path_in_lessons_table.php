<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::table('lessons', function (Blueprint $table) {
            // Mengganti nama kolom 'video_path' menjadi 'file_path'
            $table->renameColumn('video_path', 'file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::table('lessons', function (Blueprint $table) {
            // Kembalikan nama jika rollback
            $table->renameColumn('file_path', 'video_path');
        });
    }
};
