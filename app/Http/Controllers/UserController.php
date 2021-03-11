<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    //
    public function index()
    {
        // return User::with('role')->paginate();
        $users =  User::with('role')->paginate();
        return UserResource::collection($users);
    }


    public function show($id)
    {
        // return User::with('role')->find($id);
        $user = User::find($id);
        return new UserResource($user);
    }

    public function store(UserCreateRequest $request)
    {
        // $user = User::create($request->all());
        $user = User::create($request->only('first_name', 'last_name', 'email', 'role_id') + [
            // Using default password, because users are created only by admin
            'password' => Hash::make(1234)
        ]);
        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::find($id);
        // $user->update([
        //     'first_name' => $request->input('first_name'),
        //     'last_name' => $request->input('last_name'),
        //     'email' => $request->input('email'),
        //     'password' => Hash::make($request->input('password'))
        // ]);
        $user->update($request->only('first_name', 'last_name', 'email', 'role_id'));
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        User::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function user()
    {
        return \Auth::user();
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
