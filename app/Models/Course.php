<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 
        'vector', 
        'title', 
        'description', 
        'skills', 
        'duration', 
        'remote'
    ];

    protected $casts = [
        'vector' => 'array',
        'remote' => 'boolean'
    ];
}
