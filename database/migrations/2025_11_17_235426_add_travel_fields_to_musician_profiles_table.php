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
            $table->decimal('travel_radius_miles', 8, 2)->default(0)->after('base_price_per_hour');
            $table->decimal('max_travel_distance_miles', 8, 2)->nullable()->after('travel_radius_miles');
            $table->decimal('price_per_extra_mile', 8, 2)->default(0)->after('max_travel_distance_miles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('musician_profiles', function (Blueprint $table) {
            $table->dropColumn(['travel_radius_miles', 'max_travel_distance_miles', 'price_per_extra_mile']);
        });
    }
};
