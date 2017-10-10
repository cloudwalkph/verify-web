<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewVideoUploaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $video;
    public $projectId;
    public $locationId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($video, $projectId, $locationId)
    {
        $this->video = $video;
        $this->projectId = $projectId;
        $this->locationId = $locationId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
