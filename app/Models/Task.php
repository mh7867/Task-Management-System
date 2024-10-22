<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'priority',
        'start_date',
        'end_date',
        'status',
        'assigned_to',
        'created_by'
    ];

    protected $casts = [
        'assigned_to' => 'array',
    ];

    /**
     * Define a one-to-many relationship with the User model for the creator of the task.
     */
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Define a one-to-many relationship with the TaskFile model.
     */
    public function files()
    {
        return $this->hasMany(TaskFile::class);
    }

    /**
     * Define a one-to-many relationship with the TaskDiscussion model.
     */
    public function discussions()
    {
        return $this->hasMany(TaskDiscussion::class);
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id');
    }

    public function efforts()
    {
        return $this->hasMany(TaskEffort::class);
    }

}


