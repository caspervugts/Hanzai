<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        DB::table('users')->insert([
            'name' => 'Timbino',
            'email' => 'time_tim@hotmail.com',
            'password' => Hash::make('starcraft11'),
            'health' => '100', 
            'exp' => '50',
            'prefecture_id' => '1',
        ]);

        DB::table('users')->insert([
            'name' => 'NoobMaster',
            'email' => 'noobmaster@gmail.com',
            'password' => Hash::make('starcraft22'),
            'health' => '100',
            'exp' => '50',
            'prefecture_id' => '1',
        ]);

        DB::table('users')->insert([
            'name' => 'Dasper',
            'email' => 'test@test.com',
            'password' => Hash::make('welkom01'),
            'health' => '100',
            'exp' => '50',
            'prefecture_id' => '1',
        ]);

         DB::table('cities')->insert([
            'name' => 'Tokyo',
            'description' => 'The capital of Japan, known for its bustling streets and vibrant culture.',
        ]);

        DB::table('crimes_robbery')->insert([
            'name' => 'Rob a tourist',
            'description' => 'Rob a distracted tourist',
            'difficulty' => '50',
            'failure' => 'You got caught and sent to prison. You\'ll be released in 60 seconds.',
            'success' => 'You were succesful in pickpocketing a tourist. Congrats. You took ',
            'min_money' => '100',
            'max_money' => '500',
            'exp' => '10',
            'cooldown' => '60'
        ]);
        
        DB::table('crimes_robbery')->insert([
            'name' => 'Rob a young guy',
            'description' => 'Rob a young business man',
            'difficulty' => '40',
            'failure' => 'You got caught and sent to prison. You\'ll be released in 100 seconds.',
            'success' => 'You were succesful in pickpocketing a young business man. Good job. You took ',
            'min_money' => '400',
            'max_money' => '800',
            'exp' => '15',
            'cooldown' => '100'
        ]);

        DB::table('crimes_robbery')->insert([
            'name' => 'Rob a shop owner',
            'description' => 'Rob a business owner',
            'difficulty' => '25',
            'min_money' => '700',
            'max_money' => '1500',
            'exp' => '20',
            'failure' => 'You got caught and sent to prison. You\'ll be released in 150 seconds.',
            'success' => 'You were succesful in robbing a store. Well done. You took ',
            'cooldown' => '150'
        ]);

        DB::table('crimes_robbery')->insert([
            'name' => 'Rob a retired police officer',
            'description' => 'Rob a retired police officer coming out of a illegal poker game',
            'difficulty' => '15',
            'min_money' => '2000',
            'max_money' => '5000',
            'exp' => '30',
            'failure' => 'You got caught and sent to prison. You\'ll be released in 200 seconds.',
            'success' => 'You were succesful in robbing the police officer and you got some good shit from it. You took ',
            'cooldown' => '200'
        ]);

        DB::table('crimes_cartheft')->insert([
            'name' => 'Steal a crap car',
            'description' => 'Stealing a crappy car in a quiet suburban neighborhood',
            'difficulty' => '50',
            'exp' => '10',
            'failure' => 'You got caught and sent to prison. You\'ll be released in 120 seconds.',
            'success' => 'You were succesful in stealing a piece of shit car. Woohoo. You stole a ',
            'cooldown' => '120'
        ]);
        
        DB::table('crimes_cartheft')->insert([
            'name' => 'Steal a new car',
            'description' => 'Stealing a new car from a public parking lot',
            'difficulty' => '30',
            'exp' => '10',
            'failure' => 'You got caught and sent to prison. You\'ll be released in 200 seconds.',
            'success' => 'You were succesful in stealing a car from the parking lot. You stole a ',
            'cooldown' => '200'
        ]);

        DB::table('crimes_cartheft')->insert([
            'name' => 'Steal a luxury car',
            'description' => 'Stealing a luxury vehicle in a high-security apartment complex',
            'difficulty' => '20',
            'exp' => '10',
            'failure' => 'You got caught and sent to prison. You\'ll be released in 240 seconds.',
            'success' => 'You were succesful in stealing a car from a well secured place. Bravo. You stole a ',
            'cooldown' => '240'
        ]);

        DB::table('crimes_cartheft')->insert([
            'name' => 'Steal a supercar',
            'description' => 'Stealing a supercar from a private estate',
            'difficulty' => '10',
            'exp' => '100',
            'failure' => 'You got caught and sent to prison. You\'ll be released in 300 seconds.',
            'success' => 'Holy shit, you stole a supercar. Congratulations. You stole a ',
            'cooldown' => '300'
        ]);

        DB::table('foods')->insert([
            'name' => 'Onigiri',
            'description' => 'A traditional Japanese rice ball, often wrapped in seaweed and filled with savory ingredients.',
            'health_restore' => '2',
            'value' => '1000'
        ]);

        DB::table('foods')->insert([
            'name' => 'Instant Noodles',
            'description' => 'A quick and easy meal option, often flavored with various seasonings and toppings.',
            'health_restore' => '5',
            'value' => '2000'
        ]);

        DB::table('foods')->insert([
            'name' => 'Packed French Toast',
            'description' => 'A delicious breakfast item made by soaking bread in a mixture of eggs and milk.',
            'health_restore' => '14',
            'value' => '5000'
        ]);

        DB::table('foods')->insert([
            'name' => 'Japanese Bento Box',
            'description' => 'A balanced meal typically consisting of rice, fish or meat, and pickled or cooked vegetables, all neatly packed in a box.',
            'health_restore' => '30',
            'value' => '10000'
        ]);

        DB::table('crimes_gang')->insert([
            'name' => 'Rob a konbini',
            'description' => 'Robbing a konbini in a quiet suburban neighborhood',
            'difficulty' => '50',
            'exp' => '75',
            'failure' => 'You got caught and sent to prison. You\'ll be released in 300 seconds.',
            'success' => 'You were succesful in robbing a konbini. Woohoo. You took ',
            'cooldown' => '300',
            'min_money' => '1500',
            'max_money' => '15000',
            'required_gang_size' => '2',
            'required_money' => '0',
            'required_weapons' => '1',
            'required_cars' => '1'            
        ]);
        
        DB::table('crimes_gang')->insert([
            'name' => 'Rob a Pachinko parlor',
            'description' => 'Robbing a Pachinko parlor in a busy shopping district',
            'difficulty' => '30',
            'exp' => '150',
            'failure' => 'You got caught and sent to prison. You\'ll be released in 400 seconds.',
            'success' => 'You were succesful in robbing a Pachinko parlor. You took ',
            'cooldown' => '400',
            'min_money' => '5000',
            'max_money' => '20000',
            'required_gang_size' => '3',
            'required_money' => '1000',
            'required_weapons' => '2',
            'required_cars' => '1' 
        ]);

        DB::table('crimes_gang')->insert([
            'name' => 'Rob a jewelry store',
            'description' => 'Rob a jewelry store in a high-end shopping district',
            'difficulty' => '20',
            'exp' => '200',
            'failure' => 'You got caught and sent to prison. You\'ll be released in 240 seconds.',
            'success' => 'You were succesful in robbing a jewelry store. Woohoo. You took ',
            'cooldown' => '240',
            'min_money' => '25000',
            'max_money' => '45000',
            'required_gang_size' => '3',
            'required_money' => '2500',
            'required_weapons' => '2',
            'required_cars' => '2'  
        ]);

        DB::table('crimes_gang')->insert([
            'name' => 'Rob a bank',
            'description' => 'Robbing a high-security bank in the financial district',
            'difficulty' => '10',
            'exp' => '300',
            'failure' => 'You got caught and sent to prison. You\'ll be released in 600 seconds.',
            'success' => 'Holy shit, you robbed a bank. Congratulations. You took ',
            'cooldown' => '600',
            'min_money' => '40000',
            'max_money' => '150000',
            'required_gang_size' => '4',
            'required_money' => '10000',
            'required_weapons' => '4',
            'required_cars' => '2'  
        ]);

        $this->call([
            Horses::class,
            Weapons::class,
            ChatMessages::class,
            Races::class,
            Cars::class,
            Prefectures::class,
        ]);
    }
}
