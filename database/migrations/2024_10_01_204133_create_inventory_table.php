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
        Schema::create('weapons', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->string('description');
            $table->integer('value');
            $table->string('ammo')->nullable();
            $table->integer('damage');
        });

        // Schema::create('armor', function (Blueprint $table) {
        //     $table->id()->primary();
        //     $table->string('name');
        //     $table->integer('value');
        //     $table->integer('amount');
        // });

        Schema::create('user_weapon', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('weapon_id');
            $table->foreignId('user_id');
            $table->foreignId('storage_id')->nullable();
            $table->foreignId('gang_crime_id')->nullable();
            $table->integer('ammo_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('inventory');
        Schema::dropIfExists('weapons');
        Schema::dropIfExists('user_weapon');
    }
};
