<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'session_id',
        'role',
        'content',
        'tool_call_id',
        'tool_name',
    ];
}
