<?php

namespace App\Http\Controllers;

use App\Models\Hit;
use App\Models\Project;
use App\Models\ProjectLocation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProjectsController extends Controller
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
    public function show(Request $request, $projectId)
    {
        $project = Project::find($projectId);
        $locations = $project->locations;
        $reported = $this->getReportedHits($locations);
        $completed = $this->countRunsBaseOnStatus($locations, 'completed');

        return view('projects.show', compact('locations', 'project', 'answers', 'hits', 'completed', 'reported'));
    }

    public function showLocations(Request $request, $projectId)
    {
        $locations = ProjectLocation::where('project_id', $projectId)
            ->get();

        $reported = $this->getReportedHits($locations);
        $completed = $this->countRunsBaseOnStatus($locations, 'completed');

        $locations = $this->parseLocations($locations);

        $project = Project::find($projectId);

        return view('projects.show-locations', compact('locations', 'project', 'completed', 'reported'));
    }

    public function getHits(Request $request, $projectId)
    {

        if (! $request->has('location_id')) {
            $locations = ProjectLocation::where('project_id', $projectId)->get();
        } else {
            $locations = ProjectLocation::where('project_id', $projectId)
                ->where('id', $request->get('location_id'))->get();

        }

        $locationIds = [];
        foreach ($locations as $location) {
            $locationIds[] = $location->id;
        }

        $hits = Hit::whereIn('project_location_id', $locationIds)
            ->get();

        $hits = $this->parseHits($hits);

//        \Cache::add()

        return response()->json($hits, 200);
    }

    public function getDemographics(Request $request, $projectId)
    {
        if (! $request->has('location_id')) {
            $locations = ProjectLocation::where('project_id', $projectId)->get();
        } else {
            $locations = ProjectLocation::where('project_id', $projectId)
                ->where('id', $request->get('location_id'))->get();
        }

        $locationIds = [];
        foreach ($locations as $location) {
            $locationIds[] = $location->id;
        }

        $hits = Hit::whereIn('project_location_id', $locationIds)
            ->get();

        $answers = $this->parseAnswers($hits);

        return response()->json($answers, 200);
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
                'project_type'      => $location->project_type,
                'date'              => Carbon::createFromTimestamp(strtotime($location->date))->toFormattedDateString(),
                'name'              => $location->name,
                'reported_hits'     => $reported,
                'audited_hits'      => $audited,
                'audit_percent'     => $percentage,
                'status'            => $location->status
            ];
        }

        return $result;
    }

    private function parseHits($hits)
    {
        $result = [];
        foreach ($hits as $hit) {
            $hit['hit_timestamp'] = Carbon::createFromTimestamp(strtotime($hit['hit_timestamp']))
                ->minute(0)
                ->second(0)
                ->toDateTimeString();

            $result[] = $hit;
        }

        return $result;
    }

    private function parseAnswers($hits)
    {
        $result = [];
        foreach ($hits as $hit) {
            $hit['hit_timestamp'] = Carbon::createFromTimestamp(strtotime($hit['hit_timestamp']))->minute(0)
                ->toDateTimeString();

            foreach ($hit['answers'] as $answer) {
                $result[] = $answer;
            }
        }

        return $result;
    }

    private function countRunsBaseOnStatus($locations, $status = 'on-going')
    {
        $count = 0;
        foreach ($locations as $location) {
            if ($location->status === $status) {
                $count++;
            }
        }

        return $count;
    }

    private function getReportedHits($locations)
    {
        $count = 0;
        foreach ($locations as $location) {
            $count += $location->manual_hits;
        }

        return $count;
    }
}
