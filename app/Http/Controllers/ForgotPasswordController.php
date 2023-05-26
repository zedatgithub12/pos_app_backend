<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\PasswordResetLink;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function forgotpassword(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }
        $token = Str::random(60);
        $addtotable = DB::table('password_reset_tokens')->where('email', $email)->first();
        if (!$addtotable) {
            DB::table('password_reset_tokens')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => now()
            ]);
            $message = "Reset your password";
            $data = ([
                'token' => $token,
                'message' => $message,

            ]);
            Mail::to($email)->send(new PasswordResetLink($data));

            return response()->json([
                'success' => true,
                'message' => 'Password reset link sent to your email',
            ], 200);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'We have already sent you reset link, check your inbox or spam folder',
            ], 404);

        }

    }
}