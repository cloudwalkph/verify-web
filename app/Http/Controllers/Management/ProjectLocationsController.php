<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Hit;
use App\Models\Project;
use App\Models\ProjectLocation;
use App\Models\UserLocation;
use App\Models\Video;
use App\Traits\GPSTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Events\NewFaceUploaded;

class ProjectLocationsController extends Controller
{
    use GPSTrait;

    /**
     * Show the application dashboard manual hits.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $projectId, $locationId)
    {

        $location = ProjectLocation::where('id', $locationId)
            ->first();

        $project = Project::find($projectId);
        $hits = Hit::with('answers')
            ->where('auto', 0)
            ->where('project_location_id', $locationId)
            ->get();


        $answers = $this->parseAnswers($hits->toArray());
        $hits = $this->parseHits($hits);

        $services = $location->services ? json_decode($location->services) : [];
        $videos = Video::where('project_location_id', $locationId)
            ->get();

        return view('management.projects.locations.show', compact('location',
            'project', 'hits', 'answers', 'videos', 'services', 'videos'));
    }

    /**
     * Show the application dashboard for automated hits.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAutomated(Request $request, $projectId, $locationId)
    {

        $location = ProjectLocation::where('id', $locationId)
            ->first();

        $project = Project::find($projectId);
        $hits = Hit::with('answers')
            ->where('auto', 1)
            ->where('project_location_id', $locationId)
            ->limit(10)
            ->paginate();

        $services = $location->services ? json_decode($location->services) : [];

        $videos = Video::where('project_location_id', $locationId)
            ->get();

        return view('projects.locations.show-automated',
            compact('location', 'project', 'hits', 'services', 'videos'));
    }

    /**
     * Show the application dashboard for videos.
     *
     * @return \Illuminate\Http\Response
     */
    public function showVideos(Request $request, $projectId, $locationId)
    {
//        $user = $request->user();

        $location = ProjectLocation::where('id', $locationId)
            ->first();

        $project = Project::find($projectId);

        $videos = Video::where('project_location_id', $locationId)
            ->get();

        $services = $location->services ? json_decode($location->services) : [];

        return view('projects.locations.show-videos',
            compact('location', 'project', 'hits',
                'answers', 'videos', 'services', 'auto'));
    }

    /**
     * Show the application dashboard for gps.
     *
     * @return \Illuminate\Http\Response
     */
    public function showGPS(Request $request, $projectId, $locationId)
    {
//        $user = $request->user();

        $location = ProjectLocation::where('id', $locationId)
            ->first();

        $project = Project::find($projectId);

        $services = $location->services ? json_decode($location->services) : [];
        $videos = Video::where('project_location_id', $locationId)
            ->get();

        return view('management.projects.locations.show-gps',
            compact('location', 'project', 'videos', 'services'));
    }

    public function faceUpload(Request $request, $projectId, $locationId)
    {
        $user = $request->user();

        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $filename = uniqid() . '-' . $locationId . '-' . $file->getClientOriginalName();
            $path = \Storage::drive('s3')->putFileAs('faces/'.$locationId, $file, $filename, 'public');
            $timestamp = $request->has('hit_timestamp') ? $request->get('hit_timestamp') : Carbon::today()->toDateTimeString();

            // Queue the image processing
            event(new NewFaceUploaded($path, $locationId, $timestamp, $user->id));

            return response()->json('success', 200);
        }

        return response()->json('error', 400);
    }

    public function importGPSData(Request $request, $projectId, $locationId)
    {
        if (! $request->hasFile('gps_file')) {
            $request->session()->flash('error', 'No file to upload');
            return redirect()->back();
        }

        $results = [];
        \Excel::load($request->file('gps_file'), function($reader) use (&$results, $request, $locationId) {
            // reader methods
            $sheets = $reader->all();
            foreach ($sheets->toArray() as $sheet) {
                foreach ($sheet as $location) {
                    $latlng = explode(',', $location['llc']);
                    $data = [
                        'user_id'   => $request->get('user_id'),
                        'lat'   => $latlng[0],
                        'lng'   => $latlng[1],
                        'project_location_id'   => $locationId,
                        'created_at' => Carbon::createFromTimestamp(strtotime($location['time']))->toDateTimeString()
                    ];
                    $results[] = $data;
                    // Create Data
                    UserLocation::create($data);
                }
            }
        });
        return redirect()->back();
    }

    public function getGPSData(Request $request, $projectId, $locationId)
    {
        $location = ProjectLocation::where('id', $locationId)->first();
        $to = json_decode($location->target_location);

        $startDate = Carbon::createFromTimestamp(strtotime($location->date))->hour(6)->toDateTimeString();
        $endDate = Carbon::createFromTimestamp(strtotime($location->date))->hour(19)->toDateTimeString();

        $locations = UserLocation::where('project_location_id', $locationId)
            ->orderBy('created_at', 'ASC')
            ->get();

        $result = [];
        foreach ($locations as $userLoc) {
            $distance = $this->haversineGreatCircleDistance($userLoc->lat, $userLoc->lng, $to[0], $to[1]) / 1000;

            if ($distance > 1.5) {
                continue;
            }

            $result[] = $userLoc;
        }

        return response()->json($result, 200);
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

    public function update(Request $request, $projectId, $locationId)
    {
        $input = $request->all();
        $location = ProjectLocation::where('id', $locationId)->first();

        $location = $location->update($input);

        return redirect()->back();
    }

    public function destroy($projectId, $locationId)
    {
        ProjectLocation::find($locationId)->delete();

        return redirect()->back();
    }
}
