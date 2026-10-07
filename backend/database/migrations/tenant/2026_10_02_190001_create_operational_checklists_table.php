<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operational_checklists', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('booking_type')->nullable();
            $table->enum('trigger', ['pre_booking', 'post_booking', 'daily', 'maintenance']);
            $table->json('items');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operational_checklists');
    }
};
