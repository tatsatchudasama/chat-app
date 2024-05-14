<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function registration_view(){
        return view('Auth.registration');
    }

    public function registration(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'phone' => 'required|digits:10|unique:users,phone',
            'address' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json([
            'success' => 'DATA INSERT SUCCESSFULLY',
        ]);

    }

    public function login_view(){
        return view('Auth.login');
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only(["email", "password"]))) {
            return response()->json([
                "status" => true, 
                "redirect" => route("dashboard_index"),
                'success' => 'USER LOGIN SUCCESSFULLY',
            ]);
        } else {
            return response()->json([
                "status" => false,
                "errors" => ["INVALID CREDENTIALS"]
            ]);
        }
    }

    public function logout()
    {
        auth()->logout();
        
        return response()->json([
            "status" => true, 
            "redirect" => route("dashboard_index"),
            'success' => 'USER LOGGED OUT SUCCESSFULLY',
        ]);
    }

}
