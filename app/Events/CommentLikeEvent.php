<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentLikeEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public $user_id;

    public function __construct($comment_id, $post_id, $user_id)
    {
        $this->user_id = $user_id;
        $this->message = 'User '.auth()->id().' liked your comment '.$comment_id.' on post '.$post_id;
    }

    public function broadcastOn()
    {
        return 'user.'.$this->user_id;
    }

    public function broadcastAs()
    {
        return 'notification-commentlike';
    }
}
