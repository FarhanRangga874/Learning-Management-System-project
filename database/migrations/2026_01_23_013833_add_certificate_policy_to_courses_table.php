<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            // 'manual' = Admin harus approve dulu
            // 'auto'   = Langsung download saat 100%
            $table->enum('certificate_policy', ['manual', 'auto'])->default('manual')->after('access_type');
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('certificate_policy');
        });
    }
};
