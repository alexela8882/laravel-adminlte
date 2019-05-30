<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // id#1
        DB::table('permissions')->insert([
            'name' => 'Administer roles & permissions',
            'guard_name' => 'web',
        ]);

        // Users - Admin;id#2
        DB::table('permissions')->insert([
            'name' => 'Show Users',
            'guard_name' => 'web',
        ]);

        // id#3
        DB::table('permissions')->insert([
            'name' => 'Create Users',
            'guard_name' => 'web',
        ]);

        // id#4
        DB::table('permissions')->insert([
            'name' => 'Edit Users',
            'guard_name' => 'web',
        ]);

        // id#5
        DB::table('permissions')->insert([
            'name' => 'Delete Users',
            'guard_name' => 'web',
        ]);

        // User Authorizations - Admin;id#6
        DB::table('permissions')->insert([
            'name' => 'Show User Authorizations',
            'guard_name' => 'web',
        ]);

        // id#7
        DB::table('permissions')->insert([
            'name' => 'Edit User Authorizations',
            'guard_name' => 'web',
        ]);

        // Company - Admin;id#8
        DB::table('permissions')->insert([
            'name' => 'Show Companies',
            'guard_name' => 'web',
        ]);

        // id#9
        DB::table('permissions')->insert([
            'name' => 'Create Companies',
            'guard_name' => 'web',
        ]);

        // id#10
        DB::table('permissions')->insert([
            'name' => 'Edit Companies',
            'guard_name' => 'web',
        ]);

        // id#11
        DB::table('permissions')->insert([
            'name' => 'Delete Companies',
            'guard_name' => 'web',
        ]);

        // Company - User/Admin;id#12
        DB::table('permissions')->insert([
            'name' => 'Show Files',
            'guard_name' => 'web',
        ]);

        // id#13
        DB::table('permissions')->insert([
            'name' => 'Create Files',
            'guard_name' => 'web',
        ]);

        // id#14
        DB::table('permissions')->insert([
            'name' => 'Edit Files',
            'guard_name' => 'web',
        ]);

        // id#15
        DB::table('permissions')->insert([
            'name' => 'Delete Files',
            'guard_name' => 'web',
        ]);

        // id#16
        DB::table('permissions')->insert([
            'name' => 'View Files',
            'guard_name' => 'web',
        ]);

    }
}
