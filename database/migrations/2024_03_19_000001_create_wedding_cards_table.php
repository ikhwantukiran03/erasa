<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wedding_cards', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('groom_name');
            $table->string('bride_name');
            $table->date('wedding_date');
            $table->time('ceremony_time');
            $table->string('venue_name');
            $table->text('venue_address');
            $table->string('rsvp_contact_name');
            $table->string('rsvp_contact_info');
            $table->text('custom_message')->nullable();
            $table->integer('template_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wedding_cards');
    }
}; 