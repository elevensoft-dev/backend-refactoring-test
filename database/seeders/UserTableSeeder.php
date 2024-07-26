<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'example',
            'email' => 'example@elevensoft.dev',
            'password' => bcrypt('password')
        ]);
    }
}
