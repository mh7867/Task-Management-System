<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRole extends Model
{
    use HasFactory;

    protected $primaryKey = 'employee_role_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['name'];
}
