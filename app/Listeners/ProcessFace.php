<?php

namespace App\Listeners;

use App\Events\NewFaceUploaded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use App\Models\Hit;
use Aws\Rekognition\RekognitionClient;
use Aws\Rekognition\Exception\RekognitionException;

class ProcessFace implements ShouldQueue
{
    protected $rekog;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $args = [
            'region'  => 'us-east-1',
            'version'   => 'latest'
        ];

        $this->rekog = new RekognitionClient($args);
    }

    /**
     * Handle the event.
     *
     * @param  NewFaceUploaded  $event
     * @return void
     */
    public function handle(NewFaceUploaded $event)
    {
        $collection = $this->findOrCreateCollection($event->locationId);

        $this->processImage($event->path, $event->locationId, 
            $event->timestamp, $event->userId);
    }

    private function findOrCreateCollection($locationId) 
    {
        $collectionName = 'collection-'.$locationId;

        try {
            $result = $this->rekog->createCollection([
                'CollectionId'  => $collectionName
            ]);

            return $result;
        } catch (RekognitionException $e) {
            \Log::info('error on getting collection');

            return null;
        }
    }

    private function hasMatch($face, $collectionName) {
        $result = $this->rekog->searchFaces([
            'CollectionId' => $collectionName,
            'FaceId' => $face['Face']['FaceId'],
            'FaceMatchThreshold' => 80,
            'MaxFaces' => 10,
        ]);

        \Log::info($result);

        return count($result->get('FaceMatches')) <= 0 ? false : true;
    }

    private function processImage($path, $locationId, $timestamp, $userId)
    {
        $collectionName = 'collection-'.$locationId;
        $file = \Storage::drive('s3')->get($path);

        $result = $this->rekog->indexFaces([
            'CollectionId'  => $collectionName,
            'ExternalImageId'   => uniqid(),
            'Image' => [
                'Bytes' => $file
            ],
            'DetectionAttributes' => ['ALL']
        ]);

        $faceDetails = $result->get('FaceRecords');
        if ($result->count() <= 0) {
            return;
        }

        \Log::info($result);

        $hits = [];
        foreach ($faceDetails as $face) {
            if ($this->hasMatch($face, $collectionName)) {
                continue;
            }

            $faceDetail = $face['FaceDetail'];
            $ageRange = $faceDetail['AgeRange'];
            $gender = $faceDetail['Gender'];

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
