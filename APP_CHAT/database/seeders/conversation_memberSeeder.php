<?php

namespace Database\Seeders;

use App\Models\conversation_member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class conversation_memberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conversation_members = [
            ['conversation_id' => 1, 'user_id' => 1],
            ['conversation_id' => 1, 'user_id' => 2],
            ['conversation_id' => 2, 'user_id' => 1],
            ['conversation_id' => 2, 'user_id' => 3],
            ['conversation_id' => 3, 'user_id' => 1],
            ['conversation_id' => 3, 'user_id' => 4],
            ['conversation_id' => 4, 'user_id' => 4],
            ['conversation_id' => 4, 'user_id' => 3],
        ];

        foreach ($conversation_members as $u) {
            conversation_member::create([
                'conversation_id' => $u['conversation_id'],
                'user_id' => $u['user_id'],
            ]);
        }
    }
}
