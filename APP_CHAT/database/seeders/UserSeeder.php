<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
     public function run()
    {
        $users = [
            ['username' => 'nam', 'full_name' => 'HoaiNam', 'img' => 'img/nhilangthan.jpg'],
            ['username' => 'trang', 'full_name' => 'Như Tragn', 'img' => 'img/nhennhen.jpg'],
            ['username' => 'luyen', 'full_name' => 'Quang luyem', 'img' => 'img/ngokhong.jpg'],
            ['username' => 'huan', 'full_name' => 'Minh Huan', 'img' => 'img/natra.jpg'],
        ];

        foreach ($users as $u) {
            User::create([
                'username' => $u['username'],
                'password' => Hash::make('123456'),
                'full_name' => $u['full_name'],
                'img' => $u['img'],
            ]);
        }
    }
}
