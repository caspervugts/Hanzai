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
        Schema::create('cars', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->string('description');
            $table->integer('difficulty');
            $table->integer('min_money');
            $table->integer('max_money');
            $table->integer('performance');            
        });

        Schema::create('car_user', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('user_id');
            $table->foreignId('car_id');
            $table->integer('value');
            $table->foreignId('storage_id')->nullable();
            $table->foreignId('gang_crime_id')->nullable();
            $table->timestamp('createdate')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
        Schema::dropIfExists('car_user');
    }
};
