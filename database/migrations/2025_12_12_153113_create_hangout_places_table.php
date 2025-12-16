<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hangout_places', function (Blueprint $table) {
            $table->id(); // ID_Tempat
            // Relasi ke Pelaku Usaha (bisa null jika diinput admin) [cite: 309]
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('name'); // Nama_Tempat
            $table->text('address'); // Alamat_Lengkap
            
            // Koordinat Peta [cite: 307]
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            
            $table->string('category'); // Kafe, Restoran, dll
            $table->json('facilities')->nullable(); // Disimpan sebagai JSON ["WiFi", "AC"] [cite: 309]
            $table->string('operational_hours')->nullable();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable(); // Foto Utama
            
            // Analitik & Viralitas [cite: 310]
            $table->enum('crowd_level', ['sepi', 'sedang', 'ramai', 'penuh'])->default('sepi');
            $table->integer('viral_score')->default(0); 
            $table->boolean('is_verified')->default(false); // Validasi Admin
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hangout_places');
    }
};
