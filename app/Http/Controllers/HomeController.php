<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\ProjectShare;
use Illuminate\Http\Request;

class HomeController extends Controller
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
    public function index(Request $request)
    {
        $user = $request->user();

        $projects = Project::where('user_id', $user->id)
            ->get();

        $projects = $this->parseProjects($projects);

        $sharedProjects = ProjectShare::with('project')
            ->where('user_id', $user->id)
            ->get();

        return view('home', compact('projects', 'sharedProjects'));
    }

    private function parseProjects($projects)
    {
        $result = [];
        foreach ($projects as $project) {
            $locations = $project->locations;
            $reported = $this->getReportedHits($locations);
            $audited = $this->getAuditedHits($locations);
            $target = $this->getTargetHits($locations);

            if ($reported > 0) {
                $percentage = ($audited / $reported) * 100;
            } else {
                $percentage = 0;
            }

            $result[] = [
                'id'                => $project->id,
                'name'              => $project->name,
                'locations'         => $locations->toArray(),
                'active_runs'       => $this->countRunsBaseOnStatus($locations),
                'completed_runs'    => $this->countRunsBaseOnStatus($locations, 'completed'),
                'reported_hits'     => $reported,
                'audited_hits'      => $audited,
                'audit_percent'     => $percentage,
                'target'            => $target,
                'status'            => $project->status
            ];
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

    private function getAuditedHits($locations)
    {
        $count = 0;
        foreach ($locations as $location) {
            $count += $location->hits()->count();
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

    private function getReportedHits($locations)
    {
        $count = 0;
        foreach ($locations as $location) {
            $count += $location->manual_hits;
        }

        return $count;
    }
}
