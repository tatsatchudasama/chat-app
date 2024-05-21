<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\Request;


class FriendRequestController extends Controller
{
    public function create_friend_request(Request $request){

        // login user
        $login_user_email = auth()->user()->email;
        // sent request user
        $receiver_user_email = $request->input('receiver_user_email');
        $receiver_user_email_find = User::find($receiver_user_email);

        // sent request user not found error message
        if (!$receiver_user_email_find){
            return response()->json([
                "status" => false,
                "errors" => ["USER NOT FOUND"]
            ]);
        }

        // request sent caked
        $existing_request = FriendRequest::where('sender_email', $login_user_email)
                                         ->where('receiver_email', $receiver_user_email_find->email)
                                         ->exists();
        // duplicate request error message create  
        if ($existing_request) {
            return response()->json([
                "status" => false,
                "errors" => ["USER ALREADY REQUEST SENT"]
            ]);
        }

        $friend_request = new FriendRequest;

        $friend_request->sender_email = $login_user_email;
        $friend_request->receiver_email = $receiver_user_email_find->email;
        
        $friend_request->save();

        return response()->json([
            'success' => 'USER REQUEST SEND SUCCESSFULLY',
        ]);

    }

}
