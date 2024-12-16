<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentLikeEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public $user_id;

    public function __construct($post_id, $user_id)
    {
        $this->user_id = $user_id;
        $post = Post::find($post_id);
        $this->message = auth()->user()->name.' liked your comment on post '.$post->title;
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
