<?php

namespace App\Http\Controllers;

use App\Models\Hit;
use App\Models\Project;
use App\Models\ProjectLocation;
use App\Models\UserLocation;
use App\Traits\GPSTrait;
use Carbon\Carbon;
use Geocoder\Formatter\StringFormatter;
use Illuminate\Http\Request;

class GpsReportController extends Controller
{
    use GPSTrait;

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

        $startDate = Carbon::createFromTimestamp(strtotime($location->date))->hour(6)->toDateTimeString();
        $endDate = Carbon::createFromTimestamp(strtotime($location->date))->hour(19)->toDateTimeString();
        $locations = $this->getLocationsPerHour($startDate, $endDate, $location);

        return view('projects.reports.gps', compact('location', 'locations', 'project', 'hits', 'answers'));
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

//    public function preview(Request $request, $projectId, $locationId)
//    {
////        $user = $request->user();
//
//        $location = ProjectLocation::where('id', $locationId)
//            ->first();
//
//        $project = Project::find($projectId);
//        $hits = Hit::with('answers')
//            ->where('project_location_id', $locationId)
//            ->get();
//
//        $answers = $this->parseAnswers($hits->toArray());
//
//        return view('projects.reports.print.gps', compact('location', 'project', 'hits', 'answers'));
//    }

    public function preview(Request $request, $projectId, $locationId)
    {
        $location = ProjectLocation::where('id', $locationId)->first();

        $startDate = Carbon::createFromTimestamp(strtotime($location->date))->hour(6)->toDateTimeString();
        $endDate = Carbon::createFromTimestamp(strtotime($location->date))->hour(19)->toDateTimeString();

        $locations = $this->getLocationsPerHour($startDate, $endDate, $locationId);

        return view('projects.reports.print.gps', compact('location', 'locations', 'startDate'));
    }

    private function getLocationsPerHour($startDate, $endDate, $projectLocation)
    {
        $locations = UserLocation::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->where('project_location_id', $projectLocation->id)
            ->get()
            ->groupBy(function($d) {
                return Carbon::parse($d->created_at)->format('Y-m-d H');
            });

        $to = json_decode($projectLocation->target_location);

        $result = [];
        foreach ($locations as $location) {
            $distance = $this->haversineGreatCircleDistance($location[0]->lat, $location[0]->lng, $to[0], $to[1]) / 1000;

            if ($distance > 0.5) {
                continue;
            }

            // Reverse geocoding
            $address = app('geocoder')
                ->reverse($location[0]->lat, $location[0]->lng)
                ->get();

            $formatter = new StringFormatter();

            $location[0]['formatted_address'] = $formatter->format($address->first(), '%S %n, %z %L');;

            $result[] = $location[0];
        }

        return $result;
    }
}
