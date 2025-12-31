<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'title',
        'company',
        'city',
        'type',
        'description',
        'deadline',
    ];
}
