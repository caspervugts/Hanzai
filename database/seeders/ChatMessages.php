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
            'user_id' => 1,
            'chat_type' => 'all',
            'message' => 'Hello world!',
            'created_at' => '2026-01-01 12:00:00',
        ]);
        
    }
}
