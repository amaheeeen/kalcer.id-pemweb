<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hangout_places', function (Blueprint $table) {
            // Cek dulu: Kalau kolom belum ada, baru dibuat.
            if (!Schema::hasColumn('hangout_places', 'is_verified')) {
                // is_verified: false = Pending, true = Verified
                $table->boolean('is_verified')->default(false)->after('is_claimed');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hangout_places', function (Blueprint $table) {
            if (Schema::hasColumn('hangout_places', 'is_verified')) {
                $table->dropColumn('is_verified');
            }
        });
    }
};