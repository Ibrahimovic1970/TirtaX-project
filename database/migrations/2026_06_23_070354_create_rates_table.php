<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->string('origin_city', 100);
            $table->string('destination_city', 100);
            $table->decimal('price_per_kg', 10, 2);
            $table->decimal('base_price', 10, 2)->default(0);
            $table->string('service_type', 50)->default('REGULER');
            $table->integer('estimation_days')->default(3);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index untuk performa
            $table->index(['origin_city', 'destination_city']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};