<?php

namespace App\Events;

use App\Models\Receive;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReceivedOrder
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Receive */
    public $received;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($received)
    {
        $this->received = $received;
        Log::debug('ReceivedOrder event dispatched');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
