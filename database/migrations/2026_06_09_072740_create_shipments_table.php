<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique(); // Immutable
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('origin_city');
            $table->string('destination_city');
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->text('receiver_address');
            $table->decimal('weight', 8, 2); // dalam KG
            $table->decimal('total_cost', 12, 2);
            $table->string('status')->default('created'); // created, paid, picked_up, in_transit, delivered, cancelled
            $table->timestamps();

            $table->index('tracking_number');
            $table->index('status');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};