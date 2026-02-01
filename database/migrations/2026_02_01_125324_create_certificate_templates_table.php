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
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('background_image')->nullable(); // Path gambar background
            $table->string('signature_image')->nullable();  // Path gambar tanda tangan
            $table->string('signature_name')->default('Admin LMS'); // Nama penanda tangan
            $table->string('signature_position')->default('Direktur Utama'); // Jabatan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};
