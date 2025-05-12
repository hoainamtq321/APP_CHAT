<?php

namespace Database\Seeders;

use App\Models\friend_request;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class friend_requestSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conversation_members = [
            ['conversation_id' => 1,'sender_id' => 1,  'receiver_id' => 2],
            ['conversation_id' => 2,'sender_id' => 1,  'receiver_id' => 3],
            ['conversation_id' => 3,'sender_id' => 1,  'receiver_id' => 4],
            ['conversation_id' => 4,'sender_id' => 3,  'receiver_id' =>  4],
        ];

        foreach ($conversation_members as $u) {
            friend_request::create([
                'conversation_id' => $u['conversation_id'],
                'sender_id' => $u['sender_id'],
                'receiver_id' => $u['receiver_id'],
                'status' => 'accepted'
            ]);
        }
    }
}
