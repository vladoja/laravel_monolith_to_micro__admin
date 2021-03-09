<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    //
    public function index()
    {
        return User::all();
    }


    public function show($id)
    {
        return User::find($id);
    }

    public function store(UserCreateRequest $request)
    {
        // $user = User::create($request->all());
        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            // 'password' => Hash::make($request->input('password'))
            // Using default password, because users are created only by admin
            'password' => Hash::make(1234)
        ]);
        return response($user, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        // $user = User::find($id);
        // return  [
        //     'first_name' => $request->input('first_name'),
        //     'last_name' => $request->input('last_name'),
        //     'email' => $request->input('email'),
        //     'password' => Hash::make($request->input('password'))
        // ];

        $user = User::find($id);
        $user->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);
        return response($user, Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        User::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
