<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'employee_role_id',
    ];

    /**
     * Get the role that owns the employee.
     */
    public function employeeRole()
    {
        return $this->belongsTo(EmployeeRole::class, 'employee_role_id', 'employee_role_id');
    }
}
