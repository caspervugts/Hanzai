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
        Schema::create('crimes_robbery', function (Blueprint $table) {
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
        });

        Schema::create('crimes_cartheft', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->string('description');
            $table->string('failure');
            $table->string('success');
            $table->integer('difficulty');
            $table->integer('exp');
            $table->integer('cooldown');
        });

        Schema::create('crimes_financial', function (Blueprint $table) {
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
        });

        Schema::create('crimes_performed', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('userid');        
            $table->integer('crimeid')->nullable();            
            $table->integer('gangcrimeid')->nullable();             
            $table->integer('cash');
            $table->integer('carid')->nullable();
            $table->timestamp('createdate')->useCurrent();
            $table->timestamp('releasedate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crimes_robbery');
        Schema::dropIfExists('crimes_cartheft');
        Schema::dropIfExists('crimes_financial');
        Schema::dropIfExists('crimes_performed');
    }
};
