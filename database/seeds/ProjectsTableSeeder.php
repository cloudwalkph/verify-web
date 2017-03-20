<?php

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = [
            [
                'user_id'   => 2,
                'name'      => 'Wyeth Promil',
                'status'    => 'active',
                'locations' => [
                    [
                        'name'  => 'SM Marikina',
                        'date'  => Carbon\Carbon::tomorrow(),
                        'status'    => 'on-going',
                        'assigned_raspberry'    => ''
                    ],
                    [
                        'name'  => 'SM Makati',
                        'date'  => Carbon\Carbon::tomorrow(),
                        'status'    => 'on-going',
                        'assigned_raspberry'    => ''
                    ],
                    [
                        'name'  => 'SM Fairview',
                        'date'  => Carbon\Carbon::tomorrow(),
                        'status'    => 'on-going',
                        'assigned_raspberry'    => ''
                    ]
                ]
            ],
            [
                'user_id'   => 2,
                'name'      => 'Ponds University',
                'status'    => 'active',
                'locations' => [
                    [
                        'name'  => 'Marikina High School',
                        'date'  => Carbon\Carbon::tomorrow(),
                        'status'    => 'on-going',
                        'assigned_raspberry'    => ''
                    ],
                    [
                        'name'  => 'University of Makati',
                        'date'  => Carbon\Carbon::tomorrow(),
                        'status'    => 'on-going',
                        'assigned_raspberry'    => ''
                    ],
                    [
                        'name'  => 'Manila College',
                        'date'  => Carbon\Carbon::tomorrow(),
                        'status'    => 'on-going',
                        'assigned_raspberry'    => ''
                    ],
                    [
                        'name'  => 'Pasay High School',
                        'date'  => Carbon\Carbon::tomorrow(),
                        'status'    => 'on-going',
                        'assigned_raspberry'    => ''
                    ]
                ]
            ]
        ];
    }
}
