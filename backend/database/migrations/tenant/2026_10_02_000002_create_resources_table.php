<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('resource_type');
            $table->string('booking_type');
            $table->integer('capacity')->default(1);
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->enum('price_unit', ['per_night', 'per_hour', 'per_person', 'per_unit'])->default('per_unit');
            $table->string('status')->default('active');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
