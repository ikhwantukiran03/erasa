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
        Schema::create('wedding_card_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wedding_card_id');
            $table->foreign('wedding_card_id')->references('id')->on('wedding_cards')->onDelete('cascade');
            $table->string('commenter_name');
            $table->string('commenter_email')->nullable();
            $table->text('comment');
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wedding_card_comments');
    }
}; 