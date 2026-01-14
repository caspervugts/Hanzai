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
            'name' => 'Hokkaidō',
            'tax_percentage' => 2,
        ]);

        DB::table('prefectures')->insert([
            'id' => 2,
            'name' => 'Aomori',
            'tax_percentage' => 1,
        ]);

        DB::table('prefectures')->insert([
            'id' => 3,
            'name' => 'Iwate',
            'tax_percentage' => 10,
        ]);

        DB::table('prefectures')->insert([
            'id' => 4,
            'name' => 'Miyagi',
            'tax_percentage' => 5,
        ]);
        
        DB::table('prefectures')->insert([
            'id' => 5,
            'name' => 'Akita',
            'tax_percentage' => 2,
        ]);

        DB::table('prefectures')->insert([
            'id' => 6,
            'name' => 'Yamagata',
            'tax_percentage' => 3,
        ]);

        DB::table('prefectures')->insert([
            'id' => 7,
            'name' => 'Fukushima',
            'tax_percentage' => 4,
        ]);
    }
}
