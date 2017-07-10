<?php

namespace App\Http\Controllers;

use App\Models\Hit;
use App\Models\Project;
use App\Models\ProjectLocation;
use App\Models\Video;
use Aws\Rekognition\RekognitionClient;
use Carbon\Carbon;
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
        $hits = Hit::with('answers')
            ->where('project_location_id', $locationId)
            ->get();

        $auto = Hit::with('answers')
            ->where('auto', 1)
            ->where('project_location_id', $locationId)
            ->paginate(20);

        $answers = $this->parseAnswers($hits->toArray());
        $hits = $this->parseHits($hits);

        $videos = Video::where('project_location_id', $locationId)
            ->get();

        $services = $location->services ? json_decode($location->services) : [];

        return view('projects.locations.show',
            compact('location', 'project', 'hits',
                'answers', 'videos', 'services', 'auto'));
    }

    public function faceUpload(Request $request, $projectId, $locationId)
    {
        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $filename = uniqid() . '-' . $file->getClientOriginalName();

            // Upload to s3
            $path = \Storage::drive('s3')->putFileAs('faces', $file, $filename, 'public');
            $this->processImage($path, $locationId);

            return response()->json('success', 200);
        }

        return response()->json('error', 400);
    }

    private function processImage($path, $locationId)
    {
        $file = \Storage::drive('s3')->get($path);

        $args = [
            'region'  => 'us-east-1',
            'version'   => 'latest'
        ];

        $rekog = new RekognitionClient($args);
        $result = $rekog->detectFaces([
            'Image' => [
                'Bytes' => $file
            ],
            'Attributes' => ['ALL']
        ]);

        $faceDetails = $result->get('FaceDetails');
        if ($result->count() <= 0) {
            return;
        }
        $firstFace = $faceDetails[0];
        $ageRange = $firstFace['AgeRange'];
        $gender = $firstFace['Gender'];

        // Create a hit
        $hit = Hit::create([
            'project_location_id'   => $locationId,
            'hit_timestamp'         => Carbon::today('Asia/Manila')->toDateTimeString(),
            'image'                 => $path,
            'user_id'               => 1,
            'auto'                  => 1
        ]);

        $hit->answers()->create([
            'poll_id'   => 1,
            'value'     => $ageRange['Low'] . '-' . $ageRange['High']
        ]);

        $hit->answers()->create([
            'poll_id'   => 2,
            'value'     => $gender['Value']
        ]);

        return $hit;
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
}
