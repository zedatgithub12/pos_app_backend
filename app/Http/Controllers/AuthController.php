<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'success' => true,
            'data' => $users
        ], 200);
    }
    public function register(Request $request)
    {
        // $validatedData = $request->validate([
        //     'name' => 'required',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|min:8|confirmed',
        //     'role' => 'required|in:admin,manager'
        // ]);

        $email = User::where('email', $request->email)->first();

        if ($email) {
            return response()->json(['success' => false, 'message' => 'This email already taken.']);
        }
        $password = $request->password;

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($password);
        $user->role = $request->role;
        $user->email_verified_at = now(); // set the email_verified_at field to the current date and time
        $user->remember_token = Str::random(60); // generate a new remember token for the user


        if ($request->hasFile('profile')) {
            $profile = $request->file('profile');
            $filename = uniqid() . '.' . $profile->getClientOriginalExtension();

            // Store the image in the "public" disk using the generated filename
            Storage::disk('public')->put($filename, file_get_contents($profile));

            $user->profile = $filename;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Successfully added user',

        ], 201);
    }


    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid email or password']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid email or password']);
        }


        if ($user) {

            $token = $user->createToken('auth_token')->plainTextToken;
            if ($user->role === 'Admin') {
                $response = response()->json([
                    'success' => true,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => $user,
                ]);
            } else if ($user->role === 'Sales') {

                $token = $user->createToken('auth_token')->plainTextToken;
                $userWithStore = DB::table('users')
                    ->join('stores', 'users.id', '=', 'stores.manager_id')
                    ->where('users.id', '=', $user->id)
                    ->select('users.*', 'stores.id as store_id', 'stores.name as store_name')
                    ->first();

                $response = response()->json([
                    'success' => true,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => $userWithStore,
                ]);
            } else {
                // Handle other user roles or invalid roles as needed
                $response = response()->json([
                    'success' => false,
                    'message' => 'Invalid user role',
                ]);
            }
        } else {
            // Handle invalid user credentials as needed
            $response = response()->json([
                'success' => false,
                'message' => 'Invalid email or password',
            ]);
        }

        return $response;


        // return response()->json([
        //     'success' => true,
        //     'access_token' => $token,
        //     'token_type' => 'Bearer',
        //     'user' => $user,
        // ]);
    }
    public function logout()
    {
        Auth::logout();

        return response()->json([
            'message' => 'Logout successful'
        ], 200);
    }
    public function changepass(Request $request, string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->validate($request, [
            'oldPassword' => 'required',
            'newPassword' => 'required|string',
        ]);

        $hashedPassword = $user->password;

        if (Hash::check($request->oldPassword, $hashedPassword)) {

            $user->fill([
                'password' => Hash::make($request->newPassword)
            ])->save();
            return response()->json([
                'success' => true,
                'message' => 'Password Updated Successfully ',

            ], 201);

        } else {
            return response()->json(['success' => false, 'message' => 'password does not match'], 401);
        }

    }

    public function update(Request $request, $id)
    {

        $customer = User::find($id);

        if ($request->has('name')) {
            $customer->name = $request->name;
        }

        if ($request->has('email')) {
            $customer->email = $request->email;
        }

        if ($request->has('role')) {
            $customer->role = $request->role;
        }
        $customer->save();


        return response()->json([
            'success' => true,
            'message' => 'User info updated successfully',
            'data' => $customer
        ], 200);
    }
    public function destroy($id)
    {
        $customer = User::find($id);
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Record not found'], 404);
        }
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ], 200);

    }
}