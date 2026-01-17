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
    Schema::table('hangout_places', function (Blueprint $table) {
        // Cek dulu: Apakah kolom 'operational_hours' SUDAH ADA?
        if (!Schema::hasColumn('hangout_places', 'operational_hours')) {
            // Kalau BELUM ada, baru buatkan
            $table->string('operational_hours')->default('10:00 - 22:00');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down() 
    {
    Schema::table('hangout_places', function ($table) {
        $table->dropColumn('operational_hours');
    });
    }
};
