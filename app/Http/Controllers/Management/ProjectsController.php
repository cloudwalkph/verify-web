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
        $clients = User::where('user_group_id', 2)->get();

        return view('management.projects.create', compact('clients'));
    }

    public function store(CreateProjectRequest $request)
    {
        $input = $request->all();

//        $locations = $input['locations'];
        $input['status'] = 'pending';
//        unset($input['locations']);

        $project = Project::create($input);

//        foreach ($locations as $location) {
//            $location['status'] = 'pending';
//            $location['services'] = isset($location['services']) ? json_encode($location['services']) : json_encode([]);
//            $location['target_hits'] = isset($location['target_hits']) ? $location['target_hits'] : 0;
//
//            $project->locations()->create($location);
//        }

        if (! $project) {
            return redirect()->to('/management/projects')->with('status', 'Failed to create a project');
        }

        return redirect()->to('/management/projects/update/'.$project->id)->with('status', 'Successfully created new project');
    }

    public function edit($id)
    {
        $project = Project::find($id);
        $clients = User::where('user_group_id', 2)->get();
        $locations = ProjectLocation::where('project_id', $project->id)->get();
        $locations = $this->parseLocations($locations);

        return view('management.projects.update', compact('clients', 'project', 'locations'));
    }

    public function update(CreateProjectRequest $request, $id)
    {
        $input = $request->all();

        $project = Project::where('id', $id)->first();
        $project->update($input);

        return redirect()->back()->with('status', 'Successfully updated project');
    }

    private function parseLocations($locations)
    {
        $result = [];
        foreach ($locations as $location) {
            $reported = $location->manual_hits;
            $audited = $location->hits()->count();

            if ($reported > 0) {
                $percentage = ($audited / $reported) * 100;
            } else {
                $percentage = 0;
            }

            $result[] = [
                'id'                => $location->id,
                'project_id'        => $location->project_id,
                'date'              => Carbon::createFromTimestamp(strtotime($location->date))->toFormattedDateString(),
                'name'              => $location->name,
                'reported_hits'     => $reported,
                'audited_hits'      => $audited,
                'audit_percent'     => $percentage,
                'services'          => implode(", ", json_decode($location->services)),
                'vboxes'            => implode(", ", array_map_assoc(function($k, $v){
                        return $v['name'];
                    }, $location->videos->toArray())),
                'status'            => $location->status
            ];
        }

        return $result;
    }
}