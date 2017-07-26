<?php
namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectLocation;
use App\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\CreateProjectRequest;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return view('management.projects.index', compact('projects'));
    }

    public function create()
    {

        return view('management.projects.create');
    }

    public function store(CreateProjectRequest $request)
    {
        $input = $request->all();
        $input['status'] = 'pending';

        $project = Project::create($input);

        if (! $project) {
            return redirect()->to('/management/projects')->with('status', 'Failed to create a project');
        }

        return redirect()->to('/management/projects/update/'.$project->id)->with('status', 'Successfully created new project');
    }

    public function edit($id)
    {
        $project = Project::find($id);

        $locations = ProjectLocation::where('project_id', $project->id)->get();
        $locations = $this->parseLocations($locations);
        $client = User::with('profile')->where('id', $project->user_id)
            ->first();

        $client = [
            'id'    => $client->id,
            'name'  => $client->profile->full_name
        ];

        return view('management.projects.update', compact('client', 'project', 'locations'));
    }

    public function update(CreateProjectRequest $request, $id)
    {
        $input = $request->all();

        $project = Project::where('id', $id)->first();
        $project->update($input);

        return redirect()->back()->with('status', 'Successfully updated project');
    }

    public function createLocations(Request $request, $id)
    {
        $input = $request->all();
        $result = null;

        \DB::transaction(function() use ($input, $id, &$result) {
            $data = [
                'project_id'    => $id,
                'name'          => $input['name'],
                'target_hits'   => isset($input['target_hits']) ? $input['target_hits'] : 0,
                'date'          => Carbon::createFromTimestamp(strtotime($input['date']))->toDateString(),
                'services'      => json_encode($input['services']),
                'assigned_raspberry'    => '',
                'status'        => 'pending',
                'category_id'   => 0
            ];

            $location = ProjectLocation::create($data);

            // Videos
            foreach ($input['assigned_raspberries'] as $key => $video) {
                $videoData = [
                    'name'      => $video,
                    'alias'     => isset($input['video_names'][$key]) ? $input['video_names'][$key] : '',
                    'status'    => 'pending'
                ];

                $location->videos()->create($videoData);
            }

            // Users
            $users = explode(',', $input['bas']);
            $location->users()->attach($users);

            $result = [
                'location'  => $location,
                'user'      => $location->user,
                'videos'    => $location->videos,
            ];

        });


        if (! $result) {
            return redirect()->back()->with('errors', ['Failed to create a location']);
        }

        $request->session()->flash('status', 'Successfully attached a location');

        return redirect()->back();
    }

    private function parseLocations($locations)
    {
        $result = [];
        foreach ($locations as $location) {
            $reported = $location->manual_hits;
            $audited = $location->hits()->count();
            $percentage = 0;

            if ($reported > 0) {
                $percentage = ($audited / $reported) * 100;
            }

            $services = '';
            if (! $location->services === 'null') {
                $services = implode(", ", json_decode($location->services));
            }

            $result[] = [
                'id'                => $location->id,
                'project_id'        => $location->project_id,
                'date'              => Carbon::createFromTimestamp(strtotime($location->date))->toFormattedDateString(),
                'name'              => $location->name,
                'reported_hits'     => $reported,
                'audited_hits'      => $audited,
                'audit_percent'     => $percentage,
                'services'          => $services,
                'vboxes'            => implode(", ", array_map_assoc(function($k, $v){
                        return $v['name'];
                    }, $location->videos->toArray())),
                'status'            => $location->status
            ];
        }

        return $result;
    }
}