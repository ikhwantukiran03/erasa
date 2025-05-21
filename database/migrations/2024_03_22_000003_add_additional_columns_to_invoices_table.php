<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('invoice_public_id')->nullable()->after('invoice_path');
            $table->text('invoice_notes')->nullable()->after('invoice_public_id');
            $table->timestamp('due_date')->nullable()->after('amount');
            $table->timestamp('invoice_submitted_at')->nullable()->after('due_date');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_public_id',
                'invoice_notes',
                'due_date',
                'invoice_submitted_at'
            ]);
        });
    }
}; 