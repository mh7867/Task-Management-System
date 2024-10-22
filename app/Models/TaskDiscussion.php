<?php

// app/Models/TaskDiscussion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskDiscussion extends Model
{
    protected $fillable = ['message', 'user_id', 'task_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}

