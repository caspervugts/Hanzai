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
        Schema::create('gangs', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->string('description');
            $table->integer('gang_money');
            $table->integer('total_gang_exp');
            $table->foreignId('gang_boss_id')->references('id')->on('users');
        });    

        Schema::create('gang_approval', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('user_id')->references('id')->on('users');;
            $table->foreignId('gang_id')->references('id')->on('gangs');
            $table->integer('status'); // 0 = pending, 1 = approved, 2 = rejected
        });   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gangs');
        Schema::dropIfExists('gang_approval');
    }
};
