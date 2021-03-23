<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController
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
            'password' => Hash::make($request->input('password')),
            'role_id' => 1,
            'is_influencer' => 1
        ]);
        return response($user, Response::HTTP_CREATED);
    }

    public function user()
    {
        $user = \Auth::user();

        $resource = new UserResource($user);

        if ($resource->isInfluencer()) {
            return $resource;
        }

        return (new UserResource($user))->additional([
            'data' => ['permissions' => $user->permissions()]
        ]);
    }

    public function updateInfo(UpdateInfoRequest $request)
    {
        $user = \Auth::user();
        $user->update($request->only('first_name', 'last_name', 'email'));
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = \Auth::user();
        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }
}
