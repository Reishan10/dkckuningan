<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

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
            [
                'id' => Uuid::uuid4()->toString(),
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'no_telepon' => '62895617545306',
                'password' => bcrypt('123456'),
                'type' => 0,
            ],

            [
                'id' => Uuid::uuid4()->toString(),
                'name' => 'Juri',
                'email' => 'juri@gmail.com',
                'no_telepon' => '62895617545308',
                'password' => bcrypt('123456'),
                'type' => 1,
            ],

            [
                'id' => Uuid::uuid4()->toString(),
                'name' => 'Panitia',
                'email' => 'panitia@gmail.com',
                'no_telepon' => '62895617545307',
                'password' => bcrypt('123456'),
                'type' => 2,
            ],

            [
                'id' => Uuid::uuid4()->toString(),
                'name' => 'Peserta',
                'email' => 'peserta@gmail.com',
                'no_telepon' => '62895617545309',
                'password' => bcrypt('123456'),
                'type' => 3,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
