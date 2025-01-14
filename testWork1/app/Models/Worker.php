<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Worker extends Model
{
    use HasFactory;
    protected $table = "workers";
    public $timestamps = true;
    protected $fillable = [
        'name',
        'salary'
    ];

}
