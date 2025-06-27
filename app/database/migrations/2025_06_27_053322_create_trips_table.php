<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnUpdate();

            $table->foreignId('car_id')
                ->constrained()
                ->cascadeOnUpdate();

            $table->timestamp('starts_at');
            $table->timestamp('ends_at');

            $table->enum('status', ['planned', 'approved', 'cancelled'])
                ->default('planned');

            // ускоряет поиск пересечений
            $table->index(['car_id', 'starts_at', 'ends_at']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
