<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class TaskAssignedEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $task;
    public $userId;

    public function __construct(Task $task, $userId)
    {
        $this->task = $task;
        $this->userId = $userId; 
    }

    public function broadcastOn()
    {
        return new PrivateChannel('tasks.assigned.' . $this->userId);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->task->id,
            'name' => $this->task->name,
            'priority' => $this->task->priority,
            'start_date' => $this->task->start_date,
            'end_date' => $this->task->end_date
        ];
    }
}

