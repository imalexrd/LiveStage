<?php

use App\Models\MusicianProfile;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        MusicianProfile::whereNull('uuid')->get()->each(function ($profile) {
            $profile->uuid = Str::uuid();
            $profile->save();
        });

        Schema::table('musician_profiles', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('musician_profiles', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->change();
        });
    }
};
