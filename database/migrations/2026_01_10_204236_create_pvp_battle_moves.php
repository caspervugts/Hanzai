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
        Schema::create('pvp_battle_moves', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('battle_instance_id'); //instance_id
            $table->foreignId('move_user_id'); //useer_id
            $table->datetime('move_event_id'); //item_event_id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pvp_battle_moves');
    }
};
