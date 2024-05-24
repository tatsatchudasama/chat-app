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

        // list of accept request user
        $friendRequests = FriendRequest::where('receiver_email', '=', auth()->user()->email)->get();

        // total request
        $total_request = FriendRequest::where('receiver_email', auth()->user()->email)->count();
        // total request accept
        $total_request_accept = FriendRequest::where('receiver_email', auth()->user()->email)
                                             ->where('status', 'accept')  
                                             ->count();

        if ($request->ajax()) {
            return response()->json(
                $friendRequests
            );
        }

        return view('Dashboard.friend_request_list', compact('total_request', 'total_request_accept'));
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
