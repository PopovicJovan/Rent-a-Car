<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $carLocations;
    /**
     * Create a new event instance.
     */
    public function __construct($carLocations)
    {
        $this->carLocations = $carLocations;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     *
     */
    public function broadcastOn()
    {
        return new Channel('locations');
    }

    public function broadcastAs()
    {
        return 'locations';
    }

}
