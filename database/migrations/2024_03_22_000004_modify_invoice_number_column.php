<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, make the column nullable
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->change();
        });

        // Create a function to generate invoice numbers
        DB::statement('
            CREATE OR REPLACE FUNCTION generate_invoice_number()
            RETURNS trigger AS $$
            BEGIN
                NEW.invoice_number := \'INV-\' || to_char(NOW(), \'YYYYMMDD\') || \'-\' || 
                    LPAD(CAST(nextval(\'invoices_id_seq\') AS TEXT), 4, \'0\');
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // Create a trigger to automatically set invoice_number before insert
        DB::statement('
            CREATE TRIGGER set_invoice_number
            BEFORE INSERT ON invoices
            FOR EACH ROW
            WHEN (NEW.invoice_number IS NULL)
            EXECUTE FUNCTION generate_invoice_number();
        ');
    }

    public function down(): void
    {
        // Drop the trigger
        DB::statement('DROP TRIGGER IF EXISTS set_invoice_number ON invoices;');
        
        // Drop the function
        DB::statement('DROP FUNCTION IF EXISTS generate_invoice_number();');

        // Make the column required again
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('invoice_number')->nullable(false)->change();
        });
    }
}; 