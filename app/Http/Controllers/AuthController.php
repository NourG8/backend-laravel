<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Auth;


class AuthController extends Controller
{
    
    public function Register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);
        $user = User::Create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']) ,
            'valid' => 'Non valid',
            'role' => 'User' 

        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;
        $response =[
            'user' => $user,
            'token' => $token
    ];
    return response($response,201);
    }


  
    
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Logged out'
        ];
    }


}

