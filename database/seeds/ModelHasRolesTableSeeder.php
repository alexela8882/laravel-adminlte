<?php

use Illuminate\Database\Seeder;

class ModelHasRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // For Default Super Admin
        DB::table('model_has_roles')->insert([
            'role_id' => '1',
            'model_type' => 'App\User',
            'model_id' => '1',
        ]);

        // For Default User
        DB::table('model_has_roles')->insert([
            'role_id' => '2',
            'model_type' => 'App\User',
            'model_id' => '2',
        ]);
    }
}
