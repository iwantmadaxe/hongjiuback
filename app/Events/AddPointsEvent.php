<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AddPointsEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $receiver;
    public $sponsor;
    public $type;
    public $price;
    public $id;

    /**
     * AddPointsEvent constructor.
     * @param User|null $receiver
     * @param User $sponsor
     * @param $type
     * @param int $price
     */
    public function __construct($receiver, User $sponsor, $type, $price = 0, $id=null)
    {
        $this->receiver = $receiver;
        $this->sponsor = $sponsor;
        $this->type = $type;
        $this->price = $price;
        $this->id = $id;
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
