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
            'name' => 'timbino',
            'email' => 'time_tim@hotmail.com',
            'password' => Hash::make('starcraft'),
            'health' => '100',
            'exp' => '500000',
            'money' => '25655501',
            'gang_exp' => '25000',
            'gang_id' => '1',
            'prefecture_id' => '1',
        ]);

        DB::table('users')->insert([
            'name' => 'noob',
            'email' => 'timridderhof@gmail.com',
            'password' => Hash::make('starcraft'),
            'health' => '100',
            'exp' => '50',
            'money' => '50000',
            'gang_id' => '2',
            'prefecture_id' => '2',
        ]);

        DB::table('users')->insert([
            'name' => 'Dasper',
            'email' => 'test@test.com',
            'password' => Hash::make('welkom01'),
            'health' => '100',
            'exp' => '500000',
            'money' => '25600',
            'gang_id' => '2',
            'prefecture_id' => '3',
        ]);
        
        DB::table('gangs')->insert([
            'name' => 'Admin Gang Gang',
            'description' => 'De gang van de admins',
            'gang_money' => '1000000',
            'total_gang_exp' => '50000',
            'gang_boss_id' => '1',
        ]);
        
        DB::table('gangs')->insert([
            'name' => 'Noob Gang',
            'description' => 'De gang van de noobs',
            'gang_money' => '10000',
            'total_gang_exp' => '50000',
            'gang_boss_id' => '2',
        ]);

        DB::table('gang_approval')->insert([
            'user_id' => '1',
            'gang_id' => '1',
            'status' => '1'
        ]);

        DB::table('gang_approval')->insert([
            'user_id' => '3',
            'gang_id' => '1',
            'status' => '1'
        ]);

        DB::table('gang_approval')->insert([
            'user_id' => '2',
            'gang_id' => '2',
            'status' => '1'
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
            'min_money' => '1',
            'max_money' => '50',
            'exp' => '10',
            'cooldown' => '60'
        ]);
        
        DB::table('crimes_robbery')->insert([
            'name' => 'Rob a young guy',
            'description' => 'Rob a young business man',
            'difficulty' => '40',
            'failure' => 'You got caught and sent to prison. You\'ll be released in 100 seconds.',
            'success' => 'You were succesful in pickpocketing a young business man. Good job. You took ',
            'min_money' => '50',
            'max_money' => '100',
            'exp' => '15',
            'cooldown' => '100'
        ]);

        DB::table('crimes_robbery')->insert([
            'name' => 'Rob a shop owner',
            'description' => 'Rob a business owner',
            'difficulty' => '25',
            'min_money' => '50',
            'max_money' => '200',
            'exp' => '20',
            'failure' => 'You got caught and sent to prison. You\'ll be released in 150 seconds.',
            'success' => 'You were succesful in robbing a store. Well done. You took ',
            'cooldown' => '150'
        ]);

        DB::table('crimes_robbery')->insert([
            'name' => 'Rob a retired police officer',
            'description' => 'Rob a retired police officer coming out of a illegal poker game',
            'difficulty' => '15',
            'min_money' => '50',
            'max_money' => '1000',
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
