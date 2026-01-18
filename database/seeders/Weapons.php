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
            'name' => 'Knife',
            'description' => 'A sharp blade for close combat.',
            'damage' => '2500',            
            'value' => '3500'
        ]);

        DB::table('weapons')->insert([
            
            'name' => 'Glock-17',
            'description' => 'A compact semi-automatic pistol.',
            'damage' => '3',
            'ammo' => '9mm',    
            'value' => '15000'
        ]);

        DB::table('weapons')->insert([
            'name' => 'Mini-Uzi',
            'description' => 'A small, lightweight submachine gun.',
            'damage' => '10',
            'ammo' => '9mm',    
            'value' => '30000'
        ]);        

        DB::table('weapons')->insert([
            'name' => 'Ak-47',
            'description' => 'A powerful assault rifle.',
            'damage' => '20',
            'ammo' => '7.62mm',    
            'value' => '150000'
        ]);

        //glock-17 events
        DB::table('pvp_item_events')->insert([
            'item_id' => '2', // Glock-17
            'event_description_part_one' => 'fired a shot from his/her Glock-17 and hit',
            'event_description_part_two' => 'in the arm dealing 2 damage.',
            'event_chance' => '70',
            'event_damage' => '2',
            'event_recipient' => '2'
        ]); 

        DB::table('pvp_item_events')->insert([
            'item_id' => '2', // Glock-17
            'event_description_part_one' => 'fired a shot from his/her Glock-17 and hit',
            'event_description_part_two' => 'in the head dealing 25 damage.',
            'event_chance' => '10',
            'event_damage' => '25',
            'event_recipient' => '2' 
        ]); 

        DB::table('pvp_item_events')->insert([
            'item_id' => '2', // Glock-17
            'event_description_part_one' => 'fired a shot from his/her Glock-17 and missed',
            'event_description_part_two' => 'completely',
            'event_chance' => '50',
            'event_damage' => '0',
            'event_recipient' => '2' 
        ]); 

        DB::table('pvp_item_events')->insert([
            'item_id' => '2', // Glock-17
            'event_description_part_one' => 'fired a shot from his/her Glock-17 and shot',
            'event_description_part_two' => 'in the foot dealing 5 damage. Stupid ass.',
            'event_chance' => '5',
            'event_damage' => '5',
            'event_recipient' => '1' 
        ]); 
        

        //ak47 events
        DB::table('pvp_item_events')->insert([
            'item_id' => '4', // Ak-47
            'event_description_part_one' => 'fired a shot from his/her Ak-47 and hit',
            'event_description_part_two' => 'in the arm dealing 25 damage.',
            'event_chance' => '70',
            'event_damage' => '25',
            'event_recipient' => '2'
        ]); 

        DB::table('pvp_item_events')->insert([
            'item_id' => '4', // Ak-47  
            'event_description_part_one' => 'fired a shot from his/her Ak-47 and hit',
            'event_description_part_two' => 'in the head dealing 85 damage.',
            'event_chance' => '10',
            'event_damage' => '85',
            'event_recipient' => '2' 
        ]); 

        DB::table('pvp_item_events')->insert([
            'item_id' => '4', // Ak-47
            'event_description_part_one' => 'fired a shot from his/her Ak-47 and missed',
            'event_description_part_two' => 'completely.',
            'event_chance' => '50',
            'event_damage' => '0',
            'event_recipient' => '2' 
        ]); 

        DB::table('pvp_item_events')->insert([
            'item_id' => '4', // Ak-47
            'event_description_part_one' => 'fired a shot from his/her Ak-47. The bullet ricocheted off the wall and hits',
            'event_description_part_two' => 'in the leg dealing 25 damage. Unlucky.',
            'event_chance' => '5',
            'event_damage' => '25',
            'event_recipient' => '1' 
        ]); 

        //Knife events
        DB::table('pvp_item_events')->insert([
            'item_id' => '1', // Knife
            'event_description_part_one' => 'stabbed',
            'event_description_part_two' => 'in the arm dealing 2 damage.',
            'event_chance' => '70',
            'event_damage' => '2',
            'event_recipient' => '2'
        ]); 

        DB::table('pvp_item_events')->insert([
            'item_id' => '1', // Knife
            'event_description_part_one' => 'stabbed',
            'event_description_part_two' => 'in the head dealing 10 damage.',
            'event_chance' => '10',
            'event_damage' => '10',
            'event_recipient' => '2' 
        ]); 

        DB::table('pvp_item_events')->insert([
            'item_id' => '1', // Knife
            'event_description_part_one' => 'hurt himself/herself trying to stab',
            'event_description_part_two' => 'in the arm dealing 2 damage to him/herself.',
            'event_chance' => '5',
            'event_damage' => '2',
            'event_recipient' => '1'
        ]); 

        DB::table('pvp_item_events')->insert([
            'item_id' => '1', // Knife
            'event_description_part_one' => 'stabbed',
            'event_description_part_two' => 'in the leg dealing 4 damage.',
            'event_chance' => '70',
            'event_damage' => '4',
            'event_recipient' => '2'
        ]); 

        //Mini uzi events
        DB::table('pvp_item_events')->insert([
            'item_id' => '3', // Uzi
            'event_description_part_one' => 'fired a shot from his/her Uzi and hit',
            'event_description_part_two' => 'in the arm dealing 10 damage.',
            'event_chance' => '70',
            'event_damage' => '10',
            'event_recipient' => '2'
        ]); 

        DB::table('pvp_item_events')->insert([
            'item_id' => '3', // Uzi
            'event_description_part_one' => 'fired a shot from his/her Uzi and hit',
            'event_description_part_two' => 'in the head dealing 45 damage.',
            'event_chance' => '10',
            'event_damage' => '45',
            'event_recipient' => '2' 
        ]); 

        DB::table('pvp_item_events')->insert([
            'item_id' => '3', // Uzi
            'event_description_part_one' => 'fired a shot from his/her Uzi and missed',
            'event_description_part_two' => 'completely.',
            'event_chance' => '20',
            'event_damage' => '0',
            'event_recipient' => '2'
        ]); 

        DB::table('pvp_item_events')->insert([
            'item_id' => '3', // Uzi
            'event_description_part_one' => 'fired a shot from his/her Uzi. The bullet ricocheted off the wall and doesn\'t hit',
            'event_description_part_two' => ' but instead himself/herself in the leg dealing 10 damage. Unlucky.',
            'event_chance' => '5',
            'event_damage' => '10',
            'event_recipient' => '1'
        ]); 

        DB::table('pvp_item_events')->insert([
            'item_id' => '3', // Uzi
            'event_description_part_one' => 'fired a shot from his/her Uzi and hit',
            'event_description_part_two' => 'in the leg dealing 4 damage.',
            'event_chance' => '70',
            'event_damage' => '4',
            'event_recipient' => '2'
        ]); 
    }
}