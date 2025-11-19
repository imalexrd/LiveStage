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
            $table->decimal('travel_radius_miles')->nullable()->change();
            $table->decimal('max_travel_distance_miles')->nullable()->change();
            $table->decimal('price_per_extra_mile')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('musician_profiles', function (Blueprint $table) {
            $table->decimal('travel_radius_miles')->nullable(false)->change();
            $table->decimal('max_travel_distance_miles')->nullable(false)->change();
            $table->decimal('price_per_extra_mile')->nullable(false)->change();
        });
    }
};
