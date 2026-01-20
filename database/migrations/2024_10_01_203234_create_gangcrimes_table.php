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
        Schema::create('crimes_gang', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->string('description');
            $table->string('failure');
            $table->string('success');
            $table->integer('difficulty');
            $table->integer('min_money');
            $table->integer('max_money');
            $table->integer('exp');
            $table->integer('cooldown');
            $table->integer('required_gang_size');
            $table->integer('required_money')->nullable();
            $table->integer('required_weapons')->nullable();
            $table->integer('required_cars')->nullable();
            $table->timestamp('createdate')->useCurrent();
        });

        Schema::create('crimes_gang_performed', function (Blueprint $table) {
            $table->id()->primary();            
            $table->integer('user_id');  
            $table->integer('gang_crime_id');
            $table->integer('cash')->nullable();
            $table->string('result')->nullable();                    
            $table->integer('completed')->default(0); //0 = created, 1 = ready, 2 = completed
            $table->timestamp('createdate')->useCurrent();
            $table->timestamp('releasedate')->nullable();
        });

        Schema::create('crimes_gang_invites', function (Blueprint $table) {
            $table->id()->primary();
            $table->integer('user_id');        
            $table->integer('gang_crime_id');            
            $table->integer('accepted')->default(0); //0 = pending, 1 = accepted, 2 = declined   
            $table->integer('completed')->default(0); //0 = pending, 1 = completed   
        });

        Schema::create('crimes_gang_storage', function (Blueprint $table) {
            $table->id()->primary();
            $table->integer('user_id');        
            $table->integer('gang_crime_id');            
            $table->integer('car_id')->nullable();
            $table->integer('weapon_id')->nullable();            
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crimes_gang');
        Schema::dropIfExists('crimes_gang_invites');
        Schema::dropIfExists('crimes_gang_storage');
    }
};
