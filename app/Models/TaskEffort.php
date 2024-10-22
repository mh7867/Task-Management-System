<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskEffort extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'elapsed_time',
        'is_running',
        'start_time'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
