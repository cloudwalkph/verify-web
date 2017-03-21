<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectLocation;
use Illuminate\Http\Request;

class ProjectLocationsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $projectId, $locationId)
    {
//        $user = $request->user();

        $location = ProjectLocation::where('id', $locationId)
            ->first();

        $project = Project::find($projectId);

        return view('projects.locations.show', compact('location', 'project'));
    }
}
