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
    public function showOverview(Request $request, $projectId)
    {
        $project = Project::find($projectId);
        $locations = $project->locations;
        $reported = $this->getReportedHits($locations);
        $completed = $this->countRunsBaseOnStatus($locations, 'completed');
        $target = $this->getTargetHits($locations);

        return view('projects.show-overview', compact('locations', 'target', 'project', 'answers', 'hits', 'completed', 'reported'));
    }

    public function show(Request $request, $projectId)
    {
        $locations = ProjectLocation::where('project_id', $projectId)
            ->get();

        $reported = $this->getReportedHits($locations);
        $target = $this->getTargetHits($locations);
        $completed = $this->countRunsBaseOnStatus($locations, 'completed');

        $locations = $this->parseLocations($locations);

        $project = Project::find($projectId);

        return view('projects.show', compact('locations', 'target', 'project', 'completed', 'reported'));
    }

    public function getHits(Request $request, $projectId)
    {
        $cacheKey = null;

        if (! $request->has('location_id')) {
            $locations = ProjectLocation::where('project_id', $projectId)->get();
            $cacheKey = "p-{$projectId}-hits";
        } else {
            $locations = ProjectLocation::where('project_id', $projectId)
                ->where('id', $request->get('location_id'))->get();

            $cacheKey = "p-{$projectId}-l-{$request->get('location_id')}-hits";
        }

        if (\Cache::has($cacheKey)) {
            $hits = \Cache::get($cacheKey);

            return response()->json(json_decode($hits), 200);
        }

        $locationIds = [];
        foreach ($locations as $location) {
            $locationIds[] = $location->id;
        }

        $hits = Hit::with('answers')->whereIn('project_location_id', $locationIds)
            ->get();

        $hits = $this->parseHits($hits);

        \Cache::add($cacheKey, json_encode($hits), (60 * 8));

        return response()->json($hits, 200);
    }

    public function getDemographics(Request $request, $projectId)
    {
        $cacheKey = null;

        if (! $request->has('location_id')) {
            $locations = ProjectLocation::where('project_id', $projectId)->get();
            $cacheKey = "p-{$projectId}-demographics";
        } else {
            $locations = ProjectLocation::where('project_id', $projectId)
                ->where('id', $request->get('location_id'))->get();

            $cacheKey = "p-{$projectId}-l-{$request->get('location_id')}-demographics";
        }

        if (\Cache::has($cacheKey)) {
            $answers = \Cache::get($cacheKey);

            return response()->json($answers, 200);
        }

        $locationIds = [];
        foreach ($locations as $location) {
            $locationIds[] = $location->id;
        }

        $hits = Hit::whereIn('project_location_id', $locationIds)
            ->get();

        $answers = $this->parseAnswers($hits);

//        \Cache::add($cacheKey, $answers, (60 * 8));

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
                'date'              => $location->date,
                'name'              => $location->name,
                'reported_hits'     => $reported,
                'audited_hits'      => $audited,
                'audit_percent'     => $percentage,
                'target_hits'       => $location->target_hits,
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

    private function getTargetHits($locations)
    {
        $count = 0;
        foreach ($locations as $location) {
            $count += $location->target_hits;
        }

        return $count;
    }
}
