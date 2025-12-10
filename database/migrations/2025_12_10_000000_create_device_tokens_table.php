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
        Schema::create('device_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Use string with length so MySQL can create an index on it
            $table->string('fcm_token', 191)->unique(); // FCM token harus unique untuk menghindari duplikasi
            $table->string('device_id')->nullable(); // ID device untuk tracking (e.g., device fingerprint)
            $table->string('device_name')->nullable(); // Nama device (e.g., "Samsung A10", "iPhone 12")
            $table->string('platform')->nullable(); // Platform: 'ios', 'android', 'web'
            $table->string('app_version')->nullable(); // Versi app
            $table->timestamp('last_used_at')->nullable(); // Waktu terakhir device menggunakan token ini
            $table->boolean('is_active')->default(true); // Token masih aktif atau tidak
            $table->timestamps();

            // Index untuk queries cepat
            $table->index('user_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_tokens');
    }
};
