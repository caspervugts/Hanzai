<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class Prefectures extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prefectures')->insert([
            'id' => 1,
            'name' => 'Tokyo',
            'description' => 'The capital city and most populous prefecture of Japan, known for its bustling urban life and rich culture.',
            'investment_cost' => 20000,
            'tax_percentage' => 10,
            'travel_cost' => 2000,
        ]);

        DB::table('prefectures')->insert([
            'id' => 2,
            'name' => 'Kanagawa',
            'description' => 'A prefecture located south of Tokyo, famous for its beautiful coastline and vibrant cities.',
            'investment_cost' => 15000,
            'tax_percentage' => 8,
            'travel_cost' => 1500,
        ]);
        
        DB::table('prefectures')->insert([
            'id' => 3,
            'name' => 'Osaka',
            'description' => 'A major commercial center in Japan, known for its modern architecture and delicious street food.',
            'investment_cost' => 10000,
            'tax_percentage' => 5,
            'travel_cost' => 1000,
        ]);

    }
}
