<?php

namespace App\Http\Controllers;

use App\Events\NewVideoUploaded;
use App\Models\Hit;
use App\Models\Project;
use App\Models\ProjectLocation;
use App\Models\Video;
use Aws\Rekognition\RekognitionClient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Events\NewFaceUploaded;

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
     * Show the application dashboard manual hits.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $projectId, $locationId)
    {

        $location = ProjectLocation::where('id', $locationId)
            ->first();

        $services = json_decode($location->services);
        if (! in_array('manual', $services)) {
            if (in_array('automatic', $services)) {
                return redirect()->to('/projects/'.$projectId.'/locations/'.$locationId.'/automated');
            }

            if (in_array('gps', $services)) {
                return redirect()->to('/projects/'.$projectId.'/locations/'.$locationId.'/gps');
            }

            return redirect()->to('/projects/'.$projectId.'/locations/'.$locationId.'/videos');
        }

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

        return view('projects.locations.show', compact('location',
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

        return view('projects.locations.show-gps',
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

    public function videoUpload(Request $request, $projectId, $locationId)
    {
        $user = $request->user();

        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $filename = uniqid() . '-' . $locationId . '-' . $file->getClientOriginalName();
//            $path = \Storage::drive('s3')->putFileAs('videos/'.$projectId.'/'.$locationId, $file, $filename, 'public');
            $path = \Storage::drive('local')->putFileAs('videos', $file, $filename, 'public');

            $timestamp = $request->has('hit_timestamp') ? $request->get('hit_timestamp') : Carbon::today()->toDateTimeString();

            // Queue the image processing
            if ($path) {
                \Log::info('New Video ' . $path);
                event(new NewVideoUploaded($path, $projectId, $locationId));
            }
//            event(new NewFaceUploaded($path, $locationId, $timestamp, $user->id));

            return response()->json('success', 200);
        }

        return response()->json('error', 400);
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
