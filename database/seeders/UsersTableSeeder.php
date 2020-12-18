<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nbUsers = (int)$this->command->ask("How many of user you want generate ?", 10);

        User::factory()->count($nbUsers)->create();
    }
}
