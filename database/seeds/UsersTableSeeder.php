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
            'username' => 'alexela8882',
            'password' => bcrypt('M15@2dwin0n7y'),
        ]);
    }
}
