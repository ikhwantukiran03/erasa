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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['deposit', 'payment_1', 'payment_2', 'final_payment', 'other']);
            $table->string('invoice_path')->nullable();
            $table->string('invoice_public_id')->nullable(); // For Cloudinary
            $table->decimal('amount', 12, 2)->nullable();
            $table->dateTime('invoice_submitted_at')->nullable();
            $table->dateTime('invoice_verified_at')->nullable();
            $table->foreignId('invoice_verified_by')->nullable()
                ->references('id')->on('users')->nullOnDelete();
            $table->text('invoice_notes')->nullable();
            $table->date('due_date')->nullable(); // Payment due date
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();

            // Unique constraint to prevent duplicate invoice types for the same booking
            $table->unique(['booking_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};