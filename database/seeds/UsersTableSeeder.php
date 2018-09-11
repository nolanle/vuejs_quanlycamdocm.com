<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        User::create([
            'name'          => 'Administrator',
            'username'      => 'admin',
            'email'         => 'admin@example.com',
            'password'      => bcrypt('123456'),
            'company_id'    => 1,
            'activated'     => 1,
        ]);
    }
}
