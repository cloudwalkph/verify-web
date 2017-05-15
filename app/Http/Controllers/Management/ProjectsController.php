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

        return view('management.index', compact('projects'));
    }

    public function create()
    {
        $clients = User::where('user_group_id', 2)->get();

        return view('management.projects.create', compact('clients'));
    }

    public function store(CreateProjectRequest $request)
    {
        $input = $request->all();

        $locations = $input['location'];
        $locations['status'] = 'pending';
        $input['status'] = 'active';
        unset($input['location']);

        $project = Project::create($input);
        $project->locations()->create($locations);

        return redirect()->back()->with('status', 'Successfully created new project');
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