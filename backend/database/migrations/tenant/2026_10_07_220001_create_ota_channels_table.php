<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ota_channels', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('type')->default('custom'); // booking_com, airbnb, expedia, agoda, custom
            $table->string('status')->default('pending'); // active, inactive, pending
            $table->text('api_key')->nullable();
            $table->text('api_secret')->nullable();
            $table->string('channel_property_id')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->boolean('sync_enabled')->default(false);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ota_channels');
    }
};
