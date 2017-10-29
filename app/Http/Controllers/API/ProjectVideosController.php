<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\RawVideo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProjectVideosController extends Controller {
    public function getVideos(Request $request) {
        $videos = RawVideo::where('status', 'pending')
            ->get();

        return response()->json($videos);
    }

    public function processVideo(Request $request, $videoId) {
        $video = RawVideo::find($videoId);

        $video->update([
            'status'    => 'processing',
            'processing_time' => Carbon::now('Asia/Manila')->toDateTimeString()
        ]);

        return response()->json($video);
    }

    public function createVideoResult(Request $request, $videoId) {
        \Log::info($request->all());
        $video = \DB::transaction(function() use ($request, $videoId) {
            $video = RawVideo::find($videoId);

            // Create result
            $video->results()->create([
                'file'      => $request->get('file_name'),
                'result'    => json_encode($request->get('result')),
                'face_id'   => $request->get('face_id')
            ]);

            return $video;
        });

        return response()->json($video);
    }

    public function completeVideoProcessing(Request $request, $videoId) {
        $video = RawVideo::find($videoId);

        $video->update([
            'status'    => 'completed',
            'completed_time' => Carbon::now('Asia/Manila')->toDateTimeString()
        ]);

        return response()->json($video);
    }
}