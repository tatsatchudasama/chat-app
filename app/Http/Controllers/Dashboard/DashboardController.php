<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view('Dashboard.index');
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
    
    // public function edit_user($id) {
    //     $edit_user = User::find($id);
    
    //     if (!$edit_user) {
    //         return response()->json([
    //             'error' => 'USER NOT FOUND',
    //         ]);
    //     }

    //     return response()->json($edit_user);
    // }


    // public function update_user(Request $request, $id) {
    //     $request->validate([
    //         'name' => 'required',
    //         'email' => 'required|email|unique:users,email,' . $request->id,
    //         'phone' => 'required|digits:10|unique:users,phone,' . $request->id,
    //         'address' => 'required',
    //     ]);

    //     $user = User::find($id);
    
    //     if (!$user) {
    //         return response()->json(['error' => 'USER NOT FOUND']);
    //     }
    
    //     $user->update([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => $request->password,
    //         'phone' => $request->phone,
    //         'address' => $request->address,
    //     ]);
    
    //     return response()->json([
    //         'user' => $user,
    //         'success' => 'DATA UPDATE SUCCESSFULLY',
    //     ]);
        
    // }
    

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
