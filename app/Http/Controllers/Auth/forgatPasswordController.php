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
    public function forgot_password(){
        return view('Auth.forgatPassword');
    }

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

        return redirect()->route('login_view')->with('status', 'Email send successfully.');
        
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
            return redirect()->back()->withErrors(['email' => 'Invalid token provided.']);
        }
    
        if ($request->email !== $passwordResetToken) {
            return redirect()->back()->withErrors(['email' => 'This email does not match the token provided.']);
        }
    
        $user = User::where('email', $passwordResetToken)->firstOrFail();
    
        $user->password = bcrypt($request->password);
        $user->save();
    
        return redirect()->route('login_view')->with('status', 'Password has been reset successfully.');
    

    }
}
