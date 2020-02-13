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
        //
        $userCount = max((int)$this->command->ask('How many users would you like? ', 25),1);
        factory(App\User::class)->states('salim-khan')->create();
        factory(App\User::class, $userCount)->create();
    }
}
