<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $name = $faker->unique()->lastName();
        DB::table('users')
        ->insert([
            'name' => $name,
            'email' => $name.'@clsu2.edu.ph',
            'password'=> Hash::make('password'),
            'role' => 2,
        ]);

        foreach(range(1,20) as $index) 
        {
            $name = $faker->unique()->lastName();
            DB::table('users')
            ->insert([
                'name' => $name,
                'email' => $name.'@clsu2.edu.ph',
                'password'=> Hash::make('password'),
                'role' => 4,
            ]);
        }

        foreach(range(1,10) as $index) 
        {
            $name = $faker->unique()->lastName();
            DB::table('users')
            ->insert([
                'name' => $name,
                'email' => $name.'@clsu2.edu.ph',
                'password'=> Hash::make('password'),
                'role' => 3,
            ]);
        }
    }
}
