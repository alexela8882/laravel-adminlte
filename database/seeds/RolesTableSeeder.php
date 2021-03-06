<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        DB::table('roles')->insert([
            'name' => 'User',
            'guard_name' => 'web',
        ]);
    }
}
