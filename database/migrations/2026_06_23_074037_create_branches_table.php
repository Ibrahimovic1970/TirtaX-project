<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 150);
            $table->string('address', 255);
            $table->string('city', 100);
            $table->string('province', 100);
            $table->string('postal_code', 20);
            $table->string('phone', 20);
            $table->string('email', 100)->nullable();
            $table->string('pic_name', 100)->nullable();
            $table->string('pic_phone', 20)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_main')->default(false);
            $table->timestamps();

            // Index untuk performa
            $table->index('code');
            $table->index('is_active');
            $table->index('is_main');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};