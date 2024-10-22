<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class TaskMessagePosted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $message;
    public $task;

    public function __construct($user, $message, $task)
    {
        $this->user = $user;
        $this->message = $message;
        $this->task = $task;
    }

    public function broadcastOn() : Channel
    {
        return new Channel('task.' . $this->task->id);
    } 

    public function broadcastWith()
{
        return [
            'message' => $this->message,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar,
            ],
            'created_at' => now()->format('h:i A'),
        ];
    }    
}
