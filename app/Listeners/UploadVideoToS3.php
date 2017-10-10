<?php

namespace App\Listeners;

use App\Events\NewVideoUploaded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use League\Flysystem\Adapter\Local;

class UploadVideoToS3 implements ShouldQueue
{

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'uploader';

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
     * @param  NewVideoUploaded  $event
     * @return void
     */
    public function handle(NewVideoUploaded $event)
    {
        $file = \Storage::disk('local')->get($event->video);

        $s3 = \Storage::drive('s3')->put('videos/'.$event->projectId.'/'.$event->locationId, $file, [
            'visibility' => 'public'
        ]);

        \Log::info('Uploading ' . $event->video);
        \Log::info($s3);

        // Remove locally
        if ($s3) {
            \Storage::disk('local')->delete($event->video);
        }
    }
}
