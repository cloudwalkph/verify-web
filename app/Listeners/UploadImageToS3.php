<?php

namespace App\Listeners;

use App\Events\NewHitCreatedSuccessfully;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UploadImageToS3 implements ShouldQueue
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
     * @param  NewHitCreatedSuccessfully  $event
     * @return void
     */
    public function handle(NewHitCreatedSuccessfully $event)
    {
        $hit = $event->hit;
        if ($hit) {
            $file = \Storage::disk('local')->get($hit->image);
            \Storage::disk('s3')->put($hit->image, $file, [
                'visibility' => 'public'
            ]);
        }
    }
}
