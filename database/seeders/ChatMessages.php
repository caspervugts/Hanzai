<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ChatMessages extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('chat_messages')->insert([
            'user_id' => 3,
            'chat_type' => 'all',
            'message' => 'Hello world!',
            'created_at' => '2026-01-01 12:00:00',
        ]);
        
        DB::table('chat_messages')->insert([
            'user_id' => 1,
            'chat_type' => 'all',
            'message' => 'Hello Dasper!',
            'created_at' => '2026-01-01 12:01:00',
        ]);
        
        DB::table('chat_messages')->insert([
            'user_id' => 2,
            'chat_type' => 'all',
            'message' => 'Hello Noobs!',
            'created_at' => '2026-01-01 12:02:00',
        ]);

        DB::table('chat_messages')->insert([
            'user_id' => 2,
            'chat_type' => 'gang',
            'gang_id' => 2,
            'message' => 'Hello noob gang gang',
            'created_at' => '2026-01-01 12:03:00',
        ]);

        DB::table('chat_messages')->insert([
            'user_id' => 3,
            'chat_type' => 'gang',
            'gang_id' => 1,
            'message' => 'Hello Gucci gucci gang gang',
            'created_at' => '2026-01-01 12:04:00',
        ]);
    }
}
