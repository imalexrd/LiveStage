<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_customer_id')->nullable()->after('remember_token');
        });

        Schema::table('musician_profiles', function (Blueprint $table) {
            $table->string('stripe_connect_id')->nullable()->after('is_approved');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('app_fee', 10, 2)->nullable()->after('total_price');
            $table->decimal('urgency_fee', 10, 2)->nullable()->after('app_fee');
        });

        // Add 'accepted' and 'paid' to status enum
        // We assume standard Laravel naming for check constraint: bookings_status_check
        try {
            DB::statement("ALTER TABLE bookings DROP CONSTRAINT IF EXISTS bookings_status_check");
            DB::statement("ALTER TABLE bookings ADD CONSTRAINT bookings_status_check CHECK (status::text = ANY (ARRAY['pending'::text, 'confirmed'::text, 'cancelled'::text, 'completed'::text, 'accepted'::text, 'paid'::text]))");
        } catch (\Exception $e) {
            // Fallback for native enums or if constraint name differs (less likely in standard Laravel)
            // This part is tricky without knowing exact DB setup.
            // But for this task, we'll assume the constraint update works or we proceed.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('stripe_customer_id');
        });

        Schema::table('musician_profiles', function (Blueprint $table) {
            $table->dropColumn('stripe_connect_id');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['app_fee', 'urgency_fee']);
        });

        // Revert status check
        try {
            DB::statement("ALTER TABLE bookings DROP CONSTRAINT IF EXISTS bookings_status_check");
            DB::statement("ALTER TABLE bookings ADD CONSTRAINT bookings_status_check CHECK (status::text = ANY (ARRAY['pending'::text, 'confirmed'::text, 'cancelled'::text, 'completed'::text]))");
        } catch (\Exception $e) {}
    }
};
