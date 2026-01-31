<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            
            // 1. Cek dulu apakah 'is_preview' sudah ada? 
            // Karena dari file Anda, ini belum ada, jadi kita buatkan.
            if (!Schema::hasColumn('lessons', 'is_preview')) {
                // Kita taruh setelah 'file_path' karena kolom itu ada di migration terakhir Anda
                $table->boolean('is_preview')->default(false)->after('file_path');
            }

            // 2. Sekarang buat kolom 'show_results'
            // Kita taruh setelah 'is_preview' (yang barusan kita pastikan ada)
            if (!Schema::hasColumn('lessons', 'show_results')) {
                $table->boolean('show_results')->default(true)->after('is_preview');
            }
        });
    }

    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            if (Schema::hasColumn('lessons', 'show_results')) {
                $table->dropColumn('show_results');
            }
            if (Schema::hasColumn('lessons', 'is_preview')) {
                $table->dropColumn('is_preview');
            }
        });
    }
};