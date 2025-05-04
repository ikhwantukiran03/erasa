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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('venue_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->date('booking_date');
            $table->enum('session', ['morning', 'evening']);
            $table->enum('type', ['wedding', 'viewing', 'reservation', 'appointment']);
            $table->enum('status', ['ongoing', 'completed', 'cancelled','waiting for deposit'])->default('reserved');
            $table->date('expiry_date')->nullable();
            $table->foreignId('handled_by')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->timestamps();

            // Adding a unique index on venue_id, booking_date, and session
            // This ensures only one booking per venue per session per date
            $table->unique(['venue_id', 'booking_date', 'session', 'status'], 'unique_venue_date_session_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};