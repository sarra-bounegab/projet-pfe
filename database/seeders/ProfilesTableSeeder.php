<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Inserting default profiles
        DB::table('profiles')->insert([
            ['id' => 1, 'description' => 'Administrator'],
            ['id' => 2, 'description' => 'Technician'],
            ['id' => 3, 'description' => 'Utilisateur'],
        ]);
    }
}
