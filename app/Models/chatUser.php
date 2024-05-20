<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chatUser extends Model
{
    use HasFactory;

    protected $table = 'chat';

    protected $fillable = [
        'id',
        'sender_email',
        'receiver_email',
        'message',
        'status',
    ];

}
