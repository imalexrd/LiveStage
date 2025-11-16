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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('location_address')->nullable();
            $table->decimal('location_latitude', 10, 8)->nullable();
            $table->decimal('location_longitude', 11, 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('location_address');
            $table->dropColumn('location_latitude');
            $table->dropColumn('location_longitude');
        });
    }
};
