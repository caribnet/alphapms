<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wholesaler_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wholesaler_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('rate', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wholesaler_rates');
    }
};
