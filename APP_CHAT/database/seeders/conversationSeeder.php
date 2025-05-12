<?php

namespace Database\Seeders;

use App\Models\conversation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class conversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $converstation = [
            ['name' => 'Cuộc hội thoại giữa nam và trang'],
            ['name' => 'Cuộc hội thoại giữa nam và Luyện'],
            ['name' => 'Cuộc hội thoại giữa nam và Huấn'],
            ['name' => 'Cuộc hội thoại giữa Huấn và Luyện'],
        ];

        foreach ($converstation as $i) {
            conversation::create([
                'name' => $i['name'],
            ]);
        }
    }
}
