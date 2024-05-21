<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index(){


        $user = Auth::user();
        return view('Dashboard.index', compact('user'));

        // $user = User::where('id', '=', auth()->user()->id)->get();

        // return view('Dashboard.index', compact('user'));
    }

    public function user_list(){
        $user_lists = User::where('role', '!=', 'admin')
                          ->where('id', '!=', auth()->user()->id)
                          ->get();

        return view('Dashboard.user_list', compact('user_lists'));
    }

    public function user_search(Request $request){

        $search = $request->input('text');

        $user_lists_search = User::where('name', 'LIKE', '%'.$search.'%')->get();

        return response()->json($user_lists_search);
    }

    public function edit_user($id) {
        // Find the user by ID
        $edit_user = User::find($id);
    
        // If user not found, return error response
        if (!$edit_user) {
            return response()->json([
                'error' => 'USER NOT FOUND',
            ]);
        }
    
        // Return user data
        return response()->json($edit_user);
    }
    
    public function update_user(Request $request, $id) {
        // Validate request data
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|digits:10|unique:users,phone,' . $id,
            'address' => 'required',
        ]);
    
        // Find the user by ID
        $user = User::find($id);
    
        // If user not found, return error response
        if (!$user) {
            return response()->json(['error' => 'USER NOT FOUND']);
        }
    
        // Update user data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();
    
        // Return success response
        return response()->json([
            'user' => $user,
            'success' => 'DATA UPDATED SUCCESSFULLY',
        ]);
    }

}
