<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ListenerCountUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $listenerCount;

    public function __construct($listenerCount)
    {
        $this->listenerCount = $listenerCount;
    }

    public function broadcastOn()
    {
        return new Channel('listener-count-updates');
    }

    public function broadcastAs()
    {
        return 'listener.count.updated';
    }
}

