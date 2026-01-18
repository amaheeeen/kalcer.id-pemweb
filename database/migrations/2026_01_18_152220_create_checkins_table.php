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
    Schema::create('checkins', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('hangout_place_id')->constrained('hangout_places')->onDelete('cascade');
        $table->timestamp('created_at')->useCurrent();
        
        // Opsional: Jika ingin user cuma bisa check-in sekali per 3 jam di tempat yang sama
        // $table->index(['user_id', 'hangout_place_id', 'created_at']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkins');
    }
};
