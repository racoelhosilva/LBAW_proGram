<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FollowEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
        $this->message = auth()->user()->name.' started following you';
    }

    public function broadcastOn()
    {
        return 'user.'.$this->user_id;
    }

    public function broadcastAs()
    {
        return 'notification-follow';
    }
}
