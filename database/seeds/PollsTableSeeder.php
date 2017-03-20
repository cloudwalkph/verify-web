<?php

use Illuminate\Database\Seeder;

class PollsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $polls = [
            [
                'name'  => 'Age Group',
                'type'  => 'single-choice',
                'choices'   => [
                    [
                        'name'  => '15 - 20'
                    ],
                    [
                        'name'  => '20 - 25'
                    ],
                    [
                        'name'  => '26 - 30'
                    ],
                    [
                        'name'  => '31 - 35'
                    ]
                ]
            ],
            [
                'name'  => 'Male',
                'type'  => 'single-choice',
                'choices'   => [
                    [
                        'name'  => 'Male'
                    ],
                    [
                        'name'  => 'Female'
                    ]
                ]
            ]
        ];

        foreach ($polls as $poll) {
            $poll['choices'] = json_encode($poll['choices']);
            \App\Models\Poll::create($poll);
        }
    }
}
