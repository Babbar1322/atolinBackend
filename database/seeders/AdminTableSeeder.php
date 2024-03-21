<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@demo.com',
            'password' => bcrypt('123456'),
            'utype' => 'admin'
        ]);
    }
}
