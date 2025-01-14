<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department_worker extends Model
{
    protected $table = "department_worker";
    public $timestamps = true;
    protected $fillable = [
        'department_id',
        'worker_id'
    ];
}
