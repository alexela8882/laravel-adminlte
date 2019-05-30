<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Alexander',
            'last_name' => 'Flores',
            'username' => 'admin',
            'password' => bcrypt('admin'),
        ]);

        DB::table('users')->insert([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'user',
            'password' => bcrypt('user'),
        ]);
    }
}
