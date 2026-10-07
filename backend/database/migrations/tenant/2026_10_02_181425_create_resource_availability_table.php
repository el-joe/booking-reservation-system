<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_availability', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('resource_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->integer('available_capacity');
            $table->boolean('is_closed')->default(false);
            $table->string('note')->nullable();
            $table->timestamps();

            $table->unique(['resource_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_availability');
    }
};
