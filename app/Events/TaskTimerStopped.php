<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;

class TaskTimerStopped implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;
    public $elapsedTime;

    public function __construct(Task $task, $elapsedTime)
    {
        $this->task = $task;
        $this->elapsedTime = $elapsedTime;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('task.' . $this->task->id);
    }
}
