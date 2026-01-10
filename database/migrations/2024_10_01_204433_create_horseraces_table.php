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
        Schema::create('horses', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            //zet odds op race bij horse id zodat de 
            $table->decimal('odds');
            $table->string('color');
        });

        Schema::create('bets', function (Blueprint $table) {
            $table->id()->primary();
            $table->integer('amount');
            $table->integer('horse_id');
            $table->integer('race_id');
            $table->integer('user_id');
        });

        Schema::create('races', function (Blueprint $table) {
            $table->id()->primary();            
            $table->integer('completed')->default(0);
            $table->integer('winner')->nullable();    
            $table->timestamp('closedate');
        });

        Schema::create('bet_race', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('bet_id');
            $table->foreignId('race_id');
        });

        Schema::create('bet_horse', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('bet_id');
            $table->foreignId('horse_id');
        });

        Schema::create('race_horse', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('race_id');
            $table->foreignId('horse_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horses');
        Schema::dropIfExists('bets');
        Schema::dropIfExists('races');
        Schema::dropIfExists('bet_race');
        Schema::dropIfExists('bet_horse');
        Schema::dropIfExists('race_horse');
    }
};
