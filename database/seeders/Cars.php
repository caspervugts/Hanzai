<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class Cars extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cars')->insert([
            'name' => 'Peugeot 206',
            'description' => 'Peugeot 206',
            'difficulty' => '1',
            'min_money' => '1000',
            'max_money' => '3000',
            'performance' => '10'
        ]);

        DB::table('cars')->insert([
            'name' => 'Renault Twingo',
            'description' => 'Renault Twingo',
            'difficulty' => '1',
            'min_money' => '2000',
            'max_money' => '4000',
            'performance' => '10'
        ]);

        DB::table('cars')->insert([
            'name' => 'Fiat Punto',
            'description' => 'Fiat Punto',
            'difficulty' => '1',
            'min_money' => '500',
            'max_money' => '4500',
            'performance' => '10'
        ]);

        DB::table('cars')->insert([
            'name' => 'BMW Series 3',
            'description' => 'BMW Series 3',
            'difficulty' => '2',
            'min_money' => '2500',
            'max_money' => '10500',
            'performance' => '20'
        ]);

        DB::table('cars')->insert([
            'name' => 'Suzuki Swift',
            'description' => 'Suzuki Swift',
            'difficulty' => '2',
            'min_money' => '4000',
            'max_money' => '8000',
            'performance' => '20'
        ]);

        DB::table('cars')->insert([
            'name' => 'Mazda 3',
            'description' => 'Mazda 3',
            'difficulty' => '2',
            'min_money' => '5000',
            'max_money' => '12000',
            'performance' => '20'
        ]);

        DB::table('cars')->insert([
            'name' => 'Volvo s40',
            'description' => 'Volvo s40',
            'difficulty' => '3',
            'min_money' => '5000',
            'max_money' => '20000',
            'performance' => '10'
        ]);

        DB::table('cars')->insert([
            'name' => 'Mercedes C-Class',
            'description' => 'Mercedes C-Class',
            'difficulty' => '3',
            'min_money' => '8000',
            'max_money' => '35000',
            'performance' => '30'
        ]);

        DB::table('cars')->insert([
            'name' => 'Nissan GT-R',
            'description' => 'Nissan GT-R',
            'difficulty' => '3',
            'min_money' => '10000',
            'max_money' => '40000',
            'performance' => '30'
        ]);

        DB::table('cars')->insert([
            'name' => 'BMW i8',
            'description' => 'BMW i8',
            'difficulty' => '4',
            'min_money' => '40000',
            'max_money' => '80000',
            'performance' => '40'
        ]);

        DB::table('cars')->insert([
            'name' => 'Porsche 911 Turbo S',
            'description' => 'Porsche 911 Turbo S',
            'difficulty' => '4',
            'min_money' => '25000',
            'max_money' => '100000',
            'performance' => '40'
        ]);

        DB::table('cars')->insert([
            'name' => 'Jaguar F-Type',
            'description' => 'Jaguar F-Type',
            'difficulty' => '4',
            'min_money' => '35000',
            'max_money' => '120000',
            'performance' => '40'
        ]);
    }
}
