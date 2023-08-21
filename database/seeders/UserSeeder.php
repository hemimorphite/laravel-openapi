<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($x = 1; $x <= 10; $x++) {
            User::create(['name' => 'User Account '.$x, 'email' => 'user'.$x.'@gmail.com', 'password' => Hash::make('helloworld')]);
        }
    }
}
