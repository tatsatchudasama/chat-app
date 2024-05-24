<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\PasswordChnage;
use App\Models\PasswordResetTokens;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class forgatPasswordController extends Controller
{
    public function password_change_email(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        $token = Str::random(64);

        PasswordResetTokens::updateOrCreate( 
            ['email' => $user->email],
            ['token' => $token],
        );

        $email = $user->email;

        Mail::mailer()
            ->to('tatsatcreativeinfoway@gmail.com')
            ->send(new PasswordChnage($email, $token));

        return response()->json([
            'success' => 'EMAIL SEND SUCCESSFULLY',
        ]);
    }

    public function password_reset_view($token){
        return view('Auth.passwordReset', compact('token'));
    }

    public function password_reset(Request $request, $token){

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        $passwordResetToken = DB::table("password_reset_tokens")->where('token', $token)->pluck('email')->first();

        if (!$passwordResetToken) {
            return response()->json([
                "status" => false,
                "errors" => ["INVALID TOKEN PROVIDED"]
            ]);
        }
    
        if ($request->email !== $passwordResetToken) {
            return response()->json([
                "status" => false,
                "errors" => ["THIS EMAIL DOSE NOT MATCH THE TOKEN PROVIDED"]
            ]);
        }

        $user = User::where('email', $passwordResetToken)->firstOrFail();

        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'success' => 'PASSWORD HAS BEEN RESET SUCCESSFULLY',
        ]);
    }


}
