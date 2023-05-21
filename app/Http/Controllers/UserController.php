<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,customer,employee'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $role = Role::where('role_name', $request->role)->first();
        $user->roles()->attach($role->id);

        return response()->json('User registered!');
    }

    public function login(Request $request)
    {
        // Validation
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication was successful. User is now logged in.
            return response()->json('Logged in!');
        } else {
            // Authentication failed. Let's return an error message.
            return response()->json('Invalid email or password.', 401);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json('Logged out!');
    }
}
