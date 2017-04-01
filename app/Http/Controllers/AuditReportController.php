<?php

namespace App\Http\Controllers;

use App\Models\Hit;
use App\Models\Project;
use App\Models\ProjectLocation;
use Illuminate\Http\Request;

class AuditReportController extends Controller
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
        $hits = Hit::with('answers')
            ->where('project_location_id', $locationId)
            ->get();

        $answers = $this->parseAnswers($hits->toArray());

        return view('projects.reports.audit', compact('location', 'project', 'hits', 'answers'));
    }

    private function parseAnswers($hits)
    {
        $result = [];
        foreach ($hits as $hit) {
            foreach ($hit['answers'] as $answer) {
                $result[] = $answer;
            }
        }

        return $result;
    }

    public function preview(Request $request, $projectId, $locationId)
    {
//        $user = $request->user();

        $location = ProjectLocation::where('id', $locationId)
            ->first();

        $project = Project::find($projectId);
        $hits = Hit::with('answers')
            ->where('project_location_id', $locationId)
            ->get();

        $answers = $this->parseAnswers($hits->toArray());

        return view('projects.reports.print.audit', compact('location', 'project', 'hits', 'answers'));
    }
}
