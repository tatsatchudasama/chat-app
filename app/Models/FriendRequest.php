<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    use HasFactory;

    protected $table = 'friend_request';

    protected $fillable = [
        'id',
        'sender_email',
        'receiver_email',
        'status',
    ];
}
