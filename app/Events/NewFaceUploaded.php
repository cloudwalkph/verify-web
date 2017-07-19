<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewFaceUploaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $path;
    public $locationId;
    public $timestamp;
    public $userId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($path, $locationId, $timestamp, $userId)
    {
        $this->path = $path;
        $this->locationId = $locationId;
        $this->timestamp = $timestamp;
        $this->userId = $userId;
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
