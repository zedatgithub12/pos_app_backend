<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    //

    public function resetpassword(Request $request)
    {
        $token = $request->token;
        $password = $request->password;
        $reset = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (!$reset) {
            return response()->json(['message' => 'Invalid token'], 404);
        }
        $user = User::where('email', $reset->email)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        User::where('email', $user->email)->update(['password' => Hash::make($password)]);
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successful',
        ], 200);

    }
}