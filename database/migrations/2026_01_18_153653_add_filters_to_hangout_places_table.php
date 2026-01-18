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
    Schema::table('hangout_places', function (Blueprint $table) {
        // 1 = Murah ($), 2 = Sedang ($$), 3 = Mahal ($$$)
        $table->tinyInteger('price_range')->default(1)->after('category');

        // Target persona: introvert (tenang), extrovert (ramai), ambivert (santai)
        $table->enum('personality_type', ['introvert', 'extrovert', 'ambivert'])->default('ambivert')->after('price_range');
    });
    }

public function down(): void
    {
    Schema::table('hangout_places', function (Blueprint $table) {
        $table->dropColumn(['price_range', 'personality_type']);
    });
    }
};
