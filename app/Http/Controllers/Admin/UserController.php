<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\User;
use App\UserRole;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    //
    public function index()
    {
        \Gate::authorize('view', 'users');
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
        \Gate::authorize('edit', 'users');
        // $user = User::create($request->all());
        $user = User::create($request->only('first_name', 'last_name', 'email') + [
            // Using default password, because users are created only by admin
            'password' => Hash::make(1234)
        ]);
        UserRole::create(['user_id' => $user->id, 'role_id' => $request->input('role_id')]);
        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        \Gate::authorize('edit', 'users');
        $user = User::find($id);
        $user->update($request->only('first_name', 'last_name', 'email'));

        UserRole::where('user_id', $user->id)->delete();
        UserRole::create(['user_id' => $user->id, 'role_id' => $request->input('role_id')]);

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        \Gate::authorize('edit', 'users');
        User::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
