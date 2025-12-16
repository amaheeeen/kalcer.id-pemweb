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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // [cite: 299]
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // --- Tambahan Sesuai SRS ---
            // Membedakan Admin, User, dan Pelaku Usaha [cite: 290, 291]
            $table->enum('role', ['admin', 'user', 'business_owner'])->default('user');
            // Nomor HP untuk Pelaku Usaha [cite: 305]
            $table->string('phone_number')->nullable();
            // ---------------------------

            $table->rememberToken();
            $table->timestamps();
        });
        
        // ... (kode di bawahnya biarkan default)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
