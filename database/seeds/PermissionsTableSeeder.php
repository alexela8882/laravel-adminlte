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
        DB::table('permissions')->insert([
            'name' => 'Administer roles & permissions',
            'guard_name' => 'web',
        ]);

        // Users - Admin
        DB::table('permissions')->insert([
            'name' => 'Show Users',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Users',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Users',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Users',
            'guard_name' => 'web',
        ]);

        // User Authorizations - Admin
        DB::table('permissions')->insert([
            'name' => 'Show User Authorizations',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit User Authorizations',
            'guard_name' => 'web',
        ]);

        // Company - Admin
        DB::table('permissions')->insert([
            'name' => 'Show Companies',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Companies',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Companies',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Companies',
            'guard_name' => 'web',
        ]);

        // Company - User/Admin
        DB::table('permissions')->insert([
            'name' => 'Show Files',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Create Files',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Edit Files',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'Delete Files',
            'guard_name' => 'web',
        ]);

        DB::table('permissions')->insert([
            'name' => 'View Files',
            'guard_name' => 'web',
        ]);

    }
}
