<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            $table->string('contact_name')->nullable()->after('name');
            $table->string('contact_email')->nullable()->after('contact_name');
            $table->string('phone')->nullable()->after('contact_email');
            $table->string('business_type')->nullable()->after('phone');
            $table->string('status')->default('trial')->after('business_type');
            $table->string('logo')->nullable()->after('status');
            $table->text('notes')->nullable()->after('logo');
            $table->softDeletes()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'contact_name',
                'contact_email',
                'phone',
                'business_type',
                'status',
                'logo',
                'notes',
                'deleted_at',
            ]);
        });
    }
};
