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
            $table->string('location_city')->nullable()->change();
            $table->string('location_state')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('musician_profiles', function (Blueprint $table) {
            $table->string('location_city')->nullable(false)->change();
            $table->string('location_state')->nullable(false)->change();
        });
    }
};
