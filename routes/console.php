<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\Heartbeat;
use Illuminate\Support\Facades\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    //$completed =  Carbon::now()->addSeconds(600);
    $closeDate =  Carbon::now()->addSeconds(300);
    for($i = 1; $i < 9; $i++){
        DB::table('races')->insert([
            'horse_id' => $i,
            'closedate' => $closeDate           
        ]);
    }
})->everyFiveMinutes();

//Schedule::job(new Heartbeat)->everyFiveMinutes();
//Schedule::call(new createRace)->everyFiveMinutes();