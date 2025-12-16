<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // User yang mereview [cite: 313]
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Tempat yang direview [cite: 313]
            $table->foreignId('hangout_place_id')->constrained()->onDelete('cascade');
            
            $table->integer('rating'); // 1-5
            $table->text('comment');
            $table->timestamp('reviewed_at')->useCurrent();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
