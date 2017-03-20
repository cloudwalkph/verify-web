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
        $users = [
            [
                'user_group_id' => 1,
                'email' => 'admin@verify.com',
                'password'  => bcrypt('password'),
                'profile'   => [
                    'first_name'    => 'marc',
                    'last_name'     => 'medina',
                    'gender'        => 'male',
                    'birthdate'     => \Carbon\Carbon::today()->toDateString()
                ]
            ],
            [
                'user_group_id' => 2,
                'email' => 'alleo@verify.com',
                'password'  => bcrypt('password'),
                'profile'   => [
                    'first_name'    => 'alleo',
                    'last_name'     => 'indong',
                    'gender'        => 'male',
                    'birthdate'     => \Carbon\Carbon::today()->toDateString()
                ]
            ],
            [
                'user_group_id' => 3,
                'email' => 'ba1@verify.com',
                'password'  => bcrypt('password'),
                'profile'   => [
                    'first_name'    => 'rina',
                    'last_name'     => 'martez',
                    'gender'        => 'female',
                    'birthdate'     => \Carbon\Carbon::today()->toDateString()
                ]
            ],
            [
                'user_group_id' => 3,
                'email' => 'ba2@verify.com',
                'password'  => bcrypt('password'),
                'profile'   => [
                    'first_name'    => 'bea',
                    'last_name'     => 'santos',
                    'gender'        => 'female',
                    'birthdate'     => \Carbon\Carbon::today()->toDateString()
                ]
            ]
        ];

        foreach ($users as $data) {
            $user = \App\User::create([
                'user_group_id' => $data['user_group_id'],
                'email'     => $data['email'],
                'password'  => $data['password']
            ]);

            $user->profile()->create($data['profile']);
        }
    }
}
