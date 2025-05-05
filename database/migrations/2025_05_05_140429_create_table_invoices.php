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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('invoice_path')->nullable()->after('handled_by');
            $table->dateTime('invoice_submitted_at')->nullable()->after('invoice_path');
            $table->dateTime('invoice_verified_at')->nullable()->after('invoice_submitted_at');
            $table->foreignId('invoice_verified_by')->nullable()->after('invoice_verified_at')
                ->references('id')->on('users')->nullOnDelete();
            $table->text('invoice_notes')->nullable()->after('invoice_verified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_path',
                'invoice_submitted_at',
                'invoice_verified_at',
                'invoice_verified_by',
                'invoice_notes'
            ]);
        });
    }
};