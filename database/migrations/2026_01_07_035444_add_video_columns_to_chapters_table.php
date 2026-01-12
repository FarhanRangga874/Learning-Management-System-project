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
        Schema::table('chapters', function (Blueprint $table) {
            // upload atau youtube
            $table->string('video_source')->nullable(); 
            // menyimpan path file upload ATAU youtube ID
            $table->string('video_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::table('chapters', function (Blueprint $table) {
    $table->dropColumn(['video_source', 'video_url']);
        });
    }
};
