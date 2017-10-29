<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Hit;
use App\Models\Project;
use App\Models\ProjectLocation;
use App\Models\UserLocation;
use App\Models\Video;
use App\RawVideo;
use App\Traits\Cachable;
use App\Traits\GPSTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Events\NewFaceUploaded;

class ProjectLocationsController extends Controller
{
    use GPSTrait, Cachable;

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

        $raws = RawVideo::where('project_location_id', $locationId)->get();

        $services = $location->services ? json_decode($location->services) : [];

        $videos = Video::where('project_location_id', $locationId)
            ->get();


        $rawVideos = RawVideo::where('project_location_id', $locationId)
            ->where('status', 'completed')
            ->get();

        $processedData = [];
        foreach ($rawVideos as $raw) {
            $processedData = array_merge($processedData, $raw->results->toArray());
        }

        $genderData = [
            'male' => 0,
            'female' => 0
        ];

        $ageRangeData = [
            '5-9'     => 0,
            '10-14'   => 0,
            '15-19'   => 0,
            '20-24'   => 0,
            '25-29'   => 0,
            '30-34'   => 0,
            '35-39'   => 0,
            '40-44'   => 0,
            '45-49'   => 0,
            '50-54'   => 0,
            '55-59'   => 0,
            '60-100'   => 0
        ];

        foreach ($processedData as $item) {
            $raw = json_decode($item['result']);
            $faceDetails = $raw->FaceRecords[0]->FaceDetail;
            $gender = $faceDetails->Gender;
            $ageRange = $faceDetails->AgeRange;
            $age = $this->getAge($ageRange->Low, $ageRange->High);
            $ageCategory = $this->categorizeAgeRange($age);

            $ageRangeData[$ageCategory] = $ageRangeData[$ageCategory] + 1;

            if (strtolower($gender->Value) === 'male') {
                $genderData['male'] = (int) $genderData['male'] + 1;
            } else {
                $genderData['female'] = (int) $genderData['female'] + 1;
            }
        }

        return view('management.projects.locations.show-automated',
            compact('location', 'project', 'hits', 'services', 'videos', 'raws'));
    }

    private function getAge($low, $high)
    {
        $realAge = ((int)$low + (int)$high) / 2;

        return $realAge >= 40 ? ($realAge - 10) : $realAge;
    }

    private function categorizeAgeRange($age)
    {
        $age = (int) $age;

        if ($age >= 5 && $age <= 9) {
            return '5-9';
        }

        if ($age >= 10 && $age <= 14) {
            return '10-14';
        }

        if ($age >= 15 && $age <= 19) {
            return '15-19';
        }

        if ($age >= 20 && $age <= 24) {
            return '20-24';
        }

        if ($age >= 25 && $age <= 29) {
            return '25-29';
        }

        if ($age >= 30 && $age <= 34) {
            return '30-34';
        }

        if ($age >= 35 && $age <= 39) {
            return '35-39';
        }

        if ($age >= 40 && $age <= 44) {
            return '40-44';
        }

        if ($age >= 45 && $age <= 49) {
            return '45-49';
        }

        if ($age >= 50 && $age <= 54) {
            return '50-54';
        }

        if ($age >= 55 && $age <= 59) {
            return '55-59';
        }

        return '60-100';
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

        return view('management.projects.locations.show-videos',
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
        $firstUser = $location->users()->first();

        $to = json_decode($location->target_location);

        $startDate = Carbon::createFromTimestamp(strtotime($location->date))->hour(6)->toDateTimeString();
        $endDate = Carbon::createFromTimestamp(strtotime($location->date))->hour(19)->toDateTimeString();

        if (! $firstUser) {
            return response()->json([], 204);
        }

        $locations = UserLocation::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->where('user_id', $firstUser->id)
            ->orderBy('created_at', 'ASC')
            ->get();

        $result = [];
        foreach ($locations as $userLoc) {
//            $distance = $this->haversineGreatCircleDistance($userLoc->lat, $userLoc->lng, $to[0], $to[1]) / 1000;
//
//            if ($distance > 1.5) {
//                continue;
//            }

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

        $location = \DB::transaction(function() use ($input, $locationId) {

            $location = ProjectLocation::where('id', $locationId)->first();
            $input['services'] = json_encode($input['services']);

            // Videos
            if (isset($input['assigned_raspberries']) && count($input['assigned_raspberries']) > 0) {

                $videos = Video::where('project_location_id', $locationId)->get();
                Video::where('project_location_id', $locationId)->forceDelete();

                foreach ($input['assigned_raspberries'] as $key => $video) {
                    if (! $video) {
                        continue;
                    }

                    $video = $video ? $video : '';

                    $checkVideo = $videos->filter(function ($value, $key) use ($video) {
                        return $value->name === $video;
                    })->first();

                    $videoData = [
                        'name'          => $video,
                        'alias'         => isset($input['video_names'][$key]) ? $input['video_names'][$key] : '',
                        'status'        => isset($input['video_status'][$key]) ? $input['video_status'][$key] : 'pending',
                        'playback_name' => $checkVideo ? $checkVideo->playback_name : uniqid() .'-'.$video.'.mp4'
                    ];

                    $location->videos()->create($videoData);
                }
            }

            unset($input['assigned_raspberries']);
            unset($input['video_names']);
            unset($input['video_status']);

            $location->update($input);

            return $location;

        });



        return redirect()->back();
    }

    public function updateTeam(Request $request, $projectId, $locationId)
    {
        $input = $request->all();
        $bas = explode(',', $input['bas']);

        $location = ProjectLocation::where('id', $locationId)->first();
        $location->users()->detach();
        $location->users()->attach($bas);


        return redirect()->back();
    }

    public function destroy($projectId, $locationId)
    {
        ProjectLocation::find($locationId)->delete();

        $this->deleteCache('project-'.$projectId.'-location');

        return redirect()->back();
    }
}
