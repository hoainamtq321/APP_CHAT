<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class friend_request extends Model
{
    use HasFactory;
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'receiver_id',
        'status',
    ];
}
