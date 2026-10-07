<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channel_reservations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('ota_channel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->string('external_reservation_id')->index();
            $table->string('external_status');
            $table->string('guest_name');
            $table->string('guest_email')->nullable();
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedSmallInteger('guests_count')->default(1);
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->json('raw_data')->nullable();
            $table->timestamp('imported_at');
            $table->timestamps();

            $table->unique(['ota_channel_id', 'external_reservation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channel_reservations');
    }
};
