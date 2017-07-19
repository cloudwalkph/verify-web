<?php

namespace App\Listeners;

use App\Events\NewFaceUploaded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use App\Models\Hit;
use Aws\Rekognition\RekognitionClient;

class ProcessFace implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewFaceUploaded  $event
     * @return void
     */
    public function handle(NewFaceUploaded $event)
    {
        $this->processImage($event->path, $event->locationId, 
            $event->timestamp, $event->userId);
    }

    private function processImage($path, $locationId, $timestamp, $userId)
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

        $hits = [];
        foreach ($faceDetails as $face) {
            $ageRange = $face['AgeRange'];
            $gender = $face['Gender'];

            // Create a hit
            $hit = Hit::create([
                'project_location_id'   => $locationId,
                'hit_timestamp'         => Carbon::createFromTimestamp(strtotime($timestamp))->toDateTimeString(),
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

            $his[] = $hit;
        }

        return $hits;
    }
}
