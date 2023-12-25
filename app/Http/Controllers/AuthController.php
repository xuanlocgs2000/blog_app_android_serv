<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    //Register User 
    public function register(Request $request)
    {
        //validate field 
        $attrs = $request->validate([
            'name' => 'required|String',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'

        ]);
        //create user 
        $user = User::create([
            'name' => $attrs['name'],
            'email' => $attrs['email'],
            'password' => bcrypt($attrs['password'])
        ]);

        //return user và validate token 
        return response([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken
        ], 200);
    }
    //login user
    public function login(Request $request)
    {
        //validate field 
        $attrs = $request->validate([

            'email' => 'required|email',
            'password' => 'required|min:6'

        ]);
        //login
        if (!Auth::attempt($attrs)) {
            # code...
            return response([
                'message' => 'Invalid credentials'
            ], 403);
        }

        //return user và  token 
        return response([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken
        ], 200);
    }
    //logout
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Logout success'

        ], 200);
    }
    //get user 
    public function user()
    {
        return response([
            'user' => auth()->user()
        ], 200);
    }

    //update user
    public function update(Request $request)
    {
        $attrs = $request->validate([
            'name' => 'required|String'
        ]);
        $image = $this->saveImage($request->image, 'profiles');
        auth()->user()->update([
            'name' => $attrs['name'],
            'image' => $image
        ]);
        return response([
            'message' => 'user update success',
            'user' => auth()->user()

        ]);
    }
}
