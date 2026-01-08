<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hangout_places', function (Blueprint $table) {
            // Kita pakai 'user_id' yang sudah ada sebagai Owner.
            // Jadi tidak perlu bikin kolom 'owner_id' baru, cukup manfaatkan yang ada.
            
            // Fitur Promo Real-time
            if (!Schema::hasColumn('hangout_places', 'promo_text')) {
                $table->string('promo_text')->nullable(); 
                $table->timestamp('promo_expires_at')->nullable();
            }

            // Fitur Traffic Insight
            if (!Schema::hasColumn('hangout_places', 'profile_views')) {
                $table->integer('profile_views')->default(0);
            }

            // Fitur Claim Listing
            if (!Schema::hasColumn('hangout_places', 'is_claimed')) {
                $table->boolean('is_claimed')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('hangout_places', function (Blueprint $table) {
            $table->dropColumn(['promo_text', 'promo_expires_at', 'profile_views', 'is_claimed']);
        });
    }
};