<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class Races extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('races')->insert([
            'completed' => 0,
            'closedate' => now()->addMinutes(15),
        ]);

        DB::table('race_horse')->insert([
            'race_id' => 1,
            'horse_id' => 1,    
        ]);

        DB::table('race_horse')->insert([
            'race_id' => 1,
            'horse_id' => 2,    
        ]);

        DB::table('race_horse')->insert([
            'race_id' => 1,
            'horse_id' => 3,    
        ]);

        DB::table('race_horse')->insert([
            'race_id' => 1,
            'horse_id' => 4,    
        ]);

        DB::table('race_horse')->insert([
            'race_id' => 1,
            'horse_id' => 5,    
        ]);

        DB::table('race_horse')->insert([
            'race_id' => 1,
            'horse_id' => 6,    
        ]);

        DB::table('race_horse')->insert([
            'race_id' => 1,
            'horse_id' => 7,    
        ]);

        DB::table('race_horse')->insert([
            'race_id' => 1,
            'horse_id' => 8,    
        ]);
    }
}
