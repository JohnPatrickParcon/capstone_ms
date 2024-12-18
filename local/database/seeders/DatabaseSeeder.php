<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'test',
            'email' => 'test@clsu2.edu.ph',
            'role' => '4',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'test2',
            'email' => 'test2@clsu2.edu.ph',
            'role' => '4',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'test3',
            'email' => 'test3@clsu2.edu.ph',
            'role' => '4',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'test4',
            'email' => 'test4@clsu2.edu.ph',
            'role' => '4',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'test5',
            'email' => 'test5@clsu2.edu.ph',
            'role' => '4',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'coordinator',
            'email' => 'coordinator@clsu2.edu.ph',
            'role' => '2',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'instructor1',
            'email' => 'instructor1@clsu2.edu.ph',
            'role' => '3',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'instructor2',
            'email' => 'instructor2@clsu2.edu.ph',
            'role' => '3',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'instructor3',
            'email' => 'instructor3@clsu2.edu.ph',
            'role' => '3',
            'password' => 'password',
        ]);
    }
}
