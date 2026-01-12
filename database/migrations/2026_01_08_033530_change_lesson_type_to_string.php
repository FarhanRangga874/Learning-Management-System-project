<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <--- Jangan lupa import ini

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah kolom 'type' menjadi VARCHAR(50) agar bisa menampung 'pdf', 'video', 'text', dll
        // Kita gunakan Raw SQL agar tidak perlu install doctrine/dbal
        DB::statement("ALTER TABLE lessons MODIFY COLUMN type VARCHAR(50) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke ENUM jika di-rollback (Opsional)
        DB::statement("ALTER TABLE lessons MODIFY COLUMN type ENUM('video', 'text') NOT NULL");
    }
};