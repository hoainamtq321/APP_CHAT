<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conversation extends Model
{
    use HasFactory;
    protected $primaryKey = "conversation_id";
    protected $fillable = [
        'name',
        'late_message',
    ];
}
