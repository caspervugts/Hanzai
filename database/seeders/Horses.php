<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class Horses extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('horses')->insert([
            'name' => 'Willem de Tweede',
            'odds' => '0.10',
            'color' => 'yellow'    
        ]);

        DB::table('horses')->insert([
            'name' => 'Shojin',
            'odds' => '0.03',
            'color' => 'gray'    
        ]);

        DB::table('horses')->insert([
            'name' => 'Polski sklep',
            'odds' => '0.01',
            'color' => 'red'    
        ]);

        DB::table('horses')->insert([
            'name' => 'Mark Rutte',
            'odds' => '0.15',
            'color' => 'purple'    
        ]);

        DB::table('horses')->insert([
            'name' => 'Torimashu',
            'odds' => '0.33',
            'color' => 'blue'    
        ]);

        DB::table('horses')->insert([
            'name' => 'King Kawaii',
            'odds' => '0.08',
            'color' => 'orange'    
        ]);

        DB::table('horses')->insert([
            'name' => 'Bojack',
            'odds' => '0.10',
            'color' => 'Black'    
        ]);

        DB::table('horses')->insert([
            //Hoertjes neersteken en vrouwen dood schoppen in de steeg
            'name' => 'Dranakyuek',
            'odds' => '0.20',
            'color' => 'green'    
        ]);
    }
}
