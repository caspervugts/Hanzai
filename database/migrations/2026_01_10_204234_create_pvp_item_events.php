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
        Schema::create('pvp_item_events', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('item_id');
            $table->string('event_description', 255);
            $table->integer('event_chance');
            $table->integer('event_damage');
            $table->integer('event_recipient'); //(1 enemy, 2 user)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pvp_item_events');
    }
};
