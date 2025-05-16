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
        // First, modify any existing 'pending_verification' values to 'waiting for deposit'
        DB::statement("UPDATE bookings SET status = 'waiting for deposit' WHERE status = 'pending_verification'");
        
        // Then modify the enum type
        DB::statement("ALTER TABLE bookings DROP CONSTRAINT bookings_status_check");
        DB::statement("ALTER TABLE bookings ADD CONSTRAINT bookings_status_check CHECK (status::text = ANY (ARRAY['ongoing'::text, 'completed'::text, 'cancelled'::text, 'waiting for deposit'::text, 'pending_verification'::text]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, modify any existing 'pending_verification' values to 'waiting for deposit'
        DB::statement("UPDATE bookings SET status = 'waiting for deposit' WHERE status = 'pending_verification'");
        
        // Then revert the enum type
        DB::statement("ALTER TABLE bookings DROP CONSTRAINT bookings_status_check");
        DB::statement("ALTER TABLE bookings ADD CONSTRAINT bookings_status_check CHECK (status::text = ANY (ARRAY['ongoing'::text, 'completed'::text, 'cancelled'::text, 'waiting for deposit'::text]))");
    }
};
