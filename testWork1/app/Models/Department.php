<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Department extends Model
{
    use HasFactory;
    protected $table = "departments";
    public $timestamps = true;
    protected $fillable = [
        'name',
        'started_at'
    ];

    public function workers(): belongsToMany
    {
        return $this->belongsToMany(Worker::class)->withPivot("workers.salary");
    }
}
