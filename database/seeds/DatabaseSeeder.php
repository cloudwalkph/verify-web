<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UserGroupsTableSeeder::class);
         $this->call(UsersTableSeeder::class);
         $this->call(PollsTableSeeder::class);
         $this->call(ProjectsTableSeeder::class);
    }
}
