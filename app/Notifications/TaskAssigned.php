<?php 
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;

class TaskAssigned extends Notification implements ShouldBroadcast
{
    protected $task;
    protected $assignedUsers;
    protected $assignedBy;

    public function __construct($task, $assignedUsers, $assignedBy)
    {
        $this->task = $task;
        $this->assignedUsers = $assignedUsers;
        $this->assignedBy = $assignedBy;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'title' => 'New Task Assigned',
            'message' => "You have been assigned to the task: '{$this->task->name}'.",
            'assigned_by' => $this->assignedBy->name,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'title' => 'New Task Assigned',
            'message' => "You have been assigned to the task: '{$this->task->name}'.",
            'assigned_by' => $this->assignedBy->name,
        ];
    }

    public function broadcastOn()
    {
        $channels = [];
        foreach ($this->assignedUsers as $user) {
            $channels[] = new PrivateChannel('notifications.' . $user->id);
        }
        return $channels;
    }

    public function broadcastAs()
    {
        return 'task-assigned';
    }
}
