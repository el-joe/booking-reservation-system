<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_rules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('resource_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('rule_type', ['weekday', 'weekend', 'seasonal', 'dynamic']);
            $table->date('applies_from')->nullable();
            $table->date('applies_to')->nullable();
            $table->enum('modifier_type', ['fixed', 'percent']);
            $table->decimal('modifier_value', 10, 2);
            $table->integer('priority')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_rules');
    }
};
