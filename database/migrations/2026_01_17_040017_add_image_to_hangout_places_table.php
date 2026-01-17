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
        // Menambahkan kolom image setelah kolom address (opsional urutannya)
        $table->string('image')->nullable()->after('address');
    });
    }

public function down()
    {
    Schema::table('hangout_places', function (Blueprint $table) {
        $table->dropColumn('image');
    });
    }
};
