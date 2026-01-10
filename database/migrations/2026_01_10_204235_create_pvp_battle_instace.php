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
        Schema::create('pvp_battle_instance', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('attacker_id'); //user id
            $table->foreignId('defender_id'); //user id
            $table->datetime('battle_starttime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pvp_battle_instance');
    }
};
