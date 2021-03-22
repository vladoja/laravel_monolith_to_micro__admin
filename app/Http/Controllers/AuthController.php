<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('admin')->accessToken;

            $cookie = \cookie('jwt', $token, 3600);

            return response([
                'token' => $token
            ])->withCookie($cookie);
        }

        return response([
            'error' => 'Invalidne credentials'
        ], Response::HTTP_UNAUTHORIZED);
    }


    public function logout()
    {
        $cookie = \Cookie::forget('jwt');
        return response(['message' => 'success'])->withCookie($cookie);
    }
    

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->only('first_name', 'last_name', 'email') + [
            // Using default password, because users are created only by admin
            'password' => Hash::make($request->input('password'))
            // Default value pre Role je '3'=> 'Viewer' 
        ]+ [ 'role_id' => $request->input('role_id', 3)]);
        return response($user, Response::HTTP_CREATED);
    }
}
