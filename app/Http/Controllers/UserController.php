<?php

namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        // $user = User::create($request->all());
        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);
        return response($user, Response::HTTP_CREATED);
    }
}
