<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
Broadcast::channel('task.{taskId}', function ($user, $taskId) {
    return $user->tasks->contains($taskId);
}); 
Broadcast::channel('tasks.assigned.{userId}', function ($user, $userId) {
    return $user->id === (int) $userId;
});
