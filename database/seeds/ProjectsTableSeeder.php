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
                        'target_hits'   => 100,
                        'assigned_raspberry'    => ''
                    ],
                    [
                        'name'  => 'SM Makati',
                        'date'  => Carbon\Carbon::tomorrow(),
                        'status'    => 'on-going',
                        'target_hits'   => 100,
                        'assigned_raspberry'    => ''
                    ],
                    [
                        'name'  => 'SM Fairview',
                        'date'  => Carbon\Carbon::tomorrow(),
                        'status'    => 'on-going',
                        'target_hits'   => 100,
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
                        'target_hits'   => 100,
                        'assigned_raspberry'    => ''
                    ],
                    [
                        'name'  => 'University of Makati',
                        'date'  => Carbon\Carbon::tomorrow(),
                        'status'    => 'on-going',
                        'target_hits'   => 100,
                        'assigned_raspberry'    => ''
                    ],
                    [
                        'name'  => 'Manila College',
                        'date'  => Carbon\Carbon::tomorrow(),
                        'status'    => 'on-going',
                        'target_hits'   => 100,
                        'assigned_raspberry'    => ''
                    ],
                    [
                        'name'  => 'Pasay High School',
                        'date'  => Carbon\Carbon::tomorrow(),
                        'status'    => 'on-going',
                        'target_hits'   => 100,
                        'assigned_raspberry'    => ''
                    ]
                ]
            ]
        ];

        $faker = \Faker\Factory::create();

        foreach ($projects as $project) {
            $locations = $project['locations'];
            unset($project['locations']);

            $newProject = \App\Models\Project::create($project);

            foreach ($locations as $location) {
                $location['project_id'] = $newProject->id;
                $newLocation = \App\Models\ProjectLocation::create($location);

                $newLocation->users()->attach($faker->randomElement([3, 4]));
            }

            $newProject->polls()->attach([1, 2]);
        }
    }
}
