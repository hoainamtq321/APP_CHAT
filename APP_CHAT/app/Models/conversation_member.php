<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conversation_member extends Model
{
    use HasFactory;
    protected $fillable = [
        'conversation_id',
        'user_id',
    ];

}
