<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\chatUser;
use App\Models\FriendRequest;
use Illuminate\Support\Facades\Auth;

class chatUserController extends Controller
{
    public function chat_view(Request $request)
    {

        $friendRequests = FriendRequest::where('receiver_email', '=', auth()->user()->email)
            ->where('status', 'accept')
            ->get();

        if ($request->ajax()) {
            return response()->json($friendRequests);
        }

        return view('Dashboard.chat');
    }

    public function chat(Request $request)
    {

        // login user
        $login_user_email = auth()->user()->email;
        // receiver user email
        $receiver_user_email = $request->input('receiver_user_email');
        $receiver_user_email_find = FriendRequest::find($receiver_user_email);

        if (!$receiver_user_email_find) {
            return response()->json([
                'error' => 'User not found with the provided email',
            ], 404);
        }

        $message = new ChatUser();

        $message->message = $request->message;
        $message->sender_email = $login_user_email;
        $message->receiver_email = $receiver_user_email_find->sender_email;

        $message->save();

        return response()->json([
            'success' => 'USER MESSAGE SEND SUCCESSFULLY',
        ]);
    }

    // public function get_message(Request $request)
    // {
    //     $login_user_email = auth()->user()->email;

    //     $receiver_user_email = $request->input('receiver_user_emails');

    //     $get_messages = ChatUser::where('sender_email', $login_user_email)
    //                             ->where('receiver_email', $receiver_user_email)
    //                             ->get();

    //     $send_messages = ChatUser::where('receiver_email', $login_user_email)
    //                             ->where('sender_email', $receiver_user_email)
    //                             ->get();

    //     return response()->json(
    //         [
    //             'get_messages' => $get_messages,
    //             'send_messages' => $send_messages
    //         ]
    //     );

    // }


    public function get_message(Request $request)
    {
        $login_user_email = auth()->user()->email;

        $receiver_user_email = $request->input('receiver_user_emails');

        $get_messages = ChatUser::where('sender_email', $login_user_email)
                                ->where('receiver_email', $receiver_user_email)
                                ->get();

        $send_messages = ChatUser::where('receiver_email', $login_user_email)
                                ->where('sender_email', $receiver_user_email)
                                ->get();

        $all_messages = $get_messages->merge($send_messages);

        return response()->json(
            [
                'get_messages' => $get_messages,
                'send_messages' => $send_messages
            ]
        );

    }
}


