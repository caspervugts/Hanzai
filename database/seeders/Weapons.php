<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class Weapons extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('weapons')->insert([
            'name' => 'Glock-17',
            'damage' => '5',
            'ammo' => '9mm',    
            'value' => '15000'
        ]);

        DB::table('weapons')->insert([
            'name' => 'Mini-Uzi',
            'damage' => '10',
            'ammo' => '9mm',    
            'value' => '35000'
        ]);

        DB::table('weapons')->insert([
            'name' => 'Knife',
            'damage' => '2',            
            'value' => '3500'
        ]);

        DB::table('weapons')->insert([
            'name' => 'Ak-47',
            'damage' => '25',
            'ammo' => '7.62mm',    
            'value' => '150000'
        ]);
    }
}
