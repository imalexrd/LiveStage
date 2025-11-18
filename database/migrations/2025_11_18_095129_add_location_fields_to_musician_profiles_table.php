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
        Schema::table('musician_profiles', function (Blueprint $table) {
            $table->string('location_address')->nullable()->after('location_state');
            $table->decimal('latitude', 10, 7)->nullable()->after('location_address');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('musician_profiles', function (Blueprint $table) {
            $table->dropColumn(['location_address', 'latitude', 'longitude']);
        });
    }
};
