<?php

namespace App\Http\Controllers;

use App\Models\Hit;
use App\Models\Project;
use App\Models\ProjectLocation;
use App\Models\UserLocation;
use Carbon\Carbon;
use Geocoder\Formatter\StringFormatter;
use Illuminate\Http\Request;

class GpsReportController extends Controller
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

        $startDate = Carbon::createFromTimestamp(strtotime($location->date))->hour(6)->toDateTimeString();
        $endDate = Carbon::createFromTimestamp(strtotime($location->date))->hour(19)->toDateTimeString();
        $locations = $this->getLocationsPerHour($startDate, $endDate, $locationId);

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

    private function getLocationsPerHour($startDate, $endDate, $locationId)
    {
        $locations = UserLocation::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->where('project_location_id', $locationId)
            ->get()
            ->groupBy(function($d) {
                return Carbon::parse($d->created_at)->format('Y-m-d H');
            });

        $result = [];
        foreach ($locations as $location) {
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

    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     *
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    private function haversineGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }
}
