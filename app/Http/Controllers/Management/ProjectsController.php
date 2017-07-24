<?php
namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\User;

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

        $locations = $input['locations'];
        $input['status'] = 'active';
        unset($input['locations']);

        $project = Project::create($input);

        foreach ($locations as $location) {
            $location['status'] = 'pending';
            $location['services'] = isset($location['services']) ? json_encode($location['services']) : json_encode([]);
            $location['target_hits'] = isset($location['target_hits']) ? $location['target_hits'] : 0;

            $project->locations()->create($location);
        }

        return redirect()->to('/management')->with('status', 'Successfully created new project');
    }

    public function edit($id)
    {
        $project = Project::find($id);
        $clients = User::where('user_group_id', 2)->get();

        return view('management.projects.update', compact('clients', 'project'));
    }

    public function update(CreateProjectRequest $request, $id)
    {
        $input = $request->all();

        $locations = $input['location'];
        $locations['status'] = 'pending';
        $input['status'] = 'active';
        unset($input['location']);

        $project = Project::where('id', $id)->first();

        $project->update($input);
        $project->locations()->update($locations);

        return redirect()->back()->with('status', 'Successfully updated project');
    }
}