<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'conversation_id'; 
    protected $fillable = [
        'user1_id',
        'user2_id',
        'last_message_id',
    ];
}
