<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\FriendRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendRequestList extends Controller
{

    public function friend_request_list(Request $request)
    {

        $friendRequests = FriendRequest::where('receiver_email', '=', auth()->user()->email)->get();

        if ($request->ajax()) {
            return response()->json(
                $friendRequests
            );
        }

        return view('Dashboard.friend_request_list');
    }


    public function accept_request(Request $request)
    {
        $friendRequest = FriendRequest::find($request->id);

        if ($friendRequest) {

            $friendRequest->status = 'accept';
            $friendRequest->update();

            return response()->json([
                'success' => 'FRIEND REQUEST ACCEPTED SUCCESSFULLY',
            ]);
        } else {
            return response()->json([
                'error' => 'FRIEND REQUEST NOT FOUND',
            ], 404);
        }
    }

    public function un_accept_request(Request $request)
    {
        $friendRequest = FriendRequest::find($request->id);

        if ($friendRequest) {

            $friendRequest->status = 'pending';
            $friendRequest->update();

            return response()->json([
                'success' => 'FRIEND REQUEST PENDING SUCCESSFULLY',
            ]);
        } else {
            return response()->json([
                'error' => 'FRIEND REQUEST NOT FOUND',
            ], 404);
        }
    }

    public function reject_request(Request $request)
    {
        $friendRequest = FriendRequest::find($request->id);

        if ($friendRequest) {

            $friendRequest->delete();

            return response()->json([
                'success' => 'FRIEND REQUEST REJECT SUCCESSFULLY',
            ]);
        } else {
            return response()->json([
                'error' => 'FRIEND REQUEST NOT FOUND',
            ], 404);
        }
    }

    public function rejectRequest(Request $request)
    {
        $friendRequest = FriendRequest::find($request->id);

        if ($friendRequest) {

            $friendRequest->delete();

            return response()->json([
                'success' => 'FRIEND REQUEST REJECT SUCCESSFULLY',
            ]);

        } else {

            return response()->json([
                'error' => 'FRIEND REQUEST NOT FOUND',
            ], 404);


        }
    }
}
